<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\TriageRecord;
use App\Models\DispensingRecord;
use App\Models\Medicine;
use App\Models\Batch;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * All roles now use the same shared views under resources/views/reports/.
     * The layout handles per-role sidebar/branding automatically via <x-sidebar />.
     */
    private function viewPath(string $report): string
    {
        return "reports.{$report}";
    }

    /**
     * Turn the "Generate" filter controls (range dropdown + from/to dates) into
     * an actual [start, end] date window. Returns [null, null] for "All Time".
     */
    private function resolveRange(Request $request): array
    {
        $range = $request->input('range', 'all');
        $from = $request->input('from');
        $to = $request->input('to');

        switch ($range) {
            case 'month':
                return [now()->startOfMonth(), now()->endOfDay()];
            case '7days':
                return [now()->subDays(7)->startOfDay(), now()->endOfDay()];
            case '30days':
                return [now()->subDays(30)->startOfDay(), now()->endOfDay()];
            case 'custom':
                $start = $from ? Carbon::parse($from)->startOfDay() : null;
                $end = $to ? Carbon::parse($to)->endOfDay() : null;
                return [$start, $end];
            default:
                return [null, null];
        }
    }

    /**
     * Values passed to every report view so the filter form remembers what
     * was selected ("sticky filters") after clicking Generate.
     */
    private function filterViewData(Request $request): array
    {
        return [
            'selectedRange' => $request->input('range', 'all'),
            'fromDate' => $request->input('from'),
            'toDate' => $request->input('to'),
        ];
    }

    // ==================================================
    // PATIENT RECORDS REPORT
    // ==================================================
    public function patient(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $patientQuery = Patient::query();
        $dispensingQuery = DispensingRecord::query();

        if ($start && $end) {
            $patientQuery->whereBetween('created_at', [$start, $end]);
            $dispensingQuery->whereBetween('dispense_date', [$start, $end]);
        }

        $totalPatients = (clone $patientQuery)->count();
        $newPatients = (clone $patientQuery)->where('created_at', '>=', now()->subDays(30))->count();
        $returningPatients = max(0, $totalPatients - $newPatients);

        $commonCase = (clone $dispensingQuery)->whereNotNull('diagnosis')
            ->where('diagnosis', '!=', '')
            ->select('diagnosis')
            ->groupBy('diagnosis')
            ->orderByRaw('COUNT(*) DESC')
            ->value('diagnosis') ?? 'N/A';

        $commonBarangay = (clone $patientQuery)->whereNotNull('barangay')
            ->where('barangay', '!=', '')
            ->select('barangay')
            ->groupBy('barangay')
            ->orderByRaw('COUNT(*) DESC')
            ->value('barangay') ?? 'N/A';

        $patients = (clone $dispensingQuery)->select(
                'patient_name as full_name',
                'age',
                'sex as gender',
                'barangay',
                'diagnosis',
                'dispense_date as date_visited'
            )
            ->orderByDesc('dispense_date')
            ->take(200)
            ->get()
            ->map(function ($row) {
                $row->date_visited = $row->date_visited ? Carbon::parse($row->date_visited)->format('M d, Y') : '-';
                $row->diagnosis = $row->diagnosis ?: '-';
                return $row;
            });

        return view($this->viewPath('patient'), array_merge(compact(
            'totalPatients', 'newPatients', 'returningPatients', 'commonCase', 'commonBarangay', 'patients'
        ), $this->filterViewData($request)));
    }

    // ==================================================
    // RISK-SCORING REPORT
    // ==================================================
    public function risk(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $base = TriageRecord::whereNotNull('risk_level');
        if ($start && $end) {
            $base->whereBetween('created_at', [$start, $end]);
        }

        $highRisk = (clone $base)->whereRaw('LOWER(risk_level) = ?', ['high'])->count();
        $moderateRisk = (clone $base)->whereRaw('LOWER(risk_level) = ?', ['medium'])->count();
        $lowRisk = (clone $base)->whereRaw('LOWER(risk_level) = ?', ['low'])->count();
        $totalRisk = (clone $base)->count();

        $labelMap = ['high' => 'High', 'medium' => 'Moderate', 'low' => 'Low'];
        $recommendationMap = [
            'high' => 'Needs Immediate Attention',
            'medium' => 'Monitor Patient',
            'low' => 'Stable - Routine Follow-up',
        ];

        $risks = (clone $base)->with('patient')->latest()->take(200)->get()->map(function ($record) use ($labelMap, $recommendationMap) {
            $key = strtolower($record->risk_level);
            $patient = $record->patient;
            return (object) [
                'patient_id' => $patient->patient_id ?? '-',
                'full_name' => $patient ? trim($patient->first_name . ' ' . $patient->last_name) : 'Unknown',
                'age' => $patient->age ?? '-',
                'assessment_date' => $record->created_at ? $record->created_at->format('M d, Y') : '-',
                'risk_level' => $labelMap[$key] ?? $record->risk_level,
                'assessment_result' => $record->symptoms ?: 'No findings recorded',
                'recommendation' => $recommendationMap[$key] ?? 'Review needed',
            ];
        });

        return view($this->viewPath('risk'), array_merge(compact(
            'highRisk', 'moderateRisk', 'lowRisk', 'totalRisk', 'risks'
        ), $this->filterViewData($request)));
    }

    // ==================================================
    // MEDICINE INVENTORY REPORT
    // ==================================================
    public function medicine(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        // Current stock levels/expiry are always a live snapshot - a date range
        // doesn't change what's physically on the shelf right now.
        $totalMedicines = Medicine::count();

        $medicinesWithStock = Medicine::withSum('batches', 'quantity')
            ->with(['batches' => fn ($q) => $q->orderBy('expiry_date')])
            ->get();

        $lowStock = $medicinesWithStock->filter(fn ($m) => ($m->batches_sum_quantity ?? 0) <= 10)->count();

        $expiring = Batch::whereDate('expiry_date', '<=', now()->addDays(30))
            ->whereDate('expiry_date', '>=', now())
            ->count();

        // "Most Used" is the one figure that genuinely reflects the selected date range.
        $mostUsedQuery = DispensingRecord::select('medicine_id')->whereNotNull('medicine_id');
        if ($start && $end) {
            $mostUsedQuery->whereBetween('dispense_date', [$start, $end]);
        }
        $mostUsedRow = $mostUsedQuery->groupBy('medicine_id')->orderByRaw('COUNT(*) DESC')->first();
        $mostUsed = $mostUsedRow ? optional(Medicine::find($mostUsedRow->medicine_id))->name : 'N/A';

        $medicines = $medicinesWithStock->map(function ($m) {
            $nearestExpiry = $m->batches->first()?->expiry_date;
            return (object) [
                'medicine_name' => $m->name,
                'stock' => $m->batches_sum_quantity ?? 0,
                'expiry_date' => $nearestExpiry ? Carbon::parse($nearestExpiry)->format('M d, Y') : 'N/A',
                'usage_level' => 'Normal',
            ];
        });

        return view($this->viewPath('medicine'), array_merge(compact(
            'totalMedicines', 'lowStock', 'expiring', 'mostUsed', 'medicines'
        ), $this->filterViewData($request)));
    }

    // ==================================================
    // DISPENSING OF MEDICINE REPORT
    // ==================================================
    public function dispensing(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $base = DispensingRecord::query();
        if ($start && $end) {
            $base->whereBetween('dispense_date', [$start, $end]);
        }

        $totalTransactions = (clone $base)->count();
        $totalDispensed = (clone $base)->sum('quantity_dispensed');
        $todayDispensed = DispensingRecord::whereDate('dispense_date', today())->sum('quantity_dispensed');

        $mostDispensedRow = (clone $base)->select('medicine_id')
            ->whereNotNull('medicine_id')
            ->groupBy('medicine_id')
            ->orderByRaw('SUM(quantity_dispensed) DESC')
            ->first();
        $mostDispensed = $mostDispensedRow ? optional(Medicine::find($mostDispensedRow->medicine_id))->name : 'N/A';

        $activePatients = (clone $base)->distinct('patient_name')->count('patient_name');

        $dispensings = (clone $base)->with('medicine')
            ->orderByDesc('dispense_date')
            ->take(200)
            ->get()
            ->map(function ($d) {
                return (object) [
                    'patient_id' => $d->patient_ptn ?: '-',
                    'patient_name' => $d->patient_name,
                    'medicine_name' => optional($d->medicine)->name ?? '-',
                    'quantity' => $d->quantity_dispensed,
                    'date_dispensed' => $d->dispense_date ? Carbon::parse($d->dispense_date)->format('M d, Y') : '-',
                    'dispensed_by' => $d->dispensed_by ?: '-',
                ];
            });

        return view($this->viewPath('dispensing'), array_merge(compact(
            'totalDispensed', 'todayDispensed', 'mostDispensed', 'activePatients', 'totalTransactions', 'dispensings'
        ), $this->filterViewData($request)));
    }

    // ==================================================
    // OPERATIONAL REPORT
    // ==================================================
    public function operational(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $triageQuery = TriageRecord::query();
        $logQuery = ActivityLog::query();
        if ($start && $end) {
            $triageQuery->whereBetween('created_at', [$start, $end]);
            $logQuery->whereBetween('created_at', [$start, $end]);
        }

        $totalConsultations = (clone $triageQuery)->count();
        $activeUsers = User::where('last_login_at', '>=', now()->subMinutes(30))->count();

        $peakPatientLoad = (clone $triageQuery)->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->orderByDesc('c')
            ->value('c') ?? 0;

        $systemTransactions = (clone $logQuery)->count();

        $operations = (clone $logQuery)->latest()->take(200)->get()->map(function ($log) {
            return (object) [
                'date' => $log->created_at ? $log->created_at->format('M d, Y h:i A') : '-',
                'activity' => $log->action ?: '-',
                'user' => $log->user_name ?: '-',
                'module' => $log->type ?: '-',
                'status' => 'Logged',
            ];
        });

        return view($this->viewPath('operational'), array_merge(compact(
            'totalConsultations', 'activeUsers', 'peakPatientLoad', 'systemTransactions', 'operations'
        ), $this->filterViewData($request)));
    }

    // ==================================================
    // LIGHTWEIGHT JSON COUNTERS - used by the reports landing page cards
    // ==================================================
    public function apiPatient()
    {
        return response()->json(['total' => Patient::count()]);
    }

    public function apiRisk()
    {
        return response()->json(['total' => TriageRecord::whereNotNull('risk_level')->count()]);
    }

    public function apiMedicine()
    {
        return response()->json(['total' => Medicine::count()]);
    }

    public function apiDispensing()
    {
        return response()->json(['total' => (int) DispensingRecord::sum('quantity_dispensed')]);
    }

    public function apiOperational()
    {
        return response()->json(['total' => ActivityLog::count()]);
    }

    // ==================================================
    // MEDICINE INVENTORY CATEGORY PAGE
    // ==================================================
    public function medicineCategory()
    {
        return view('reports.categories.medicine');
    }

    // ==================================================
    // MEDICINE INVENTORY STATUS REPORT
    // ==================================================
    public function medicineInventoryStatus(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $medicines = Medicine::orderBy('name')->get();

        $rows = $medicines->map(function ($med) use ($start, $end) {
            $totalIn = StockTransaction::where('medicine_id', $med->id)->where('type', 'IN')->sum('quantity');
            $totalOut = StockTransaction::where('medicine_id', $med->id)->where('type', 'OUT')->sum('quantity');
            $currentStock = $totalIn - $totalOut;

            if ($start) {
                $inBefore = StockTransaction::where('medicine_id', $med->id)->where('type', 'IN')->where('created_at', '<', $start)->sum('quantity');
                $outBefore = StockTransaction::where('medicine_id', $med->id)->where('type', 'OUT')->where('created_at', '<', $start)->sum('quantity');
                $beginningStock = $inBefore - $outBefore;
            } else {
                $beginningStock = 0;
            }

            $receivedQ = StockTransaction::where('medicine_id', $med->id)->where('type', 'IN');
            $dispensedQ = StockTransaction::where('medicine_id', $med->id)->where('type', 'OUT');
            if ($start && $end) {
                $receivedQ->whereBetween('created_at', [$start, $end]);
                $dispensedQ->whereBetween('created_at', [$start, $end]);
            }
            $stockReceived = $receivedQ->sum('quantity');
            $quantityDispensed = $dispensedQ->sum('quantity');

            $nearestBatch = Batch::where('medicine_id', $med->id)
                ->where('quantity', '>', 0)
                ->orderBy('expiry_date')
                ->first();
            $nearestExpiry = $nearestBatch?->expiry_date;

            $reorderLevel = 10;
            if ($currentStock <= 0) {
                $status = 'Stock Out';
            } elseif ($currentStock <= $reorderLevel) {
                $status = 'Low Stock';
            } else {
                $status = 'Normal';
            }

            $expiryStatus = 'OK';
            if ($nearestExpiry) {
                $exp = Carbon::parse($nearestExpiry);
                if ($exp->isPast()) {
                    $expiryStatus = 'Expired';
                } elseif ($exp->diffInDays(now()) <= 30) {
                    $expiryStatus = 'Near Expiry';
                }
            }

            return (object) [
                'name' => $med->name,
                'beginning_stock' => $beginningStock,
                'stock_received' => $stockReceived,
                'quantity_dispensed' => $quantityDispensed,
                'current_stock' => $currentStock,
                'reorder_level' => $reorderLevel,
                'status' => $status,
                'nearest_expiry' => $nearestExpiry ? Carbon::parse($nearestExpiry)->format('M d, Y') : '-',
                'expiry_status' => $expiryStatus,
            ];
        });

        $summary = [
            'total_medicines' => $rows->count(),
            'low_stock' => $rows->where('status', 'Low Stock')->count(),
            'stock_out' => $rows->where('status', 'Stock Out')->count(),
            'expiring_soon' => $rows->whereIn('expiry_status', ['Expired', 'Near Expiry'])->count(),
        ];

        return view('reports.inventory-status', array_merge(
            ['rows' => $rows, 'summary' => $summary],
            $this->filterViewData($request)
        ));
    }

    // ==================================================
    // STOCK-OUT REPORT
    // ==================================================
    public function stockOutReport(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $medicines = Medicine::orderBy('name')->get();
        $incidents = collect();

        foreach ($medicines as $med) {
            $transactions = StockTransaction::where('medicine_id', $med->id)
                ->orderBy('created_at')
                ->get();

            if ($transactions->isEmpty()) continue;

            $balance = 0;
            $currentStockoutStart = null;

            foreach ($transactions as $t) {
                $balance += ($t->type === 'IN') ? $t->quantity : -$t->quantity;

                if ($balance <= 0 && $currentStockoutStart === null) {
                    $currentStockoutStart = $t->created_at;
                } elseif ($balance > 0 && $currentStockoutStart !== null) {
                    $incidents->push((object) [
                        'medicine_name' => $med->name,
                        'date_started' => $currentStockoutStart,
                        'date_restocked' => $t->created_at,
                        'duration' => Carbon::parse($currentStockoutStart)->diffInDays($t->created_at) ?: 1,
                        'reason' => $t->remarks ?: 'Stock replenished',
                        'status' => 'Resolved',
                    ]);
                    $currentStockoutStart = null;
                }
            }

            if ($currentStockoutStart !== null) {
                $incidents->push((object) [
                    'medicine_name' => $med->name,
                    'date_started' => $currentStockoutStart,
                    'date_restocked' => null,
                    'duration' => Carbon::parse($currentStockoutStart)->diffInDays(now()) ?: 1,
                    'reason' => 'Awaiting restock',
                    'status' => 'Ongoing',
                ]);
            }
        }

        if ($start && $end) {
            $incidents = $incidents->filter(fn($i) => $i->date_started >= $start && $i->date_started <= $end);
        }

        $rows = $incidents->sortByDesc('date_started')->values()->map(function ($i) {
            return (object) [
                'medicine_name' => $i->medicine_name,
                'date_started' => Carbon::parse($i->date_started)->format('M d, Y'),
                'date_restocked' => $i->date_restocked ? Carbon::parse($i->date_restocked)->format('M d, Y') : '-',
                'duration' => $i->duration . ' day' . ($i->duration === 1 ? '' : 's'),
                'reason' => $i->reason,
                'status' => $i->status,
            ];
        });

        $summary = [
            'total_incidents' => $rows->count(),
            'resolved' => $rows->where('status', 'Resolved')->count(),
            'ongoing' => $rows->where('status', 'Ongoing')->count(),
        ];

        return view('reports.stock-out', array_merge(
            ['rows' => $rows, 'summary' => $summary],
            $this->filterViewData($request)
        ));
    }

    // ==================================================
    // PATIENT REPORTS CATEGORY PAGE
    // ==================================================
    public function patientCategory()
    {
        return view('reports.categories.patient');
    }

    // ==================================================
    // PATIENT DEMOGRAPHIC REPORT
    // ==================================================
    public function patientDemographic(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $baseQuery = Patient::query();
        if ($start && $end) {
            $baseQuery->whereBetween('created_at', [$start, $end]);
        }

        $totalPatients = (clone $baseQuery)->count();
        $maleCount = (clone $baseQuery)->whereRaw('LOWER(gender) = ?', ['male'])->count();
        $femaleCount = (clone $baseQuery)->whereRaw('LOWER(gender) = ?', ['female'])->count();

        $newPatients = (clone $baseQuery)->where('created_at', '>=', now()->subDays(30))->count();
        $returningPatients = max(0, $totalPatients - $newPatients);

        $ageGroups = [
            '0-9' => 0, '10-19' => 0, '20-29' => 0, '30-39' => 0,
            '40-49' => 0, '50-59' => 0, '60+' => 0,
        ];
        (clone $baseQuery)->select('id', 'dob')->get()->each(function ($p) use (&$ageGroups) {
            $age = $p->age;
            if (!is_numeric($age)) return;
            $age = (int) $age;
            if ($age <= 9) $ageGroups['0-9']++;
            elseif ($age <= 19) $ageGroups['10-19']++;
            elseif ($age <= 29) $ageGroups['20-29']++;
            elseif ($age <= 39) $ageGroups['30-39']++;
            elseif ($age <= 49) $ageGroups['40-49']++;
            elseif ($age <= 59) $ageGroups['50-59']++;
            else $ageGroups['60+']++;
        });

        $barangayRows = (clone $baseQuery)->select('barangay')
            ->selectRaw("SUM(CASE WHEN LOWER(gender) = 'male' THEN 1 ELSE 0 END) as male_count")
            ->selectRaw("SUM(CASE WHEN LOWER(gender) = 'female' THEN 1 ELSE 0 END) as female_count")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('barangay')
            ->orderByDesc('total')
            ->get();

        return view('reports.patient-demographic', array_merge([
            'totalPatients' => $totalPatients,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'newPatients' => $newPatients,
            'returningPatients' => $returningPatients,
            'ageGroups' => $ageGroups,
            'barangayRows' => $barangayRows,
        ], $this->filterViewData($request)));
    }

    // ==================================================
    // PATIENT REGISTRATION REPORT
    // ==================================================
    public function patientRegistration(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $query = Patient::query();
        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        $registrationTotal = (clone $query)->count();

        $rows = (clone $query)->orderByDesc('created_at')->take(300)->get()->map(function ($p) {
            $hasRecentTriage = TriageRecord::where('patient_id', $p->id)
                ->where('created_at', '>=', now()->subMonths(6))
                ->exists();
            $hasRecentDispense = DispensingRecord::where('patient_ptn', $p->patient_id)
                ->where('dispense_date', '>=', now()->subMonths(6))
                ->exists();
            $status = ($hasRecentTriage || $hasRecentDispense) ? 'Active' : 'Inactive';

            return (object) [
                'patient_id' => $p->patient_id,
                'name' => trim("{$p->first_name} {$p->middle_name} {$p->last_name}"),
                'age' => $p->age,
                'sex' => $p->gender,
                'registration_date' => $p->created_at ? $p->created_at->format('M d, Y') : '-',
                'status' => $status,
            ];
        });

        return view('reports.patient-registration', array_merge([
            'rows' => $rows,
            'registrationTotal' => $registrationTotal,
        ], $this->filterViewData($request)));
    }

    // ==================================================
    // OPERATIONAL REPORTS CATEGORY PAGE
    // ==================================================
    public function operationalCategory()
    {
        return view('reports.categories.operational');
    }

    // ==================================================
    // DAILY SERVICE REPORT
    // ==================================================
    public function dailyServiceReport(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);
        if (!$start) {
            $start = now()->subDays(30)->startOfDay();
            $end = now()->endOfDay();
        }

        $registrations = Patient::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');

        $visits = TriageRecord::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');

        $assessments = TriageRecord::whereNotNull('risk_level')->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');

        $dispensing = DispensingRecord::whereBetween('dispense_date', [$start, $end])
            ->selectRaw('DATE(dispense_date) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');

        $medReqs = \App\Models\MedicineRequest::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');

        $activities = ActivityLog::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');

        $allDates = collect()
            ->merge($registrations->keys())
            ->merge($visits->keys())
            ->merge($dispensing->keys())
            ->merge($medReqs->keys())
            ->merge($activities->keys())
            ->unique()
            ->sortDesc()
            ->take(60);

        $rows = $allDates->map(function ($d) use ($registrations, $visits, $assessments, $dispensing, $medReqs, $activities) {
            return (object) [
                'date' => Carbon::parse($d)->format('M d, Y'),
                'registrations' => $registrations[$d] ?? 0,
                'visits' => $visits[$d] ?? 0,
                'assessments' => $assessments[$d] ?? 0,
                'dispensing' => $dispensing[$d] ?? 0,
                'requests' => $medReqs[$d] ?? 0,
                'activities' => $activities[$d] ?? 0,
            ];
        });

        $summary = [
            'total_registrations' => $registrations->sum(),
            'total_visits' => $visits->sum(),
            'total_dispensing' => $dispensing->sum(),
            'total_requests' => $medReqs->sum(),
        ];

        return view('reports.daily-service', array_merge(
            ['rows' => $rows, 'summary' => $summary],
            $this->filterViewData($request)
        ));
    }

    // ==================================================
    // MORBIDITY REPORT
    // ==================================================
    public function morbidityReport(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $base = DispensingRecord::whereNotNull('diagnosis')->where('diagnosis', '!=', '');
        if ($start && $end) {
            $base->whereBetween('dispense_date', [$start, $end]);
        }

        $rows = (clone $base)
            ->select('diagnosis')
            ->selectRaw("SUM(CASE WHEN sex = 'Male' THEN 1 ELSE 0 END) as male_count")
            ->selectRaw("SUM(CASE WHEN sex = 'Female' THEN 1 ELSE 0 END) as female_count")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('diagnosis')
            ->orderByDesc('total')
            ->take(50)
            ->get();

        $totalCases = (clone $base)->count();
        $topCondition = $rows->first()->diagnosis ?? 'N/A';

        return view('reports.morbidity', array_merge([
            'rows' => $rows,
            'totalCases' => $totalCases,
            'topCondition' => $topCondition,
            'uniqueConditions' => $rows->count(),
        ], $this->filterViewData($request)));
    }

    // ==================================================
    // MEDICINE REQUEST REPORT
    // ==================================================
    public function medicineRequestReport(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $base = \App\Models\MedicineRequest::query();
        if ($start && $end) {
            $base->whereBetween('created_at', [$start, $end]);
        }

        $approved = (clone $base)->where('status', 'Approved')->count();
        $rejected = (clone $base)->where('status', 'Rejected')->count();
        $pending = (clone $base)->where('status', 'Pending Physician')->count();
        $total = (clone $base)->count();

        $rows = (clone $base)->with(['medicine', 'requester'])->latest()->take(200)->get()->map(function ($r) {
            return (object) [
                'request_id' => $r->id,
                'medicine_name' => optional($r->medicine)->name ?? '-',
                'quantity_requested' => $r->quantity_requested,
                'request_date' => $r->created_at ? $r->created_at->format('M d, Y') : '-',
                'requested_by' => optional($r->requester)->name ?? '-',
                'status' => $r->status ?? 'Pending Physician',
            ];
        });

        return view('reports.medicine-request', array_merge([
            'rows' => $rows,
            'approved' => $approved,
            'rejected' => $rejected,
            'pending' => $pending,
            'total' => $total,
        ], $this->filterViewData($request)));
    }
}
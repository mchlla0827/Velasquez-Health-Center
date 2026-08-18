<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\User;
use App\Models\StockTransaction;
use App\Models\DispensingRecord; // ✅ ADDED — CORRECT TABLE
use App\Models\TriageRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index()
{
    // ✅ AYOS NA: $patientsToday = LISTAHAN (para sa count() sa card)
    // ✅ AYOS NA: $patientsTodayCount = BILANG (para sa badge sa recent patients)
    $patientsToday = Patient::whereDate('created_at', today())->latest()->get();
    $patientsTodayCount = $patientsToday->count();

    $lowStockMedicines = Medicine::whereColumn('stock', '<=', 'threshold')->select('id', 'name', 'stock')->get();
    $recentPatients = Patient::latest()->take(4)->get();
    $totalRegisteredPatients = Patient::count();

    $consultedPatientIds = TriageRecord::whereDate('created_at', today())
    ->pluck('patient_id')
    ->toArray();

    // ✅ FIXED: Count & Sum from DispensingRecord (YOUR ACTUAL DATA)
    $medicinesDispensedToday = DispensingRecord::whereDate('dispense_date', today())->sum('quantity_dispensed');
    $dispensedRecordsToday = DispensingRecord::whereDate('dispense_date', today())
        ->join('medicines', 'dispensing_records.medicine_id', '=', 'medicines.id')
        ->select(
            'dispensing_records.*',
            'medicines.name AS medicine_name',
            'medicines.brand'
        )
        ->orderBy('dispense_date', 'DESC')
        ->take(10) // Show last 10 only
        ->get();

    $patientsConsultedToday = TriageRecord::whereDate('created_at', today())->count();

    // Patient Risk Summary
    $totalInQueue = TriageRecord::whereDate('created_at', today())->count();
    $highRiskCount = TriageRecord::where('risk_level', 'High')->count();
    $mediumRiskCount = TriageRecord::where('risk_level', 'Medium')->count();
    $lowRiskCount = TriageRecord::where('risk_level', 'Low')->count();

    // AI Forecast Summary
    $last30Days = Carbon::now()->subDays(30);
    $usageData = StockTransaction::where('type', 'dispense')
                    ->where('created_at', '>=', $last30Days)
                    ->selectRaw('medicine_id, SUM(quantity) as total_used')
                    ->groupBy('medicine_id')
                    ->orderBy('total_used', 'DESC')
                    ->with('medicine:id,name,stock,threshold')
                    ->get();

    $forecastData = [];
    foreach ($usageData as $data) {
        if (!$data->medicine) continue;
        $dailyAvg = $data->total_used / 30;
        $daysLeft = $data->medicine->stock / ($dailyAvg ?: 1);

        if ($daysLeft < 7 || $data->medicine->stock <= $data->medicine->threshold) {
            $forecastData[] = [
                'name' => $data->medicine->name,
                'predicted_shortage' => round($daysLeft) . ' days left',
                'high_demand' => $data->total_used . ' pcs used (30d)',
                'recommended_restock' => 'Order ' . round($dailyAvg * 30) . ' pcs'
            ];
        }
    }
    $forecastData = array_slice($forecastData, 0, 5);

    return view('dashboard', compact(
        'patientsToday',        // ✅ PARA SA CARD (Patients Registered Today)
        'patientsTodayCount',   // ✅ PARA SA BADGE (Recent Patients X today)
        'lowStockMedicines', 
        'recentPatients', 
        'highRiskCount',
        'totalRegisteredPatients', 
        'medicinesDispensedToday', 
        'patientsConsultedToday',
        'totalInQueue', 
        'mediumRiskCount', 
        'lowRiskCount', 
        'forecastData',
        'dispensedRecordsToday',
        'consultedPatientIds'
    ));
}
    public function manageUsers(Request $request)
    {
        $totalUsers = User::count();
        $adminCount = User::where('role', 'Admin')->count();
        $workerCount = User::whereIn('role', ['BHW', 'Health Worker'])->count();
        $activeCount = User::where('updated_at', '>=', now()->subMinutes(30))->count();

        $query = User::query();
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        } elseif ($request->filled('status') && $request->status == 'active') {
            $query->where('updated_at', '>=', now()->subMinutes(30));
        }
        $users = $query->latest()->get();

        return view('admin.manage-user', compact('users', 'totalUsers', 'adminCount', 'workerCount', 'activeCount'));
    }
}
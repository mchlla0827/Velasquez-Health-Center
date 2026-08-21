<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\NcdAssessment;
use App\Models\TriageRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\DispensingRecord;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,nurse,doctor,bhw'); 
    }

    public function index()
    {
        $patients = Patient::orderBy('created_at', 'desc')->get();
        $totalPatientsCount = $patients->count();
        $newPatientsCount = Patient::where('created_at', '>=', now()->startOfMonth())->count();
        $activePatientsCount = Patient::whereHas('triageRecords', function ($query) {
            $query->where('created_at', '>=', now()->startOfMonth());
        })->count();
        $seniorCount = Patient::where('age', '>=', 60)->count();
        return view('patients.records', compact('patients', 'totalPatientsCount', 'newPatientsCount', 'activePatientsCount', 'seniorCount'));
    }

    public function create()
    {
        $lastFamilyNumber = \App\Models\Patient::max(\DB::raw('CAST(family_number AS UNSIGNED)')) ?? 0;
        $nextFamilyId = $lastFamilyNumber + 1; 
        return view('patients.registration', compact('nextFamilyId'));
    }

    public function store(Request $request)
    {
        $role = strtolower(auth()->user()->role);

        if ($request->input('action') === 'cancel') {
            return redirect()
                ->route($role . '.patient-registration')
                ->withInput()
                ->with('info', 'Registration process cancelled.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'contact_number' => 'required|digits:11',
            'family_number' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'philhealth_type' => 'required|in:None,Member,Dependent',
            'philhealth_no_member' => 'required_if:philhealth_type,Member|nullable|digits:12',
            'philhealth_no_dep' => 'required_if:philhealth_type,Dependent|nullable|digits:12',
            'philhealth_member_name' => 'required_if:philhealth_type,Dependent|nullable|string|max:255',
        ]);

        $age = Carbon::parse($validated['dob'])->age;

        $philhealthNo = null;
        if ($validated['philhealth_type'] === 'Member') {
            $philhealthNo = $request->philhealth_no_member;
        } elseif ($validated['philhealth_type'] === 'Dependent') {
            $philhealthNo = $request->philhealth_no_dep;
        }

        $patientId = 'PAT-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($validated, $request, $patientId, $philhealthNo, $age) {
            Patient::create([
                'patient_id' => $patientId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_initial' => $validated['middle_initial'] ?? null,
                'dob' => $validated['dob'],
                'age' => $age,
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'barangay' => $validated['barangay'],
                'contact_number' => $validated['contact_number'],
                'family_number' => $validated['family_number'],
                'email' => $validated['email'] ?? null,
                'philhealth_type' => $validated['philhealth_type'],
                'philhealth_no' => $philhealthNo,
                'principal_member_name' => $validated['philhealth_type'] === 'Dependent'
                    ? $validated['philhealth_member_name']
                    : null,
            ]);
        });

        return redirect()
            ->route($role . '.patient-registration')
            ->with('success', 'Patient registered successfully.');
    }

    public function edit($id) 
    { 
        $patient = Patient::findOrFail($id); 
        return view('admin.patient-edit', compact('patient')); 
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'mother_first' => 'nullable|string|max:255',
            'mother_middle' => 'nullable|string|max:255',
            'mother_last' => 'nullable|string|max:255',
            'father_first' => 'nullable|string|max:255',
            'father_middle' => 'nullable|string|max:255',
            'father_last' => 'nullable|string|max:255',
            'dob' => 'required|date',
            'pob' => 'nullable|string|max:255',
            'gender' => 'required|string',
            'civil_status' => 'nullable|string',
            'address' => 'required|string',
            'barangay' => 'required|string',
            'contact_number' => 'nullable|string|max:11',
            'email' => 'nullable|email|max:255',
            'osca_pwd_no' => 'nullable|string|max:255',
            'four_ps_no' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string|max:255',
            'philhealth' => 'nullable|string',
            'philhealth_no_member' => 'nullable|string|max:255',
            'philhealth_no_dependent' => 'nullable|string|max:255',
            'philhealth_member_name' => 'nullable|string|max:255',
            'philhealth_member_dob' => 'nullable|date',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Patient record updated successfully.',
            'patient' => $patient->fresh()
        ]);
    }

    public function destroy($id) 
    { 
        Patient::findOrFail($id)->delete(); 
        return redirect()->back()->with('success','Deleted.'); 
    }

    // ==================================================
    // TRIAGE MAIN VIEW
    // ==================================================
    public function triage()
    {
        $triageRecords = TriageRecord::whereHas('patient')
            ->with('patient')
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '')
                    ->orWhereNotIn('status', ['Done', 'Consulted', 'done', 'consulted']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalQueueCount = $triageRecords->count();
        $highRiskCount = $triageRecords->filter(fn($r) => strtolower($r->risk_level) === 'high')->count();
        $mediumRiskCount = $triageRecords->filter(fn($r) => strtolower($r->risk_level) === 'medium')->count();
        $lowRiskCount = $triageRecords->filter(fn($r) => strtolower($r->risk_level) === 'low')->count();

        $user = auth()->user();
        $role = strtolower($user->role);
        $isDoctor = ($role === 'doctor');
        $isPIC = !empty($user->is_physician_in_charge) && $user->is_physician_in_charge == 1;
        $canCallPatient = $isDoctor || $isPIC;

        return view('patients.triage', compact(
            'triageRecords',
            'totalQueueCount',
            'highRiskCount',
            'mediumRiskCount',
            'lowRiskCount',
            'isDoctor',
            'isPIC',
            'canCallPatient',
            'role'
        ));
    }

    // ==================================================
    // UPDATE STATUS — Doctor OR PIC
    // ==================================================
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $role = strtolower($user->role);
            $isDoctor = ($role === 'doctor');
            $isPIC = !empty($user->is_physician_in_charge) && $user->is_physician_in_charge == 1;

            if (!$isDoctor && !$isPIC) {
                return response()->json(['success' => false, 'message' => 'Unauthorized: Doctor or PIC only'], 403);
            }

            $request->validate(['status' => 'required|string|in:Waiting,Called,In Session,Done']);

            $record = TriageRecord::findOrFail($id);
            $record->status = $request->input('status');
            $record->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ==================================================
    // STORE QUEUE
    // ==================================================
    public function storeQueue(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service_type' => 'required|string',
            'temp' => 'nullable|string',
            'bp' => 'nullable|string',
            'weight' => 'nullable|string',
            'height' => 'nullable|string',
            'symptoms' => 'nullable|array',
        ]);

        $patient = Patient::findOrFail($request->patient_id);
        $symptomsArray = $request->input('symptoms', []);
        $lowercaseSymptoms = array_map('strtolower', $symptomsArray);
        $riskLevel = 'Low';

        if (in_array('difficulty breathing', $lowercaseSymptoms) || in_array('chest pain', $lowercaseSymptoms)) {
            $riskLevel = 'High';
        } elseif (in_array('fever', $lowercaseSymptoms) && count($lowercaseSymptoms) >= 2) {
            $riskLevel = 'Medium';
        }

        $symptomsString = !empty($symptomsArray) ? implode(', ', $symptomsArray) : 'No symptoms reported.';
        $queueNumber = TriageRecord::generateQueueNumber();

        TriageRecord::create([
            'patient_id' => $patient->id,
            'service_type' => $request->service_type,
            'risk_level' => $riskLevel,
            'temp' => $request->temp,
            'bp' => $request->bp,
            'weight' => $request->weight,
            'height' => $request->height,
            'symptoms' => $symptomsString,
            'status' => 'Waiting',
            'queue_number' => $queueNumber,
        ]);

        $patient->update(['current_risk_status' => $riskLevel]);
        return redirect()->back()->with('success', 'Patient added to queue as #' . $queueNumber);
    }

    // ==================================================
    // AJAX SEARCH
    // ==================================================
    public function search(Request $request)
    {
        $term = $request->input('q');
        if (empty($term)) return response()->json([]);
        $query = Patient::query();
        if (str_contains($term, '/')) {
            $parts = explode('/', $term);
            $familyNumber = trim($parts[0]);
            $barangay = trim($parts[1]);
            if ($familyNumber !== '' && $barangay !== '') {
                $query->where('family_number', $familyNumber)->where('barangay', 'LIKE', "%{$barangay}%");
            }
        } else {
            $query->where(function($q) use ($term) {
                $q->where('first_name', 'LIKE', "%{$term}%")
                    ->orWhere('last_name', 'LIKE', "%{$term}%")
                    ->orWhere('contact_number', 'LIKE', "%{$term}%")
                    ->orWhere('family_number', 'LIKE', "%{$term}%");
            });
        }
        return response()->json($query->take(10)->get());
    }

    public function show($id) 
    { 
        return response()->json(Patient::findOrFail($id)); 
    }

    public function getMedicineHistory($id) 
    { 
        $patient = Patient::findOrFail($id); 
        $history = $patient->dispensingRecords ?? []; 
        return view('nurse.patient-medicine-history', compact('patient','history')); 
    }

    // ==================================================
    // LIVE DATA FOR BHW
    // ==================================================
    public function getLiveQueueData()
    {
        $triageRecords = TriageRecord::whereHas('patient')
            ->with('patient')
            ->whereNotIn('status', ['Done', 'done', 'Consulted', 'consulted'])
            ->orWhereNull('status')
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('patients.partials.triage_table_rows', compact('triageRecords'))->render();
        $callingPatient = $triageRecords->where('status', 'Called')->first();

        return response()->json([
            'html' => $html,
            'active_call' => $callingPatient ? true : false,
            'patient_name' => $callingPatient ? $callingPatient->patient->first_name.' '.$callingPatient->patient->last_name : '',
            'call_id' => $callingPatient ? $callingPatient->id : 0
        ]);
    }

    public function getPatientMedicineHistory($ptn)
    {
        $history = DispensingRecord::with('medicine')
            ->where('patient_ptn', trim($ptn))
            ->orderByDesc('dispense_date')
            ->get();

        $result = $history->map(function ($record) {
            return [
                'date' => optional($record->dispense_date)
                    ? \Carbon\Carbon::parse($record->dispense_date)->format('M d, Y')
                    : '---',
                'medicine_name' => optional($record->medicine)->name ?? 'Unknown',
                'quantity' => $record->quantity_dispensed,
                'unit' => $record->unit,
                'dispensed_by' => $record->dispensed_by,
            ];
        });

        return response()->json($result);
    }

    // ==================================================
    // ✅ NCD ASSESSMENT — LOAD LATEST (AJAX)
    // ==================================================
    public function getMedicalHistory($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $ncdAssessment = NcdAssessment::where('patient_id', $patient->id)
            ->latest()
            ->first();

        return response()->json([
            'has_ncd' => !is_null($ncdAssessment),
            'ncd_data' => $ncdAssessment,
        ]);
    }

    // ==================================================
    // ✅ NCD ASSESSMENT — SHOW FORM
    // ==================================================
    public function createNcdAssessment($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $ncdAssessment = NcdAssessment::where('patient_id', $patient->id)
            ->latest()
            ->first();

        return view('patients.ncd-assessment', compact('patient', 'ncdAssessment'));
    }

    // ==================================================
    // ✅ NCD ASSESSMENT — SAVE FORM
    // ==================================================
    public function storeNcdAssessment(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $validated = $request->validate([
                // === ADD THESE NEW FIELDS AT THE TOP ===
            'health_facility' => 'nullable|string|max:255',
            'assessment_date' => 'nullable|date',
            'family_no' => 'nullable|string|max:50',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'id_no' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'telepono' => 'nullable|string|max:20',
            'birthday' => 'nullable|date',
            'edad' => 'nullable|numeric',
            'kasarian' => 'nullable|string|max:20',
            'estadocivil' => 'nullable|string|max:50',
            'relihiyon' => 'nullable|string|max:100',
            'educational_attainment' => 'nullable|string|max:255',
            'is_diabetic' => 'nullable|boolean',
            'is_diabetic_year' => 'nullable|string|max:4',
            'is_diabetic_meds' => 'nullable|string',
            'risk_dm' => 'nullable|boolean',
            'is_hypertensive' => 'nullable|boolean',
            'is_hypertensive_year' => 'nullable|string|max:4',
            'is_hypertensive_meds' => 'nullable|string',
            'risk_hpn' => 'nullable|boolean',
            'has_copd' => 'nullable|boolean',
            'has_copd_year' => 'nullable|string|max:4',
            'has_copd_meds' => 'nullable|string',
            'risk_copd' => 'nullable|boolean',
            'has_cancer' => 'nullable|boolean',
            'cancer_site_condition' => 'nullable|string',
            'cancer_year' => 'nullable|string|max:4',
            'cancer_meds' => 'nullable|string',
            'risk_cancer' => 'nullable|boolean',
            'has_eye_disease' => 'nullable|boolean',
            'eye_year' => 'nullable|string|max:4',
            'eye_meds' => 'nullable|string',
            'cp1','cp2','cp3','cp4','cp5','cp6','cp7','cp8' => 'nullable|string',
            'r_diet','r_salt' => 'nullable|boolean',
            'diet_gulay','diet_prutas','diet_isda','diet_karne','diet_processed food' => 'nullable|string',
            'diet_maalat','diet_matatamis','diet_mamantika' => 'nullable|string',
            'alc_u','alc_q','amt_b','amt_w','amt_s','alc_f','alc_b' => 'nullable|string',
            'alc_t' => 'nullable|array',
            'r_binge' => 'nullable|boolean',
            'w' => 'nullable|numeric',
            'h' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'bmi_s' => 'nullable|string',
            'r_over','r_obese' => 'nullable|boolean',
            'waist','hip','whr' => 'nullable|numeric',
            'whr_s' => 'nullable|string',
            'r_whr' => 'nullable|boolean',
            'fbs','vn' => 'nullable|numeric',
            'fbs_s' => 'nullable|string',
            'r_predm' => 'nullable|boolean',
            'rbs_s' => 'nullable|string',
            's_pol','s_wgt','r_dm_f' => 'nullable|boolean',
            'bp_l','bp_r','bp_b' => 'nullable|string',
            'bp_s' => 'nullable|string',
            'r_hpn_f' => 'nullable|boolean',
            'chol' => 'nullable|numeric',
            'ch_s' => 'nullable|string',
            'r_chol' => 'nullable|boolean',
            'pro','ket' => 'nullable|string',
            'r_pro' => 'nullable|boolean',
            'rp' => 'nullable|string',
            'r_30' => 'nullable|boolean',
            'cs' => 'nullable|string',
        ]);

        // Auto-calculate BMI
        if (!empty($validated['w']) && !empty($validated['h'])) {
            $validated['bmi'] = round(($validated['w'] / (($validated['h'] / 100) ** 2)), 1);
        }

        // Normalize Yes/No checkboxes
        $checkboxFields = [
            'is_diabetic','is_hypertensive','has_copd','has_cancer','has_eye_disease',
            'risk_dm','risk_hpn','risk_copd','risk_cancer','r_diet','r_salt','r_binge',
            'r_over','r_obese','r_whr','r_predm','s_pol','s_wgt','r_dm_f','r_hpn_f',
            'r_chol','r_pro','r_30'
        ];
        foreach ($checkboxFields as $field) {
            $validated[$field] = in_array(strtolower(trim($request->$field)), ['yes','y','1','on']) ? true : false;
        }

        // Save new assessment
        NcdAssessment::create([
            'patient_id' => $patient->id,
            'assessed_by' => auth()->id(),
            ...$validated
        ]);

        return redirect()
            ->back()
            ->with('success', '✅ NCD Risk Assessment saved successfully!');
    }

    // ==================================================
    // SERVICE HISTORY
    // ==================================================
    public function serviceHistory($id)
    {
        $patient = Patient::findOrFail($id);
        $records = $patient->triageRecords()
            ->with('registeredBy')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($records->map(function ($record) {
            return [
                'id' => $record->id,
                'date' => $record->created_at ? $record->created_at->format('M d, Y') : '---',
                'time' => $record->created_at ? $record->created_at->format('h:i A') : '---',
                'service_type' => $record->service_type ?: '---',
                'risk_level' => $record->risk_level ?: '---',
                'triage_level' => $record->triage_level ?: '---',
                'status' => $record->status ?: '---',
                'bp' => $record->bp ?: '---',
                'temp' => $record->temp !== null ? $record->temp . ' °C' : '---',
                'weight' => $record->weight !== null ? $record->weight . ' kg' : '---',
                'height' => $record->height !== null ? $record->height . ' cm' : '---',
                'symptoms' => $record->symptoms ?: '---',
                'registered_by' => $record->registeredBy
                    ? ($record->registeredBy->name ?? $record->registeredBy->email)
                    : '---',
            ];
        }));
    }
}
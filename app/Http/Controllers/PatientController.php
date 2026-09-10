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
        $patients = Patient::where('patient_status', '!=', 'archived')->orderBy('created_at', 'desc')->get();
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
        $activeBarangays = \App\Models\Barangay::where('status', 'active')->orderBy('name')->pluck('name');
        return view('patients.registration', compact('nextFamilyId', 'activeBarangays'));
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
            'middle_initial' => 'nullable|string|max:100',
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
            'mother_first' => 'nullable|string|max:255',
            'mother_middle' => 'nullable|string|max:255',
            'mother_last' => 'nullable|string|max:255',
            'father_first' => 'nullable|string|max:255',
            'father_middle' => 'nullable|string|max:255',
            'father_last' => 'nullable|string|max:255',
            'pob' => 'nullable|string|max:255',
            'civil_status' => 'nullable|string|max:50',
            'osca_pwd_no' => 'nullable|string|max:50',
            'four_ps_no' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:100',
            'educational_attainment' => 'nullable|string|max:100',
            'philhealth_member_dob' => 'nullable|date',
            'tracking_immunization' => 'nullable|string',
            'tracking_maternal' => 'nullable|string',
        ]);

        $age = Carbon::parse($validated['dob'])->age;

        $philhealthNo = null;
        if ($validated['philhealth_type'] === 'Member') {
            $philhealthNo = $request->philhealth_no_member;
        } elseif ($validated['philhealth_type'] === 'Dependent') {
            $philhealthNo = $request->philhealth_no_dep;
        }

        $patientId = 'PAT-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $immunizationFields = $request->only([
            'vac_bcg', 'vac_hepa', 'vac_penta1', 'vac_opv1', 'vac_pcv1',
            'vac_ipv1', 'vac_ipv2', 'vac_penta2', 'vac_opv2', 'vac_pcv2',
            'vac_penta3', 'vac_opv3', 'vac_pcv3', 'vac_mr1', 'vac_mmr1',
            'vac_mmr2', 'vac_hpv1', 'vac_hpv2', 'vac_flu', 'vac_pneumo', 'vac_td',
        ]);

        $maternalFields = $request->only([
            'mat_nbs', 'mat_nbs_date', 'mat_nbs_result',
            'mat_hearing', 'mat_hearing_date', 'mat_hearing_result',
            'mat_birth_order', 'mat_birth_length', 'mat_birth_weight',
            'mat_delivery_type', 'mat_feeding_type', 'mat_attendant', 'mat_delivery_place',
            'mat_vit_a_dose', 'mat_vit_a_date', 'mat_deworming_1', 'mat_deworming_2',
            'ob_g', 'ob_p_t', 'ob_p_p', 'ob_p_a', 'ob_p_l',
            'ob_menarche', 'ob_pmp', 'ob_lmp', 'ob_edc', 'ob_tt_status',
            'ob_td1', 'ob_td2', 'ob_td3', 'ob_td4', 'ob_td5',
            'residency_proof' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($validated, $request, $patientId, $philhealthNo, $age, $immunizationFields, $maternalFields) {
            Patient::create(array_merge([
                'patient_id' => $patientId,
                'residency_proof_path' => $request->hasFile('residency_proof') ? $request->file('residency_proof')->store('residency_proofs', 'public') : null,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_initial'] ?? null,
                'dob' => $validated['dob'],
                'age' => $age,
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'barangay' => $validated['barangay'],
                'contact_number' => $validated['contact_number'],
                'family_number' => $validated['family_number'],
                'email' => $validated['email'] ?? null,
                'philhealth' => strtolower($validated['philhealth_type']),
                'philhealth_no_member' => $validated['philhealth_type'] === 'Member' ? $philhealthNo : null,
                'philhealth_no_dependent' => $validated['philhealth_type'] === 'Dependent' ? $philhealthNo : null,
                'philhealth_member_name' => $validated['philhealth_type'] === 'Dependent'
                    ? $validated['philhealth_member_name']
                    : null,
                'philhealth_member_dob' => $validated['philhealth_member_dob'] ?? null,
                'mother_first' => $validated['mother_first'] ?? null,
                'mother_middle' => $validated['mother_middle'] ?? null,
                'mother_last' => $validated['mother_last'] ?? null,
                'father_first' => $validated['father_first'] ?? null,
                'father_middle' => $validated['father_middle'] ?? null,
                'father_last' => $validated['father_last'] ?? null,
                'pob' => $validated['pob'] ?? null,
                'civil_status' => $validated['civil_status'] ?? null,
                'osca_pwd_no' => $validated['osca_pwd_no'] ?? null,
                'four_ps_no' => $validated['four_ps_no'] ?? null,
                'religion' => $validated['religion'] ?? null,
                'educational_attainment' => $validated['educational_attainment'] ?? null,
                'tracking_immunization' => $validated['tracking_immunization'] ?? 'No',
                'tracking_maternal' => $validated['tracking_maternal'] ?? 'No',
            ], $immunizationFields, $maternalFields));
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
            'tracking_immunization' => 'nullable|string',
            'tracking_maternal' => 'nullable|string',
        ]);

        $immunizationFields = $request->only([
            'vac_bcg', 'vac_hepa', 'vac_penta1', 'vac_opv1', 'vac_pcv1',
            'vac_ipv1', 'vac_ipv2', 'vac_penta2', 'vac_opv2', 'vac_pcv2',
            'vac_penta3', 'vac_opv3', 'vac_pcv3', 'vac_mr1', 'vac_mmr1',
            'vac_mmr2', 'vac_hpv1', 'vac_hpv2', 'vac_flu', 'vac_pneumo', 'vac_td',
        ]);

        $maternalFields = $request->only([
            'mat_nbs', 'mat_nbs_date', 'mat_nbs_result',
            'mat_hearing', 'mat_hearing_date', 'mat_hearing_result',
            'mat_birth_order', 'mat_birth_length', 'mat_birth_weight',
            'mat_delivery_type', 'mat_feeding_type', 'mat_attendant', 'mat_delivery_place',
            'mat_vit_a_dose', 'mat_vit_a_date', 'mat_deworming_1', 'mat_deworming_2',
            'ob_g', 'ob_p_t', 'ob_p_p', 'ob_p_a', 'ob_p_l',
            'ob_menarche', 'ob_pmp', 'ob_lmp', 'ob_edc', 'ob_tt_status',
            'ob_td1', 'ob_td2', 'ob_td3', 'ob_td4', 'ob_td5',
            'residency_proof' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update(array_merge($validated, $immunizationFields, $maternalFields));

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
            ->orderByRaw("FIELD(risk_level, 'High', 'Medium', 'Low')")
            ->orderBy('created_at', 'asc')
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
    // UPDATE STATUS - Doctor OR PIC
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

        // Prevent duplicate active queue entries - server-side, so this
        // can't be bypassed by submitting the form directly. "Active"
        // means not yet Done/Consulted, matching the definition used
        // everywhere else in the triage/consultation workflow.
        $hasActiveQueueEntry = \App\Models\TriageRecord::where('patient_id', $patient->id)
            ->whereNotIn('status', ['Done', 'done', 'Consulted', 'consulted'])
            ->exists();

        if ($hasActiveQueueEntry) {
            return redirect()->back()->with('error', 'This patient is already in the Triage Queue.');
        }
        $symptomsArray = $request->input('symptoms', []);
        $lowercaseSymptoms = array_map('strtolower', $symptomsArray);

        $latestNcd = NcdAssessment::where('patient_id', $patient->id)
            ->latest('assessment_date')
            ->first();

        $riskService = new \App\Services\RiskScoringService();
        $assessment = $riskService->assess(
            [
                'temp' => $request->temp,
                'bp' => $request->bp,
                'weight' => $request->weight,
                'height' => $request->height,
            ],
            $lowercaseSymptoms,
            $patient->age,
            $latestNcd
        );

        $riskLevel = $assessment['level'];

        $symptomsString = !empty($symptomsArray) ? implode(', ', $symptomsArray) : 'No symptoms reported.';
        $queueNumber = TriageRecord::generateQueueNumber();

        TriageRecord::create([
            'patient_id' => $patient->id,
            'service_type' => $request->service_type,
            'risk_level' => $riskLevel,
            'triage_level' => $riskLevel,
            'risk_score' => $assessment['score'],
            'risk_factors' => $assessment['factors'],
            'registered_by' => auth()->id(),
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
        $patient = Patient::findOrFail($id);
        $latestAssessment = NcdAssessment::where('patient_id', $patient->id)
        ->with('assessedBy:id,name')
            ->latest()
            ->first();

        $hasActiveTriageToday = TriageRecord::where('patient_id', $patient->id)
            ->whereNotIn('status', ['Done', 'done', 'Consulted', 'consulted'])
            ->exists();

        $data = $patient->toArray();
        $data['latest_ncd_assessment'] = $latestAssessment;
        $data['has_active_triage_today'] = $hasActiveTriageToday;
        $data['residency_proof_url'] = $patient->residency_proof_path ? asset('storage/' . $patient->residency_proof_path) : null;

        return response()->json($data);
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
            ->where(function ($query) {
                $query->whereNotIn('status', ['Done', 'done', 'Consulted', 'consulted'])
                    ->orWhereNull('status');
            })
            ->orderByRaw("FIELD(risk_level, 'High', 'Medium', 'Low')")
            ->orderBy('created_at', 'asc')
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
    // NCD ASSESSMENT - LOAD LATEST (AJAX)
    // ==================================================
    public function getMedicalHistory($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $ncdAssessment = NcdAssessment::where('patient_id', $patient->id)
        ->with('assessedBy:id,name')
            ->latest()
            ->first();

        return response()->json([
            'has_ncd' => !is_null($ncdAssessment),
            'ncd_data' => $ncdAssessment,
        ]);
    }

    // ==================================================
    // NCD ASSESSMENT - SHOW FORM
    // ==================================================
    public function createNcdAssessment($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $ncdAssessment = NcdAssessment::where('patient_id', $patient->id)
        ->with('assessedBy:id,name')
            ->latest()
            ->first();

        $latestTriage = \App\Models\TriageRecord::where('patient_id', $patient->id)
            ->whereNotIn('status', ['Done', 'done', 'Consulted', 'consulted'])
            ->latest()
            ->first();

        return view('patients.ncd-assessment', compact('patient', 'ncdAssessment', 'latestTriage'));
    }

    // ==================================================
    // NCD ASSESSMENT - SAVE FORM
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
            'is_diabetic' => 'nullable|string',
            'is_diabetic_year' => 'nullable|string|max:4',
            'is_diabetic_meds' => 'nullable|string',
            'risk_dm' => 'nullable|string',
            'is_hypertensive' => 'nullable|string',
            'is_hypertensive_year' => 'nullable|string|max:4',
            'is_hypertensive_meds' => 'nullable|string',
            'risk_hpn' => 'nullable|string',
            'has_copd' => 'nullable|string',
            'has_copd_year' => 'nullable|string|max:4',
            'has_copd_meds' => 'nullable|string',
            'risk_copd' => 'nullable|string',
            'has_cancer' => 'nullable|string',
            'cancer_site_condition' => 'nullable|string',
            'cancer_year' => 'nullable|string|max:4',
            'cancer_meds' => 'nullable|string',
            'risk_cancer' => 'nullable|string',
            'has_eye_disease' => 'nullable|string',
            'eye_year' => 'nullable|string|max:4',
            'eye_meds' => 'nullable|string',
            'cp1' => 'nullable|string',
            'cp2' => 'nullable|string',
            'cp3' => 'nullable|string',
            'cp4' => 'nullable|string',
            'cp5' => 'nullable|string',
            'cp6' => 'nullable|string',
            'cp7' => 'nullable|string',
            'cp8' => 'nullable|string',
            'r_diet' => 'nullable|string',
            'r_salt' => 'nullable|string',
            'diet_gulay' => 'nullable|string',
            'diet_prutas' => 'nullable|string',
            'diet_isda' => 'nullable|string',
            'diet_karne' => 'nullable|string',
            'diet_processed_food' => 'nullable|string',
            'diet_maalat' => 'nullable|string',
            'diet_matatamis' => 'nullable|string',
            'diet_mamantika' => 'nullable|string',
            'alc_u' => 'nullable|string',
            'alc_q' => 'nullable|string',
            'amt_b' => 'nullable|string',
            'amt_w' => 'nullable|string',
            'amt_s' => 'nullable|string',
            'alc_f' => 'nullable|string',
            'alc_b' => 'nullable|string',
            'alc_t' => 'nullable|array',
            'r_binge' => 'nullable|string',
            'w' => 'nullable|numeric',
            'h' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'bmi_s' => 'nullable|string',
            'r_over' => 'nullable|string',
            'r_obese' => 'nullable|string',
            'waist' => 'nullable|numeric',
            'hip' => 'nullable|numeric',
            'whr' => 'nullable|numeric',
            'whr_s' => 'nullable|string',
            'r_whr' => 'nullable|string',
            'fbs' => 'nullable|numeric',
            'vn' => 'nullable|numeric',
            'fbs_s' => 'nullable|string',
            'r_predm' => 'nullable|string',
            'rbs_s' => 'nullable|string',
            's_pol' => 'nullable|string',
            's_wgt' => 'nullable|string',
            'r_dm_f' => 'nullable|string',
            'bp_l' => 'nullable|string',
            'bp_r' => 'nullable|string',
            'bp_b' => 'nullable|string',
            'bp_s' => 'nullable|string',
            'r_hpn_f' => 'nullable|string',
            'chol' => 'nullable|numeric',
            'ch_s' => 'nullable|string',
            'r_chol' => 'nullable|string',
            'pro' => 'nullable|string',
            'ket' => 'nullable|string',
            'r_pro' => 'nullable|string',
            'rp' => 'nullable|string',
            'r_30' => 'nullable|string',
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
            'r_over','r_obese','r_whr','r_predm','s_pol','s_pdp','s_pph','s_wgt','r_dm_f','r_hpn_f',
            'r_chol','r_pro','r_ket','r_hpn_pre','r_30',
            'fam_hypertension','fam_heart_disease','fam_stroke','fam_diabetes','fam_cancer',
            'fam_kidney_disease','fam_lung_disease',
            'risk_activity','risk_smoking_history','risk_smoker','risk_stress'
        ];
        foreach ($checkboxFields as $field) {
            $validated[$field] = in_array(strtolower(trim($request->$field)), ['yes','y','1','on']) ? true : false;
        }

        // Convert MM/DD/YYYY strings from the form into MySQL's expected Y-m-d format
        foreach (['assessment_date', 'birthday'] as $dateField) {
            if (!empty($validated[$dateField])) {
                try {
                    $validated[$dateField] = Carbon::createFromFormat('m/d/Y', $validated[$dateField])->format('Y-m-d');
                } catch (\Exception $e) {
                    $validated[$dateField] = null;
                }
            }
        }

        // Save new assessment
        $newAssessment = NcdAssessment::create([
            'patient_id' => $patient->id,
            'assessed_by' => auth()->id(),
            ...$validated
        ]);

        if ($request->filled('link_consultation')) {
            \App\Models\Consultation::where('id', $request->link_consultation)
                ->where('patient_id', $patient->id)
                ->update(['related_ncd_assessment_id' => $newAssessment->id]);
        }

        $role = strtolower(auth()->user()->role);

        return redirect()
            ->route($role . '.patient-records', [
                'view_patient' => $patient->id,
                'tab' => 'medical-history',
            ])
            ->with('success', 'NCD Risk Assessment saved successfully.');
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
                'triage_level' => $record->triage_level ?: ($record->risk_level ?: '---'),
                'status' => $record->status ?: '---',
                'bp' => $record->bp ?: '---',
                'temp' => $record->temp !== null ? $record->temp . ' degC' : '---',
                'weight' => $record->weight !== null ? $record->weight . ' kg' : '---',
                'height' => $record->height !== null ? $record->height . ' cm' : '---',
                'symptoms' => $record->symptoms ?: '---',
                'registered_by_name' => $record->registeredBy
                    ? ($record->registeredBy->name ?: $record->registeredBy->email)
                    : '---',
                'registered_by_role' => $record->registeredBy
                    ? ucfirst($record->registeredBy->role ?: 'User')
                    : '---',
            ];
        }));
    }

    /**
     * Archived Patients list - separate from the normal active list.
     */
    public function archivedPatients()
    {
        $patients = \App\Models\Patient::where('patient_status', \App\Models\Patient::STATUS_ARCHIVED)
            ->orderByDesc('archived_at')
            ->get();

        $role = strtolower(auth()->user()->role ?? 'bhw');

        return view('patients.archived', compact('patients', 'role'));
    }

    /**
     * Archive a patient. Only allowed if currently active - prevents
     * duplicate archive actions on an already-archived patient. Does
     * NOT delete any data, only flips the status flag.
     */
    public function archivePatient($id)
    {
        $patient = \App\Models\Patient::findOrFail($id);

        abort_unless(
            in_array(strtolower(auth()->user()->role ?? ''), ['admin', 'nurse', 'doctor'], true),
            403
        );

        if ($patient->patient_status === \App\Models\Patient::STATUS_ARCHIVED) {
            return redirect()->back()->with('error', 'This patient is already archived.');
        }

        $patient->update([
            'patient_status' => \App\Models\Patient::STATUS_ARCHIVED,
            'archived_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Patient archived successfully. Their complete record and history remain intact.');
    }

    /**
     * Reactivate an archived patient. Only allowed if currently
     * archived - prevents duplicate/invalid reactivation. Restores the
     * SAME existing record, never creates a new one. History, triage,
     * consultations, NCD assessments, dispensing records, and the
     * residency proof are all untouched.
     */
    public function reactivatePatient($id)
    {
        $patient = \App\Models\Patient::findOrFail($id);

        abort_unless(
            in_array(strtolower(auth()->user()->role ?? ''), ['admin', 'nurse', 'doctor'], true),
            403
        );

        if ($patient->patient_status !== \App\Models\Patient::STATUS_ARCHIVED) {
            return redirect()->back()->with('error', 'This patient is not archived.');
        }

        $patient->update([
            'patient_status' => \App\Models\Patient::STATUS_ACTIVE,
            'archived_at' => null,
        ]);

        return redirect()->back()->with('success', 'Patient reactivated successfully. Their complete history has been preserved.');
    }

    /**
     * Lightweight check used by the Add to Queue form BEFORE submission,
     * so the duplicate-queue block happens instantly instead of after
     * a full form submit round-trip. The actual store still validates
     * this server-side too (storeQueue) - this is purely for fast UX.
     */
    public function checkActiveQueue($patientId)
    {
        $inQueue = \App\Models\TriageRecord::where('patient_id', $patientId)
            ->whereNotIn('status', ['Done', 'done', 'Consulted', 'consulted'])
            ->exists();

        return response()->json(['in_queue' => $inQueue]);
    }
}
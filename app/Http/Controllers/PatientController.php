<?php

namespace App\Http\Controllers;

use App\Models\Patient;
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
    // Get logged-in user's role
    $role = strtolower(auth()->user()->role);

    // Cancel button
    if ($request->input('action') === 'cancel') {
        return redirect()
            ->route($role . '.patient-registration')
            ->withInput()
            ->with('info', 'Registration process cancelled.');
    }

    // ============================================
    // Validation
    // ============================================
    $validated = $request->validate([
        'first_name'               => 'required|string|max:255',
        'last_name'                => 'required|string|max:255',
        'middle_initial'           => 'nullable|string|max:1',

        'dob'                      => 'required|date',

        'gender'                   => 'required|string',
        'address'                  => 'required|string|max:255',
        'barangay'                 => 'required|string|max:255',

        'contact_number'           => 'required|digits:11',

        'family_number'            => 'required|string|max:50',

        'email'                    => 'nullable|email|max:255',

        'philhealth_type'          => 'required|in:None,Member,Dependent',

        'philhealth_no_member'     => 'required_if:philhealth_type,Member|nullable|digits:12',
        'philhealth_no_dep'        => 'required_if:philhealth_type,Dependent|nullable|digits:12',

        'philhealth_member_name'   => 'required_if:philhealth_type,Dependent|nullable|string|max:255',
    ]);

    // ============================================
    // Calculate Age Automatically
    // ============================================
    $age = Carbon::parse($validated['dob'])->age;

    // ============================================
    // Determine PhilHealth Number
    // ============================================
    $philhealthNo = null;

    if ($validated['philhealth_type'] === 'Member') {
        $philhealthNo = $request->philhealth_no_member;
    } elseif ($validated['philhealth_type'] === 'Dependent') {
        $philhealthNo = $request->philhealth_no_dep;
    }

    // ============================================
    // Generate Patient ID
    // ============================================
    $patientId = 'PAT-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

    // ============================================
    // Save Patient
    // ============================================
    DB::transaction(function () use (
        $validated,
        $request,
        $patientId,
        $philhealthNo,
        $age
    ) {

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
        'first_name'               => 'required|string|max:255',
        'middle_name'              => 'nullable|string|max:255',
        'last_name'                => 'required|string|max:255',

        'mother_first'             => 'nullable|string|max:255',
        'mother_middle'            => 'nullable|string|max:255',
        'mother_last'              => 'nullable|string|max:255',

        'father_first'             => 'nullable|string|max:255',
        'father_middle'            => 'nullable|string|max:255',
        'father_last'              => 'nullable|string|max:255',

        'dob'                      => 'required|date',
        'pob'                      => 'nullable|string|max:255',
        'gender'                   => 'required|string',
        'civil_status'             => 'nullable|string',
        'address'                  => 'required|string',
        'barangay'                 => 'required|string',
        'contact_number'           => 'nullable|string|max:11',
        'email'                    => 'nullable|email|max:255',

        'osca_pwd_no'              => 'nullable|string|max:255',
        'four_ps_no'               => 'nullable|string|max:255',
        'religion'                 => 'nullable|string|max:255',
        'educational_attainment'   => 'nullable|string|max:255',

        'philhealth'               => 'nullable|string',
        'philhealth_no_member'     => 'nullable|string|max:255',
        'philhealth_no_dependent'  => 'nullable|string|max:255',
        'philhealth_member_name'   => 'nullable|string|max:255',
        'philhealth_member_dob'    => 'nullable|date',
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
        $highRiskCount   = $triageRecords->filter(fn($r) => strtolower($r->risk_level) === 'high')->count();
        $mediumRiskCount = $triageRecords->filter(fn($r) => strtolower($r->risk_level) === 'medium')->count();
        $lowRiskCount    = $triageRecords->filter(fn($r) => strtolower($r->risk_level) === 'low')->count();

        return view('patients.triage', compact('triageRecords','totalQueueCount','highRiskCount','mediumRiskCount','lowRiskCount'));
    }

    // ==================================================
    // ✅ UPDATE STATUS - TAMA AT TUMPAK
    // ==================================================
    public function updateStatus(Request $request, $id)
    {
        try {
            if (strtolower(auth()->user()->role) !== 'doctor') {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // ✅ TAMA NA STATUS LIST
            $request->validate([
                'status' => 'required|string|in:Waiting,Called,In Session,Done'
            ]);

            $record = TriageRecord::findOrFail($id);
            $record->status = $request->input('status');
            $record->save();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ==================================================
    // ✅ STORE QUEUE - INAYOS! TINANGGAL ANG ASSESSMENTS()
    // ==================================================
    public function storeQueue(Request $request)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'service_type' => 'required|string',
            'temp'         => 'nullable|string',
            'bp'           => 'nullable|string',
            'weight'       => 'nullable|string',
            'height'       => 'nullable|string',
            'symptoms'     => 'nullable|array',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        $symptomsArray = $request->input('symptoms', []);
        $lowercaseSymptoms = array_map('strtolower', $symptomsArray);
        $riskLevel = 'Low';

        if (in_array('difficulty breathing', $lowercaseSymptoms) || in_array('chest pain', $lowercaseSymptoms)) {
            $riskLevel = 'High';
        }
        elseif (in_array('fever', $lowercaseSymptoms) && count($lowercaseSymptoms) >= 2) {
            $riskLevel = 'Medium';
        }

        $symptomsString = !empty($symptomsArray) ? implode(', ', $symptomsArray) : 'No symptoms reported.';

        // ✅ GENERATE QUEUE NUMBER (RESETS DAILY)
        $queueNumber = TriageRecord::generateQueueNumber();

        TriageRecord::create([
            'patient_id'   => $patient->id,
            'service_type' => $request->service_type,
            'risk_level'   => $riskLevel,
            'temp'         => $request->temp,
            'bp'           => $request->bp,
            'weight'       => $request->weight,
            'height'       => $request->height,
            'symptoms'     => $symptomsString,
            'status'       => 'Waiting',
            'queue_number' => $queueNumber, // ✅ SAVE THE NUMBER
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

        // ✅ HANAPIN ANG "Called" STATUS
        $callingPatient = $triageRecords->where('status', 'Called')->first();

        return response()->json([
            'html'         => $html,
            'active_call'  => $callingPatient ? true : false,
            'patient_name' => $callingPatient ? $callingPatient->patient->first_name.' '.$callingPatient->patient->last_name : '',
            'call_id'      => $callingPatient ? $callingPatient->id : 0
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

// In PatientController.php (or wherever your modal history endpoint is)
public function getMedicalHistory($patientId)
{
    $patient = Patient::findOrFail($patientId);
    
    // Fetch the latest NCD assessment for this patient
    $ncdAssessment = NcdAssessment::where('patient_id', $patient->id)
        ->latest()
        ->first();

    return response()->json([
        'has_ncd' => !is_null($ncdAssessment),
        'ncd_data' => $ncdAssessment,
    ]);
}

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

            'date' => $record->created_at
                ? $record->created_at->format('M d, Y')
                : '---',

            'time' => $record->created_at
                ? $record->created_at->format('h:i A')
                : '---',

            'service_type' => $record->service_type ?: '---',

            'risk_level' => $record->risk_level ?: '---',

            'triage_level' => $record->triage_level ?: '---',

            'status' => $record->status ?: '---',

            'bp' => $record->bp ?: '---',

            'temp' => $record->temp !== null
                ? $record->temp . ' °C'
                : '---',

            'weight' => $record->weight !== null
                ? $record->weight . ' kg'
                : '---',

            'height' => $record->height !== null
                ? $record->height . ' cm'
                : '---',

            'symptoms' => $record->symptoms ?: '---',

            'registered_by' => $record->registeredBy
                ? ($record->registeredBy->name ?? $record->registeredBy->email)
                : '---',
        ];
    }));
}
}
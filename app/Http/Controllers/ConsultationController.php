<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationPrescription;
use App\Models\NcdAssessment;
use App\Models\Patient;
use App\Models\TriageRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * Return this patient's consultation history as JSON, for the
     * Medical History timeline. Reverse chronological (newest first).
     */
    public function index($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $consultations = Consultation::where('patient_id', $patient->id)
            ->with(['attendedBy:id,name', 'relatedNcdAssessment:id'])
            ->orderByDesc('consultation_date')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'consultation_date' => optional($c->consultation_date)->format('Y-m-d'),
                    'consultation_type' => $c->consultation_type,
                    'type_label' => $c->type_label,
                    'provider_name' => optional($c->attendedBy)->name,
                    'chief_complaint' => $c->chief_complaint,
                    'assessment_diagnosis' => $c->assessment_diagnosis,
                    'status_classification' => $c->status_classification,
                    'vital_bp' => $c->vital_bp,
                    'vital_bmi' => $c->vital_bmi,
                    'related_ncd_assessment_id' => $c->related_ncd_assessment_id,
                ];
            });

        return response()->json([
            'patient_id' => $patient->id,
            'consultations' => $consultations,
        ]);
    }

    /**
     * Universal New Consultation form. Automatically pulls today\'s BHW
     * triage record (or the most recent one if nothing was recorded
     * today) so the doctor doesn't have to re-enter vitals/symptoms
     * that were already captured at triage.
     */
    public function create($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $hasActiveTriageToday = TriageRecord::where('patient_id', $patient->id)
            ->whereNotIn('status', ['Done', 'done', 'Consulted', 'consulted'])
            ->exists();

        abort_unless(
            $hasActiveTriageToday,
            403,
            'This patient must be actively in today\'s triage queue before a New Consultation can be started.'
        );

        $ncdAssessments = NcdAssessment::where('patient_id', $patient->id)
            ->orderByDesc('assessment_date')
            ->get(['id', 'assessment_date', 'is_diabetic', 'is_hypertensive']);

        $previousConsultations = Consultation::where('patient_id', $patient->id)
            ->orderByDesc('consultation_date')
            ->get(['id', 'consultation_date', 'consultation_type', 'assessment_diagnosis']);

        $medicines = \App\Models\Medicine::orderBy('name')->get(['id', 'name', 'dosage_strength', 'unit']);

        $latestTriage = TriageRecord::where('patient_id', $patient->id)
            ->whereDate('created_at', today())
            ->latest()
            ->first()
            ?? TriageRecord::where('patient_id', $patient->id)->latest()->first();

        return view('consultations.create', compact(
            'patient', 'ncdAssessments', 'previousConsultations', 'latestTriage', 'medicines'
        ));
    }

    /**
     * Save a new consultation record, including any structured
     * prescription line items and the linked triage record (if any).
     */
    public function store(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $validated = $request->validate([
            'consultation_type' => 'required|in:general,ncd_risk_assessment,ncd_followup,followup_recheck',
            'consultation_date' => 'required|date',
            'reason_for_visit' => 'nullable|string|max:255',
            'chief_complaint' => 'nullable|string',

            'vital_bp' => 'nullable|string|max:20',
            'vital_temp' => 'nullable|string|max:10',
            'vital_pulse' => 'nullable|string|max:10',
            'vital_resp_rate' => 'nullable|string|max:10',
            'vital_o2sat' => 'nullable|string|max:10',
            'vital_weight' => 'nullable|numeric',
            'vital_height' => 'nullable|numeric',

            'assessment_diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'health_advice' => 'nullable|string',
            'referral' => 'nullable|string',
            'status_classification' => 'nullable|string|max:100',
            'follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string',

            'related_ncd_assessment_id' => 'nullable|exists:ncd_assessments,id',
            'related_consultation_id' => 'nullable|exists:consultations,id',
            'triage_record_id' => 'nullable|exists:triage_records,id',

            'details' => 'nullable|array',

            // Structured prescription line items
            'rx_medicine_id' => 'nullable|array',
            'rx_medicine_id.*' => 'nullable|exists:medicines,id',
            'rx_dosage' => 'nullable|array',
            'rx_frequency' => 'nullable|array',
            'rx_duration' => 'nullable|array',
            'rx_quantity' => 'nullable|array',
            'rx_unit' => 'nullable|array',
            'rx_instructions' => 'nullable|array',
        ]);

        // Copy vitals from the linked triage record (if any) into the
        // consultation's own columns, so screens that read vitals
        // directly off the consultation (like the Medical History
        // timeline) keep working without needing to know about the
        // triage relationship. Must happen BEFORE the BMI calc below,
        // since the form itself no longer submits weight/height directly.
        if (!empty($validated['triage_record_id'])) {
            $triage = \App\Models\TriageRecord::find($validated['triage_record_id']);
            if ($triage) {
                $validated['vital_temp'] = $validated['vital_temp'] ?? $triage->temp;
                $validated['vital_bp'] = $validated['vital_bp'] ?? $triage->bp;
                $validated['vital_weight'] = $validated['vital_weight'] ?? $triage->weight;
                $validated['vital_height'] = $validated['vital_height'] ?? $triage->height;
                $validated['reason_for_visit'] = $validated['reason_for_visit'] ?? $triage->service_type;
                $validated['status_classification'] = $validated['status_classification']
                    ?? ($triage->risk_level ? $triage->risk_level . ' Risk' : null);
            }
        }

        if (!empty($validated['vital_weight']) && !empty($validated['vital_height']) && is_numeric($validated['vital_weight']) && is_numeric($validated['vital_height'])) {
            $validated['vital_bmi'] = round(
                $validated['vital_weight'] / (($validated['vital_height'] / 100) ** 2),
                1
            );
        }

        // Build a human-readable summary of the prescriptions for the
        // legacy free-text `medicines` column (used by report/timeline
        // summaries), while the real structured data goes to its own table.
        $rxMedicineIds = $request->input('rx_medicine_id', []);
        $medicineNamesById = \App\Models\Medicine::whereIn('id', array_filter($rxMedicineIds))
            ->pluck('name', 'id');
        $medicinesSummary = collect($rxMedicineIds)
            ->filter(fn($id) => !empty($id))
            ->map(fn($id) => $medicineNamesById[$id] ?? null)
            ->filter()
            ->values();

        $consultation = DB::transaction(function () use ($validated, $patient, $request, $medicinesSummary, $medicineNamesById) {
            $consultation = Consultation::create(array_merge($validated, [
                'patient_id' => $patient->id,
                'attended_by' => auth()->id(),
                'medicines' => $medicinesSummary->implode(', '),
            ]));

            $rxMedicineIds = $request->input('rx_medicine_id', []);
            $rxDosage = $request->input('rx_dosage', []);
            $rxFrequency = $request->input('rx_frequency', []);
            $rxDuration = $request->input('rx_duration', []);
            $rxQuantity = $request->input('rx_quantity', []);
            $rxUnit = $request->input('rx_unit', []);
            $rxInstructions = $request->input('rx_instructions', []);

            foreach ($rxMedicineIds as $i => $medicineId) {
                if (empty($medicineId)) {
                    continue;
                }
                ConsultationPrescription::create([
                    'consultation_id' => $consultation->id,
                    'medicine_id' => $medicineId,
                    'medicine_name' => $medicineNamesById[$medicineId] ?? 'Unknown Medicine',
                    'dosage' => $rxDosage[$i] ?? null,
                    'frequency' => $rxFrequency[$i] ?? null,
                    'duration' => $rxDuration[$i] ?? null,
                    'quantity' => $rxQuantity[$i] ?? null,
                    'status' => 'pending',
                    'unit' => $rxUnit[$i] ?? null,
                    'instructions' => $rxInstructions[$i] ?? null,
                ]);
            }

            return $consultation;
        });

        if ($validated['consultation_type'] === Consultation::TYPE_NCD_RISK_ASSESSMENT) {
            return redirect()
                ->route('ncd.create', ['id' => $patient->id, 'link_consultation' => $consultation->id])
                ->with('success', 'Consultation saved. Please complete the NCD Risk Assessment form below.');
        }

        $role = strtolower(auth()->user()->role);

        return redirect()
            ->route($role . '.patient-records', [
                'view_patient' => $patient->id,
                'tab' => 'medical-history',
            ])
            ->with('success', 'Consultation recorded successfully.');
    }

    /**
     * Read-only consultation details view. Viewable by any authenticated
     * staff role, matching the same access pattern as the rest of the
     * patient records module (write actions are what's restricted, not
     * viewing).
     */
    public function show($id)
    {
        $consultation = Consultation::with([
            'patient', 'attendedBy:id,name', 'triageRecord',
            'relatedNcdAssessment', 'relatedConsultation', 'prescriptions',
        ])->findOrFail($id);

        return view('consultations.show', compact('consultation'));
    }
}
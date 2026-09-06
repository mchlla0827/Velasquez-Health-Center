<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\NcdAssessment;
use App\Models\Patient;
use Illuminate\Http\Request;

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
     * Universal New Consultation form (type selector + dynamic sections).
     */
    public function create($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $ncdAssessments = NcdAssessment::where('patient_id', $patient->id)
            ->orderByDesc('assessment_date')
            ->get(['id', 'assessment_date', 'is_diabetic', 'is_hypertensive']);

        $previousConsultations = Consultation::where('patient_id', $patient->id)
            ->orderByDesc('consultation_date')
            ->get(['id', 'consultation_date', 'consultation_type', 'assessment_diagnosis']);

        return view('consultations.create', compact('patient', 'ncdAssessments', 'previousConsultations'));
    }

    /**
     * Save a new consultation record.
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
            'medicines' => 'nullable|string',
            'health_advice' => 'nullable|string',
            'referral' => 'nullable|string',
            'status_classification' => 'nullable|string|max:100',
            'follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string',

            'related_ncd_assessment_id' => 'nullable|exists:ncd_assessments,id',
            'related_consultation_id' => 'nullable|exists:consultations,id',

            'details' => 'nullable|array',
        ]);

        if (!empty($validated['vital_weight']) && !empty($validated['vital_height'])) {
            $validated['vital_bmi'] = round(
                $validated['vital_weight'] / (($validated['vital_height'] / 100) ** 2),
                1
            );
        }

        $consultation = Consultation::create(array_merge($validated, [
            'patient_id' => $patient->id,
            'attended_by' => auth()->id(),
        ]));

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
     * Phase 4: read-only consultation details view.
     * Not yet implemented.
     */
    public function show($id)
    {
        abort(501, 'Consultation details view is not built yet (Phase 4).');
    }
}
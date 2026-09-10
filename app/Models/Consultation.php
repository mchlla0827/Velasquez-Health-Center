<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    const TYPE_GENERAL = 'general';
    const TYPE_NCD_RISK_ASSESSMENT = 'ncd_risk_assessment';
    const TYPE_NCD_FOLLOWUP = 'ncd_followup';
    const TYPE_FOLLOWUP_RECHECK = 'followup_recheck';

    const TYPE_LABELS = [
        self::TYPE_GENERAL => 'General Consultation',
        self::TYPE_NCD_RISK_ASSESSMENT => 'NCD Risk Assessment',
        self::TYPE_NCD_FOLLOWUP => 'NCD Follow-up',
        self::TYPE_FOLLOWUP_RECHECK => 'Follow-up / Re-check',
    ];

    protected $fillable = [
        'patient_id', 'attended_by', 'triage_record_id',
        'consultation_type', 'consultation_date',
        'reason_for_visit', 'chief_complaint',
        'vital_bp', 'vital_temp', 'vital_pulse', 'vital_resp_rate', 'vital_o2sat',
        'vital_weight', 'vital_height', 'vital_bmi',
        'assessment_diagnosis', 'treatment', 'medicines', 'health_advice', 'referral',
        'status_classification', 'follow_up_date', 'notes',
        'related_ncd_assessment_id', 'related_consultation_id',
        'details',
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'follow_up_date' => 'date',
        'details' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function attendedBy()
    {
        return $this->belongsTo(User::class, 'attended_by');
    }

    public function relatedNcdAssessment()
    {
        return $this->belongsTo(NcdAssessment::class, 'related_ncd_assessment_id');
    }

    public function relatedConsultation()
    {
        return $this->belongsTo(Consultation::class, 'related_consultation_id');
    }

    public function triageRecord()
    {
        return $this->belongsTo(TriageRecord::class, 'triage_record_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(ConsultationPrescription::class);
    }

    public function getTypeLabelAttribute()
    {
        return self::TYPE_LABELS[$this->consultation_type] ?? $this->consultation_type;
    }
}

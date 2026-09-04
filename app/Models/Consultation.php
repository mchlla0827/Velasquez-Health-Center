<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'consultation_id', 'patient_id', 'attended_by', 'consultation_type',
        'consultation_date', 'reason_for_visit', 'chief_complaint',
        'blood_pressure', 'temperature', 'pulse_rate', 'respiratory_rate',
        'oxygen_saturation', 'weight', 'height', 'bmi', 'assessment',
        'treatment', 'medicines', 'follow_up_date', 'details',
        'previous_consultation_id', 'ncd_assessment_id',
    ];

    protected $casts = [
        'consultation_date' => 'datetime',
        'follow_up_date' => 'date',
        'details' => 'array',
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function provider() { return $this->belongsTo(User::class, 'attended_by'); }
    public function previousConsultation() { return $this->belongsTo(self::class, 'previous_consultation_id'); }
    public function ncdAssessment() { return $this->belongsTo(NcdAssessment::class); }
}
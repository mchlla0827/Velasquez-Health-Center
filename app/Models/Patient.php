<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    // ✅ DAGDAG ITO — ITO ANG NAWAWALA
    public $timestamps = true; // Ito ang nag-uutos na ilagay ang oras sa created_at / updated_at
    protected $dateFormat = 'Y-m-d H:i:s'; // Siguraduhin na tamang format ng petsa ang ilalagay

    protected $fillable = [
        'patient_id', 'last_name', 'first_name', 'middle_name',
        'mother_last', 'mother_first', 'mother_middle',
        'father_last', 'father_first', 'father_middle',
        'address', 'barangay', 'family_number', 'contact_number',
        'email', 'dob', 'pob', 'gender', 'civil_status',
        'osca_pwd_no', 'four_ps_no', 'religion', 'educational_attainment',
        'philhealth', 'philhealth_no_member', 'philhealth_no_dependent',
        'philhealth_member_name', 'philhealth_member_dob',
        'tracking_immunization','tracking_maternal','in_queue', 'reason', 'risk_level',
        'screen_temp', 'screen_bp', 'screen_weight', 'screen_height', 'triage_symptoms'
    ];

    /**
     * Get all of the triage records for the patient.
     * This links the patient to their health clinic check-ins/visits.
     */
    public function triageRecords()
    {
        return $this->hasMany(TriageRecord::class, 'patient_id', 'id');
    }
}
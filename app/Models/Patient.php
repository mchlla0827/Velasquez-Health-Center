<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DispensingRecord;
use Carbon\Carbon;

class Patient extends Model
{
    // âœ… DAGDAG ITO â€” ITO ANG NAWAWALA
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
        'screen_temp', 'screen_bp', 'screen_weight', 'screen_height', 'triage_symptoms',
        'vac_bcg', 'vac_hepa', 'vac_penta1', 'vac_opv1', 'vac_pcv1',
        'vac_ipv1', 'vac_ipv2', 'vac_penta2', 'vac_opv2', 'vac_pcv2',
        'vac_penta3', 'vac_opv3', 'vac_pcv3', 'vac_mr1', 'vac_mmr1',
        'vac_mmr2', 'vac_hpv1', 'vac_hpv2', 'vac_flu', 'vac_pneumo', 'vac_td',
        'mat_nbs', 'mat_nbs_date', 'mat_nbs_result',
        'mat_hearing', 'mat_hearing_date', 'mat_hearing_result',
        'mat_birth_order', 'mat_birth_length', 'mat_birth_weight',
        'mat_delivery_type', 'mat_feeding_type', 'mat_attendant', 'mat_delivery_place',
        'mat_vit_a_dose', 'mat_vit_a_date', 'mat_deworming_1', 'mat_deworming_2',
        'ob_g', 'ob_p_t', 'ob_p_p', 'ob_p_a', 'ob_p_l',
        'ob_menarche', 'ob_pmp', 'ob_lmp', 'ob_edc', 'ob_tt_status',
        'ob_td1', 'ob_td2', 'ob_td3', 'ob_td4', 'ob_td5',
    ];

    /**
     * Get all of the triage records for the patient.
     * This links the patient to their health clinic check-ins/visits.
     */
    public function triageRecords()
    {
        return $this->hasMany(TriageRecord::class, 'patient_id', 'id');
    }

    // Append 'age' dynamically when model is serialized
    protected $appends = ['age'];

    public function getAgeAttribute()
    {
        if (!$this->dob) {
            return 'N/A';
        }

        return Carbon::parse($this->dob)->age;
    }

    public function dispensingRecords()
{
    return $this->hasMany(
        DispensingRecord::class,
        'patient_ptn',
        'patient_id'
    );
}
}
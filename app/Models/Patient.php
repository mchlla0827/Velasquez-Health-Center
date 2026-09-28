<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DispensingRecord;
use Carbon\Carbon;

class Patient extends Model
{
    // ADD THIS - THIS IS WHAT'S MISSING
    public $timestamps = true; // Ito ang nag-uutos na ilagay ang oras sa created_at / updated_at
    protected $dateFormat = 'Y-m-d H:i:s'; // Siguraduhin na tamang format ng petsa ang ilalagay

    protected $fillable = [
        'patient_status',
        'archived_at',
        'residency_proof_path',
        'residency_proof_path',
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
    protected $appends = ['age', 'age_display'];

    public function getAgeAttribute()
    {
        if (!$this->dob) {
            return 'N/A';
        }

        return Carbon::parse($this->dob)->age;
    }

    /**
     * Human-readable age for DISPLAY only - shows months for infants
     * under 1 year old instead of "0 years old", matching real-world
     * clinical convention. The raw numeric age above is left
     * untouched, since other parts of the system (age-group reports,
     * risk scoring, etc.) depend on it being a plain integer.
     */
    public function getAgeDisplayAttribute()
    {
        if (!$this->dob) {
            return 'N/A';
        }

        $dob = Carbon::parse($this->dob)->startOfDay();
        $today = Carbon::now()->startOfDay();

        $years = (int) $dob->diffInYears($today);
        $months = (int) $dob->diffInMonths($today);
        $days = (int) $dob->diffInDays($today);

        if ($years >= 1) {
            return $years . ' year' . ($years === 1 ? '' : 's') . ' old';
        }

        if ($months >= 1) {
            return $months . ' month' . ($months === 1 ? '' : 's') . ' old';
        }

        return $days . ' day' . ($days === 1 ? '' : 's') . ' old';
    }

    public function dispensingRecords()
{
    return $this->hasMany(
        DispensingRecord::class,
        'patient_ptn',
        'patient_id'
    );
}

    /**
     * Archiving lifecycle constants.
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';

    protected $casts = [
        'archived_at' => 'datetime',
    ];

    /**
     * The patient's most recent REAL health-center activity date across
     * every relevant module - never the registration date. Falls back
     * to the registration date only if the patient has no activity at
     * all yet (a brand-new patient shouldn't be immediately eligible
     * for archiving just because they have no history).
     */
    public function latestActivityDate()
    {
        $dates = collect([
            optional(\App\Models\TriageRecord::where('patient_id', $this->id)->latest()->first())->created_at,
            optional(\App\Models\Consultation::where('patient_id', $this->id)->latest()->first())->created_at,
            optional(\App\Models\NcdAssessment::where('patient_id', $this->id)->latest()->first())->created_at,
            optional(\App\Models\DispensingRecord::where('patient_ptn', $this->patient_id)->latest()->first())->created_at,
        ])->filter();

        return $dates->isNotEmpty() ? $dates->max() : $this->created_at;
    }

    /**
     * Eligible for archiving: currently active, and no real activity
     * for 5+ years (based on latestActivityDate, not registration date).
     */
    public function isArchiveEligible(): bool
    {
        if ($this->patient_status !== self::STATUS_ACTIVE) {
            return false;
        }

        return $this->latestActivityDate() <= now()->subYears(5);
    }

    /**
     * Sweep every currently-active patient and automatically archive
     * anyone who has hit 5+ years of real inactivity. This is what
     * makes archiving automatic instead of a manual per-patient click:
     * it's called by the scheduled console command (patients:archive-
     * inactive) AND, as a self-healing safety net, from the Patients
     * and Archived Patients pages themselves (throttled - see
     * PatientController) so it still works correctly even in a local
     * environment where the task scheduler isn't running.
     *
     * Nothing is deleted. This only flips patient_status/archived_at,
     * exactly like the existing manual archivePatient() action did.
     *
     * Returns how many patients were archived by this sweep.
     */
    public static function runAutoArchiveSweep(): int
    {
        $archivedCount = 0;

        static::where('patient_status', self::STATUS_ACTIVE)
            ->chunkById(200, function ($patients) use (&$archivedCount) {
                foreach ($patients as $patient) {
                    if ($patient->isArchiveEligible()) {
                        $patient->update([
                            'patient_status' => self::STATUS_ARCHIVED,
                            'archived_at' => now(),
                        ]);
                        $archivedCount++;
                    }
                }
            });

        return $archivedCount;
    }
}
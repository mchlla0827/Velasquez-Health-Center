<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NcdAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'assessed_by',
        'health_facility', 'assessment_date', 'family_no',
        'first_name', 'middle_name', 'last_name', 'id_no',
        'address', 'barangay', 'telepono', 'birthday', 'edad',
        'kasarian', 'estadocivil', 'relihiyon', 'educational_attainment',
        'is_diabetic', 'is_diabetic_year', 'is_diabetic_meds', 'risk_dm',
        'is_hypertensive', 'is_hypertensive_year', 'is_hypertensive_meds', 'risk_hpn',
        'has_copd', 'has_copd_year', 'has_copd_meds', 'risk_copd',
        'has_cancer', 'cancer_site_condition', 'cancer_year', 'cancer_meds', 'risk_cancer',
        'has_eye_disease', 'eye_year', 'eye_meds',
        'cp1','cp2','cp3','cp4','cp5','cp6','cp7','cp8',
        'r_diet','r_salt',
        'diet_gulay','diet_prutas','diet_isda','diet_karne','diet_processed_food',
        'diet_maalat','diet_matatamis','diet_mamantika',
        'alc_u','alc_q','alc_t','amt_b','amt_w','amt_s','alc_f','alc_b','r_binge',
        'w','h','bmi','bmi_s','r_over','r_obese',
        'waist','hip','whr','whr_s','r_whr',
        'fbs','vn','fbs_s','r_predm','rbs_s','s_pol','s_wgt','r_dm_f',
        'bp_l','bp_r','bp_b','bp_s','r_hpn_f',
        'chol','ch_s','r_chol',
        'pro','ket','r_pro',
        'rp','r_30','cs'
    ];

    protected $casts = [
        'alc_t' => 'array',
        'is_diabetic' => 'boolean',
        'is_hypertensive' => 'boolean',
        'has_copd' => 'boolean',
        'has_cancer' => 'boolean',
        'has_eye_disease' => 'boolean',
        'risk_dm' => 'boolean',
        'risk_hpn' => 'boolean',
        'risk_copd' => 'boolean',
        'risk_cancer' => 'boolean',
        'r_diet' => 'boolean',
        'r_salt' => 'boolean',
        'r_binge' => 'boolean',
        'r_over' => 'boolean',
        'r_obese' => 'boolean',
        'r_whr' => 'boolean',
        'r_predm' => 'boolean',
        's_pol' => 'boolean',
        's_wgt' => 'boolean',
        'r_dm_f' => 'boolean',
        'r_hpn_f' => 'boolean',
        'r_chol' => 'boolean',
        'r_pro' => 'boolean',
        'r_30' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function assessedBy()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
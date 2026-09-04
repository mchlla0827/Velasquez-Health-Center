@php
    $attributes = $assessment->getAttributes();
    $sections = [
        '1. Personal Information' => ['health_facility','family_no','first_name','middle_name','last_name','id_no','address','barangay','telepono','birthday','edad','kasarian','estadocivil','relihiyon','educational_attainment'],
        '2. Existing Medical Conditions' => ['is_diabetic','is_diabetic_year','is_diabetic_meds','is_hypertensive','is_hypertensive_year','is_hypertensive_meds','has_copd','has_copd_year','has_copd_meds','has_cancer','cancer_site_condition','cancer_year','cancer_meds','has_eye_disease','eye_year','eye_meds'],
        '3. Chest Pain Assessment' => ['cp1','cp2','cp3','cp4','cp5','cp6','cp7','cp8'],
        '4. Diet and Nutrition' => ['r_diet','r_salt','diet_gulay','diet_prutas','diet_isda','diet_karne','diet_processed_food','diet_maalat','diet_matatamis','diet_mamantika'],
        '5. Alcohol Intake' => ['alc_u','alc_q','amt_b','amt_w','amt_s','alc_f','alc_b','alc_t','r_binge'],
        '6. Anthropometrics' => ['w','h','bmi','bmi_s','waist','hip','whr','whr_s'],
        '7. Blood Pressure' => ['bp_l','bp_r','bp_b','bp_s'],
        '8. Blood Sugar / Diabetes Screening' => ['fbs','vn','fbs_s','r_predm','rbs_s','s_pol','s_wgt','r_dm_f'],
        '9. Cholesterol' => ['chol','ch_s'],
        '10. Urinalysis' => ['pro','ket'],
        '11. Risk Indicators' => ['risk_dm','risk_hpn','risk_copd','risk_cancer','r_over','r_obese','r_whr','r_hpn_f','r_chol','r_pro','r_30'],
        '12. Recommendations and Notes' => ['rp','cs'],
    ];
    $display = fn ($field, $raw) => is_bool($raw) ? ($raw ? 'Yes' : 'No') : $raw;
@endphp
<div class="mh-form-view mh-ncd-record">
    <div class="mh-form-head mh-ncd-header"><div><span class="mh-kicker">Historical record</span><h4>Full NCD Risk Assessment</h4><p>Assessment date: {{ $assessment->assessment_date ?: 'Undated' }} · Assessed by: {{ $assessment->assessedBy->name ?? '---' }}</p></div><div class="mh-risk-banner"><span>Overall risk</span><strong>{{ $assessment->cs ?: 'Not classified' }}</strong></div><button class="btn-secondary" type="button" onclick="loadMedicalHistory()">Back to Medical History</button></div>
    @foreach($sections as $heading => $fields)
        @php $visible = array_filter($fields, fn ($field) => array_key_exists($field, $attributes) && $attributes[$field] !== null && $attributes[$field] !== ''); @endphp
        @if($visible)
            <section class="mh-form-section mh-ncd-section"><h5>{{ $heading }}</h5><div class="mh-ncd-grid">@foreach($visible as $field)<div class="mh-readonly-line"><label>{{ ucwords(str_replace('_', ' ', $field)) }}</label><span>{{ $display($field, $attributes[$field]) }}</span></div>@endforeach</div></section>
        @endif
    @endforeach
</div>
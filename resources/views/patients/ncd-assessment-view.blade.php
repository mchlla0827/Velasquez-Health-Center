<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('NCD Risk Assessment') }}
        </h2>
    </x-slot>

@php
    $yn = function ($v) {
        if ($v === true || $v === 'Yes' || $v === 'Oo') return 'Yes';
        if ($v === false || $v === 'No' || $v === 'Hindi') return 'No';
        return '—';
    };
    $dash = fn ($v) => ($v === null || $v === '') ? '—' : $v;
@endphp

<div style="max-width: 900px; margin: 0 auto; padding: 24px 16px;">

    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0; font-size: 20px; color: #111827;">NCD Risk Assessment</h2>
            <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">
                {{ $patient->first_name }} {{ $patient->last_name }} &middot; {{ $patient->patient_id }} &middot; Read-only record
            </p>
        </div>
        <a href="{{ url()->previous() }}" style="font-size: 13px; font-weight: 600; color: #374151; border: 1px solid #D1D5DB; background: #fff; padding: 8px 16px; border-radius: 8px; text-decoration: none;">
            &larr; Back
        </a>
    </div>

    <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 10px 14px; margin-bottom: 20px; font-size: 12.5px; color: #1E40AF;">
        This is a read-only view of a saved assessment. To conduct a new assessment, use the "Conduct NCD Risk Assessment" action from the patient record.
    </div>

    {{-- Assessment Information --}}
    <div class="ro-card">
        <h5>Assessment Information</h5>
        <div class="ro-grid">
            <div class="ro-item"><span>Health Facility</span><p>{{ $dash($ncdAssessment->health_facility) }}</p></div>
            <div class="ro-item"><span>Assessment Date</span><p>{{ $dash($ncdAssessment->assessment_date) }}</p></div>
            <div class="ro-item"><span>Family No.</span><p>{{ $dash($ncdAssessment->family_no) }}</p></div>
            <div class="ro-item"><span>Assessed By</span><p>{{ $dash(optional($ncdAssessment->assessedBy)->name) }}</p></div>
            <div class="ro-item"><span>Designation</span><p>{{ $dash($ncdAssessment->designation) }}</p></div>
            <div class="ro-item"><span>Occupation</span><p>{{ $dash($ncdAssessment->occupation) }}</p></div>
            <div class="ro-item"><span>Sign Date</span><p>{{ $dash($ncdAssessment->sign_date) }}</p></div>
            <div class="ro-item"><span>ID No.</span><p>{{ $dash($ncdAssessment->id_no) }}</p></div>
            <div class="ro-item"><span>Address / Barangay</span><p>{{ $dash($ncdAssessment->address) }} {{ $ncdAssessment->barangay ? ', '.$ncdAssessment->barangay : '' }}</p></div>
        </div>
    </div>

    {{-- Past Medical History --}}
    <div class="ro-card">
        <h5>Past Medical History</h5>
        <table class="ro-table">
            <tr>
                <td><b>Diabetes</b> — {{ $yn($ncdAssessment->is_diabetic) }}{{ $ncdAssessment->is_diabetic ? ' ('.$dash($ncdAssessment->is_diabetic_year).', '.$dash($ncdAssessment->is_diabetic_meds).')' : '' }}</td>
                <td>@if($ncdAssessment->risk_dm)<span class="ro-tag">DM Flag</span>@endif</td>
            </tr>
            <tr>
                <td><b>Hypertension</b> — {{ $yn($ncdAssessment->is_hypertensive) }}{{ $ncdAssessment->is_hypertensive ? ' ('.$dash($ncdAssessment->is_hypertensive_year).', '.$dash($ncdAssessment->is_hypertensive_meds).')' : '' }}</td>
                <td>@if($ncdAssessment->risk_hpn)<span class="ro-tag">HPN Flag</span>@endif</td>
            </tr>
            <tr>
                <td><b>Cancer</b> — {{ $yn($ncdAssessment->has_cancer) }}{{ $ncdAssessment->has_cancer ? ' ('.$dash($ncdAssessment->cancer_year).', '.$dash($ncdAssessment->cancer_meds).')' : '' }}</td>
                <td>@if($ncdAssessment->risk_cancer)<span class="ro-tag">Cancer Flag</span>@endif</td>
            </tr>
            <tr>
                <td><b>COPD / Sakit sa baga</b> — {{ $yn($ncdAssessment->has_copd) }}{{ $ncdAssessment->has_copd ? ' ('.$dash($ncdAssessment->has_copd_year).', '.$dash($ncdAssessment->has_copd_meds).')' : '' }}</td>
                <td>@if($ncdAssessment->risk_copd)<span class="ro-tag">COPD Flag</span>@endif</td>
            </tr>
            <tr>
                <td><b>Sakit sa mata</b> — {{ $yn($ncdAssessment->has_eye_disease) }}{{ $ncdAssessment->has_eye_disease ? ' ('.$dash($ncdAssessment->eye_year).', '.$dash($ncdAssessment->eye_meds).')' : '' }}</td>
                <td></td>
            </tr>
        </table>
        @if($ncdAssessment->cancer_site_condition)
            <p style="font-size: 13px; color: #374151; margin-top: 10px;"><b>Cancer site / klase:</b> {{ $ncdAssessment->cancer_site_condition }}</p>
        @endif
    </div>

    {{-- Chest Pain Screening --}}
    @php
        $cpQuestions = [
            'cp1' => '2.1 Nakakaramdam ka ba ng pananakit o kabigatan sa dibdib?',
            'cp2' => '2.2 Ang sakit ba ay nasa gitna ng dibdib, kaliwang bahagi hanggang sa kaliwang braso?',
            'cp3' => '2.3 Nararamdaman mo ba ito kung nagmamadali o naglalakad ng mabilis o paakyat?',
            'cp4' => '2.4 Napapatigil ka ba sa paglalakad kapag sumasakit ang iyong dibdib?',
            'cp5' => '2.5 Nawawala ba ang sakit kapag hindi ka kumikilos o naglagay ng gamot sa ilalim ng dila?',
            'cp6' => '2.6 Nawawala ba ng sakit sa loob ng 10 minuto?',
            'cp7' => '2.7 Nakakaramdam ka ba ng sakit sa dibdib na tumatagal higit sa 30 minuto?',
            'cp8' => '2.8 Hirap sa pagsasalita, panghihina ng braso/binti, o pamamanhid sa kalahating bahagi ng katawan?',
        ];
        $cpDanger = in_array($ncdAssessment->cp4, ['Yes']) || in_array($ncdAssessment->cp5, ['Yes'])
            || in_array($ncdAssessment->cp6, ['Yes']) || in_array($ncdAssessment->cp7, ['Yes']);
    @endphp
    <div class="ro-card">
        <h5>Chest Pain / Angina Screening</h5>
        <table class="ro-table">
            @foreach ($cpQuestions as $field => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td style="text-align:right; font-weight:700; color: {{ $ncdAssessment->$field === 'Yes' ? '#DC2626' : '#9CA3AF' }};">
                        {{ $yn($ncdAssessment->$field) }}
                    </td>
                </tr>
            @endforeach
        </table>
        @if($cpDanger)
            <div class="ro-warn">Kung Oo sagot sa Q2.4-2.7, maaring may angina o impending heart attack. Dalhin kaagad sa doktor.</div>
        @endif
        @if($ncdAssessment->cp8 === 'Yes')
            <div class="ro-warn">Q2.8 is positive. Dalhin agad ang pasyente sa Doktor.</div>
        @endif
    </div>

    {{-- Family History --}}
    @php
        $famFlags = [
            'fam_hypertension' => 'Mataas na presyon',
            'fam_heart_disease' => 'Sakit sa puso',
            'fam_stroke' => 'Stroke',
            'fam_diabetes' => 'Diabetes',
            'fam_cancer' => 'Kanser',
            'fam_kidney_disease' => 'Sakit sa bato',
            'fam_lung_disease' => 'Sakit sa baga (non-communicable)',
        ];
        $famActive = collect($famFlags)->filter(fn ($label, $field) => $ncdAssessment->$field);
    @endphp
    <div class="ro-card">
        <h5>Family History</h5>
        <div class="ro-chips">
            @forelse ($famActive as $field => $label)
                <span class="ro-chip">{{ $label }}</span>
            @empty
                <span class="ro-chip">None reported</span>
            @endforelse
        </div>
        @if($ncdAssessment->fam_other)
            <p style="font-size: 13px; color: #374151; margin-top: 10px;"><b>Iba pang sakit:</b> {{ $ncdAssessment->fam_other }}</p>
        @endif
    </div>

    {{-- Lifestyle --}}
    <div class="ro-card">
        <h5>Lifestyle (Modifiable Risk Factors)</h5>

        <p class="ro-sub">Nutrition</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Gulay (daily)</span><p>{{ $yn($ncdAssessment->diet_gulay) }}</p></div>
            <div class="ro-item"><span>Prutas (daily)</span><p>{{ $yn($ncdAssessment->diet_prutas) }}</p></div>
            <div class="ro-item"><span>Isda (daily)</span><p>{{ $yn($ncdAssessment->diet_isda) }}</p></div>
            <div class="ro-item"><span>Karne (daily)</span><p>{{ $yn($ncdAssessment->diet_karne) }}</p></div>
            <div class="ro-item"><span>Processed food (daily)</span><p>{{ $yn($ncdAssessment->diet_processed_food) }}</p></div>
            <div class="ro-item"><span>Maalat (&gt;2x/week)</span><p>{{ $yn($ncdAssessment->diet_maalat) }}</p></div>
            <div class="ro-item"><span>Matatamis (&gt;2x/week)</span><p>{{ $yn($ncdAssessment->diet_matatamis) }}</p></div>
            <div class="ro-item"><span>Mamantika (&gt;2x/week)</span><p>{{ $yn($ncdAssessment->diet_mamantika) }}</p></div>
            <div class="ro-item"><span>Unhealthy diet flag</span><p>{{ $yn($ncdAssessment->r_diet) }}</p></div>
            <div class="ro-item"><span>High salt intake flag</span><p>{{ $yn($ncdAssessment->r_salt) }}</p></div>
        </div>

        <p class="ro-sub">Alcohol</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Umiinom ng alak</span><p>{{ $dash($ncdAssessment->alc_u) }}</p></div>
            <div class="ro-item"><span>Tagal tumigil</span><p>{{ $dash($ncdAssessment->alc_q) }}</p></div>
            <div class="ro-item"><span>Klase ng alak</span><p>{{ $dash(is_array($ncdAssessment->alc_t) ? implode(', ', $ncdAssessment->alc_t) : $ncdAssessment->alc_t) }}</p></div>
            <div class="ro-item"><span>Dalas kada linggo</span><p>{{ $dash($ncdAssessment->alc_f) }}</p></div>
            <div class="ro-item"><span>Beer (kada araw)</span><p>{{ $dash($ncdAssessment->amt_b) }}</p></div>
            <div class="ro-item"><span>Wine (kada araw)</span><p>{{ $dash($ncdAssessment->amt_w) }}</p></div>
            <div class="ro-item"><span>Whisky/gin/brandy</span><p>{{ $dash($ncdAssessment->amt_s) }}</p></div>
            <div class="ro-item"><span>Bote kada okasyon</span><p>{{ $dash($ncdAssessment->alc_b) }}</p></div>
            <div class="ro-item"><span>Binge drinker flag</span><p>{{ $yn($ncdAssessment->r_binge) }}</p></div>
        </div>

        <p class="ro-sub">Exercise</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Sapat na ehersisyo</span><p>{{ $dash($ncdAssessment->has_exercise) }}</p></div>
            <div class="ro-item"><span>Klase ng ehersisyo</span><p>{{ $dash($ncdAssessment->exercise_type) }}</p></div>
            <div class="ro-item"><span>Insufficient activity flag</span><p>{{ $yn($ncdAssessment->risk_activity) }}</p></div>
        </div>

        <p class="ro-sub">Smoking</p>
        @php
            $smokeMap = ['current' => 'Oo, naninigarilyo', 'quit' => 'Oo, pero tumigil na', 'never' => 'Hindi'];
        @endphp
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Status</span><p>{{ $dash($smokeMap[$ncdAssessment->smoke_status] ?? $ncdAssessment->smoke_status) }}</p></div>
            <div class="ro-item"><span>Sticks kada araw</span><p>{{ $dash($ncdAssessment->smoke_sticks_per_day) }}</p></div>
            <div class="ro-item"><span>Tagal tumigil</span><p>{{ $dash($ncdAssessment->smoke_quit_duration) }}</p></div>
            <div class="ro-item"><span>Naka-100 sticks</span><p>{{ $yn($ncdAssessment->smoke_100_sticks) }}</p></div>
            <div class="ro-item"><span>Lantad sa usok</span><p>{{ $yn($ncdAssessment->smoke_exposed) }}</p></div>
            <div class="ro-item"><span>History of smoking flag</span><p>{{ $yn($ncdAssessment->risk_smoking_history) }}</p></div>
            <div class="ro-item"><span>Smoker flag</span><p>{{ $yn($ncdAssessment->risk_smoker) }}</p></div>
        </div>

        <p class="ro-sub">Stress</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Madalas ma-stress</span><p>{{ $yn($ncdAssessment->stress_frequent) }}</p></div>
            <div class="ro-item"><span>Dahilan</span><p>{{ $dash($ncdAssessment->stress_cause) }}</p></div>
            <div class="ro-item"><span>Naaapektuhan pamumuhay</span><p>{{ $yn($ncdAssessment->stress_affects_life) }}</p></div>
            <div class="ro-item"><span>Stressed flag</span><p>{{ $yn($ncdAssessment->risk_stress) }}</p></div>
        </div>
    </div>

    {{-- Risk Screening --}}
    <div class="ro-card">
        <h5>Risk Screening</h5>

        <p class="ro-sub">Anthropometric Measurement</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Weight</span><p>{{ $dash($ncdAssessment->w) }} kg</p></div>
            <div class="ro-item"><span>Height</span><p>{{ $dash($ncdAssessment->h) }} cm</p></div>
            <div class="ro-item"><span>BMI</span><p><b>{{ $dash($ncdAssessment->bmi) }}</b></p></div>
            <div class="ro-item"><span>BMI status</span><p><b>{{ $dash($ncdAssessment->bmi_s) }}</b></p></div>
            <div class="ro-item"><span>Waist</span><p>{{ $dash($ncdAssessment->waist) }} cm</p></div>
            <div class="ro-item"><span>Hip</span><p>{{ $dash($ncdAssessment->hip) }} cm</p></div>
            <div class="ro-item"><span>W/H ratio</span><p>{{ $dash($ncdAssessment->whr) }}</p></div>
            <div class="ro-item"><span>W/H status</span><p><b>{{ $dash($ncdAssessment->whr_s) }}</b></p></div>
            <div class="ro-item"><span>Overweight flag</span><p>{{ $yn($ncdAssessment->r_over) }}</p></div>
            <div class="ro-item"><span>Obese flag</span><p>{{ $yn($ncdAssessment->r_obese) }}</p></div>
            <div class="ro-item"><span>At risk (W/H) flag</span><p>{{ $yn($ncdAssessment->r_whr) }}</p></div>
        </div>

        <p class="ro-sub">Blood Sugar</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>FBS (CBG)</span><p>{{ $dash($ncdAssessment->fbs) }}</p></div>
            <div class="ro-item"><span>FBS venous</span><p>{{ $dash($ncdAssessment->vn) }}</p></div>
            <div class="ro-item"><span>FBS status</span><p><b>{{ $dash($ncdAssessment->fbs_s) }}</b></p></div>
            <div class="ro-item"><span>RBS status</span><p><b>{{ $dash($ncdAssessment->rbs_s) }}</b></p></div>
            <div class="ro-item"><span>Polyuria</span><p>{{ $yn($ncdAssessment->s_pol) }}</p></div>
            <div class="ro-item"><span>Polydipsia</span><p>{{ $yn($ncdAssessment->s_pdp) }}</p></div>
            <div class="ro-item"><span>Polyphagia</span><p>{{ $yn($ncdAssessment->s_pph) }}</p></div>
            <div class="ro-item"><span>Weight loss</span><p>{{ $yn($ncdAssessment->s_wgt) }}</p></div>
            <div class="ro-item"><span>Pre-diabetes flag</span><p>{{ $yn($ncdAssessment->r_predm) }}</p></div>
            <div class="ro-item"><span>DM flag</span><p>{{ $yn($ncdAssessment->r_dm_f) }}</p></div>
        </div>

        <p class="ro-sub">Blood Pressure</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Left arm mean BP</span><p>{{ $dash($ncdAssessment->bp_l) }}</p></div>
            <div class="ro-item"><span>Right arm mean BP</span><p>{{ $dash($ncdAssessment->bp_r) }}</p></div>
            <div class="ro-item"><span>Baseline BP</span><p><b>{{ $dash($ncdAssessment->bp_b) }}</b></p></div>
            <div class="ro-item"><span>Status</span><p><b>{{ $dash($ncdAssessment->bp_s) }}</b></p></div>
            <div class="ro-item"><span>Pre-HPN flag</span><p>{{ $yn($ncdAssessment->r_hpn_pre) }}</p></div>
            <div class="ro-item"><span>HPN flag</span><p>{{ $yn($ncdAssessment->r_hpn_f) }}</p></div>
        </div>

        <p class="ro-sub">Cholesterol</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Result</span><p>{{ $dash($ncdAssessment->chol) }}</p></div>
            <div class="ro-item"><span>Status</span><p><b>{{ $dash($ncdAssessment->ch_s) }}</b></p></div>
            <div class="ro-item"><span>High cholesterol flag</span><p>{{ $yn($ncdAssessment->r_chol) }}</p></div>
        </div>

        <p class="ro-sub">Urine Dipstick Test</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Protein</span><p>{{ $dash($ncdAssessment->pro) }}</p></div>
            <div class="ro-item"><span>(+) Protein flag</span><p>{{ $yn($ncdAssessment->r_pro) }}</p></div>
            <div class="ro-item"><span>Ketones</span><p>{{ $dash($ncdAssessment->ket) }}</p></div>
            <div class="ro-item"><span>(+) Ketones flag</span><p>{{ $yn($ncdAssessment->r_ket) }}</p></div>
        </div>

        <p class="ro-sub">Risk Profile &amp; Cancer Screening</p>
        <div class="ro-grid ro-grid-2">
            <div class="ro-item"><span>Risk percentage</span><p><b>{{ $dash($ncdAssessment->rp) }}</b></p></div>
            <div class="ro-item"><span>&ge;30% risk flag</span><p>{{ $yn($ncdAssessment->r_30) }}</p></div>
            <div class="ro-item"><span>Nai-screen sa breast/cervical cancer</span><p>{{ $dash($ncdAssessment->cs) }}</p></div>
        </div>
    </div>

</div>

<style>
.ro-card { background: #fff; border: 1px solid #E5E7EB; border-radius: 10px; padding: 20px; margin-bottom: 16px; }
.ro-card h5 { margin: 0 0 16px; font-size: 14px; color: #111827; border-bottom: 1px solid #F3F4F6; padding-bottom: 8px; }
.ro-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.ro-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 6px; }
.ro-item span { font-size: 11px; color: #9CA3AF; display: block; margin-bottom: 3px; font-weight: 600; text-transform: uppercase; }
.ro-item p { margin: 0; font-size: 13.5px; color: #1F2937; }
.ro-sub { font-size: 11px; font-weight: 700; letter-spacing: .3px; text-transform: uppercase; color: #2563EB; margin: 16px 0 6px; }
.ro-sub:first-of-type { margin-top: 0; }
.ro-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.ro-table td { padding: 8px 4px; border-bottom: 1px solid #F3F4F6; }
.ro-table tr:last-child td { border-bottom: none; }
.ro-tag { display: inline-block; background: #FEE2E2; color: #B91C1C; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 4px; }
.ro-warn { display: flex; gap: 8px; background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 12.5px; padding: 10px 14px; border-radius: 8px; margin-top: 12px; }
.ro-chips { display: flex; flex-wrap: wrap; gap: 7px; }
.ro-chip { background: #F3F4F6; color: #374151; border-radius: 6px; font-size: 12.5px; padding: 5px 11px; }
@media (max-width: 780px) {
    .ro-grid { grid-template-columns: 1fr; }
    .ro-grid-2 { grid-template-columns: 1fr; }
}
</style>
</x-app-layout>
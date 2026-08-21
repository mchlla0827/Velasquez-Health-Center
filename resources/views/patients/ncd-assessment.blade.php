<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <title>Integrated NCD Risk Assessment - {{ $patient->first_name }}</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #E5E7EB; color: #1F2937; padding-bottom: 50px; }
        .container { max-width: 1000px; margin: 40px auto; background: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        .header { background: #1A73E8; color: white; padding: 25px 30px; text-align: center; }
        .header h2 { margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
        .sub-header { background: #F3F4F6; padding: 15px 30px; border-bottom: 2px solid #D1D5DB; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px; font-size: 14px; }
        .form-body { padding: 30px; }
        .section-title { background: #374151; color: white; padding: 10px 15px; font-size: 15px; font-weight: bold; margin: 25px 0 15px; border-radius: 4px; }
        .sub-section-title { font-size: 14px; font-weight: bold; color: #111827; border-bottom: 1px solid #D1D5DB; padding-bottom: 5px; margin: 15px 0; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 12px; }
        .form-group label { font-size: 13px; font-weight: bold; color: #4B5563; margin-bottom: 4px; }
        .form-group input, .form-group select { padding: 6px 10px; border: 1px solid #D1D5DB; border-radius: 4px; font-size: 13px; }
        .check-group { display: flex; align-items: center; gap: 8px; font-size: 14px; margin-bottom: 4px; cursor: pointer; }
        .radio-inline { display: flex; gap: 12px; font-size: 12px; margin-top: 5px; flex-wrap: wrap; }
        .doh-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 14px; table-layout: fixed; }
        .doh-table th, .doh-table td { border: 1px solid #D1D5DB; padding: 8px; text-align: left; vertical-align: top; }
        .doh-table th { background: #F9FAFB; font-weight: bold; }
        .col-yes-no { width: 50px; text-align: center !important; }
        .risk-area { background: #FDF2F2; }
        .paper-line { border: none; border-bottom: 1px solid #374151; outline: none; font-weight: bold; width: 100%; font-size: 13px; }
        input[readonly] { background: transparent; color: inherit; cursor: default; }
        .footer { padding: 20px 30px; background: #F9FAFB; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end; gap: 12px; position: sticky; bottom: 0; z-index: 100; }
        .btn-save { padding: 10px 25px; background: #10B981; border: none; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; font-size: 14px; }
        .btn-save:hover { background: #059669; }
        .btn-cancel { padding: 10px 25px; background: #6B7280; border: none; border-radius: 6px; color: white; text-decoration: none; font-size: 14px; text-align: center; }
        .btn-cancel:hover { background: #4B5563; }
        .alert-note { color: #991B1B; font-weight: bold; font-size: 12px; }
        input:focus, select:focus { border-color: #1A73E8; outline: none; box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2); }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Integrated NCD Risk Assessment Form</h2>
        <p>Official DOH Electronic Version • Patient Record Integration</p>
    </div>

    <div class="sub-header">
        <div><b>Patient:</b> {{ $patient->last_name }}, {{ $patient->first_name }}</div>
        <div><b>Patient ID:</b> {{ $patient->patient_id }}</div>
        <div><b>Assessment Date:</b> {{ date('m/d/Y') }}</div>
        @if ($ncdAssessment)
            <div><small><i>Loaded from previous assessment</i></small></div>
        @endif
    </div>

    <form action="{{ route('ncd.store', $patient->id) }}" method="POST" class="form-body">
        @csrf

        {{-- ================================================== --}}
        {{-- ✅ HEADER & PART I — EXACT DOH FORM LAYOUT --}}
        {{-- ================================================== --}}
        <table class="doh-table">
            <tr>
                <td colspan="3"><strong>Health Facility:</strong>
                    <input type="text" name="health_facility" class="paper-line" style="width:85%; margin-left:10px;"
                        value="{{ old('health_facility', $ncdAssessment?->health_facility ?? '') }}"
                        placeholder="Ilagay kung kailangan">
                </td>
                <td><strong>Date of Assessment (mm/dd/yyyy):</strong>
                    <input type="text" name="assessment_date" class="paper-line" placeholder="mm/dd/yyyy" style="width:120px;"
                        value="{{ old('assessment_date', $ncdAssessment?->assessment_date ?? date('m/d/Y')) }}">
                </td>
            </tr>
            <tr>
                <td><strong>Family No.</strong>
                    <input type="text" name="family_no" class="paper-line" readonly
                        value="{{ old('family_no', $ncdAssessment?->family_no ?? $patient->family_number ?? '') }}">
                </td>
                <td><strong>Unang Pangalan:</strong>
                    <input type="text" name="first_name" class="paper-line" readonly
                        value="{{ old('first_name', $ncdAssessment?->first_name ?? $patient->first_name ?? '') }}">
                </td>
                <td><strong>Gitnang Pangalan:</strong>
                    <input type="text" name="middle_name" class="paper-line" readonly
                        value="{{ old('middle_name', $ncdAssessment?->middle_name ?? $patient->middle_initial ?? '') }}">
                </td>
                <td><strong>Apyelido:</strong>
                    <input type="text" name="last_name" class="paper-line" readonly
                        value="{{ old('last_name', $ncdAssessment?->last_name ?? $patient->last_name ?? '') }}">
                </td>
            </tr>
            <tr>
                <td><strong>ID No.</strong>
                    <input type="text" name="id_no" class="paper-line" readonly
                        value="{{ old('id_no', $ncdAssessment?->id_no ?? $patient->patient_id ?? '') }}">
                </td>
                <td colspan="3"></td>
            </tr>
        </table>

        <div class="section-title" style="margin-top:25px;">Part I. Demographic-Socio-Economic Profile</div>

        <table class="doh-table" style="border-top:none; width:100%; table-layout:fixed;">
    <tr>
        <td style="width:30%;"><strong>Address:</strong>
            <input type="text" name="address" class="paper-line" readonly
                value="{{ old('address', $ncdAssessment?->address ?? $patient->address ?? '') }}">
        </td>
        <td style="width:22%;"><strong>Barangay:</strong>
            <input type="text" name="barangay" class="paper-line" readonly
                value="{{ old('barangay', $ncdAssessment?->barangay ?? $patient->barangay ?? '') }}">
        </td>
        <td style="width:23%;"><strong>Telepono:</strong>
            <input type="text" name="telepono" class="paper-line" readonly
                value="{{ old('telepono', $ncdAssessment?->telepono ?? $patient->contact_number ?? '') }}">
        </td>
        <td style="width:25%;"><strong>Estatus ng Sibil:</strong>
            <input type="text" name="estadocivil" class="paper-line" readonly
                value="{{ old('estadocivil', $ncdAssessment?->estadocivil ?? $patient->civil_status ?? '') }}">
        </td>
    </tr>
    <tr>
        <td style="width:30%;"><strong>Birthday (mm/dd/yyyy):</strong>
            <input type="text" name="birthday" class="paper-line" readonly
                value="{{ old('birthday', $ncdAssessment?->birthday ?? optional($patient->dob)->format('m/d/Y')) }}">
        </td>
        <td style="width:22%;"><strong>Edad:</strong>
            <input type="text" name="edad" class="paper-line" readonly
                value="{{ old('edad', $ncdAssessment?->edad ?? $patient->age ?? '') }}">
        </td>
        <td style="width:23%;"><strong>Kasarian:</strong>
            <input type="text" name="kasarian" class="paper-line" readonly
                value="{{ old('kasarian', $ncdAssessment?->kasarian ?? $patient->gender ?? '') }}">
        </td>
        <td style="width:25%;"><!-- empty cell for balanced layout --></td>
    </tr>
    <tr>
        <td colspan="2" style="width:52%;"><strong>Relihiyon:</strong>
            <input type="text" name="relihiyon" class="paper-line" readonly
                value="{{ old('relihiyon', $ncdAssessment?->relihiyon ?? $patient->religion ?? '') }}">
        </td>
        <td colspan="2" style="width:48%;"><strong>Antas ng Pag-aaral / Educational Attainment:</strong>
            <input type="text" name="educational_attainment" class="paper-line" readonly
                value="{{ old('educational_attainment', $ncdAssessment?->educational_attainment ?? $patient->educational_attainment ?? '') }}">
        </td>
    </tr>
</table>

        {{-- ================================================== --}}
{{-- PART II: PAST MEDICAL HISTORY --}}
{{-- ================================================== --}}
<div class="section-title" style="margin-top: 0;">Part II. Past Medical History</div>
<table class="doh-table" style="width:100%; table-layout:fixed;">
    <tr>
        <th style="width: 28%;">1. Karamdaman (Conditions)</th>
        <th style="width: 12%;">1.2 Lagyan ng tsek "/" kung may karamdaman</th>
        <th style="width: 20%;">1.3 Taon Nalaman</th>
        <th style="width: 25%;">1.4 Ininom na Gamot</th>
        <th style="width: 15%;">Risk Status Indicator</th>
    </tr>

    {{-- Diabetes --}}
    <tr>
        <td>
            <label class="check-group">
                <input type="checkbox" name="is_diabetic" value="Yes"
                    {{ old('is_diabetic', $ncdAssessment?->is_diabetic) ? 'checked' : '' }}>
                Diabetes
            </label>
        </td>
        <td style="border:1px solid #ccc; padding:2px;">
    <input type="text" name="check_mark_diabetic" class="paper-line" style="width:100%; border:none; text-align:center;" maxlength="1">
</td>
        <td><input type="text" name="is_diabetic_year" class="paper-line" placeholder="YYYY"
            value="{{ old('is_diabetic_year', $ncdAssessment?->is_diabetic_year) }}"></td>
        <td><input type="text" name="is_diabetic_meds" class="paper-line" placeholder="Gamot"
            value="{{ old('is_diabetic_meds', $ncdAssessment?->is_diabetic_meds) }}"></td>
        <td class="risk-area">
            <label class="check-group" style="color:#991B1B; font-weight:bold;">
                <input type="checkbox" name="risk_dm" value="Yes"
                    {{ old('risk_dm', $ncdAssessment?->risk_dm) ? 'checked' : '' }}> DM
            </label>
        </td>
    </tr>

    {{-- Hypertension --}}
    <tr>
        <td>
            <label class="check-group">
                <input type="checkbox" name="is_hypertensive" value="Yes"
                    {{ old('is_hypertensive', $ncdAssessment?->is_hypertensive) ? 'checked' : '' }}>
                Hypertension
            </label>
        </td>
<td style="border:1px solid #ccc; padding:2px;">
    <input type="text" name="check_mark_diabetic" class="paper-line" style="width:100%; border:none; text-align:center;" maxlength="1">
</td>        <td><input type="text" name="is_hypertensive_year" class="paper-line" placeholder="YYYY"
            value="{{ old('is_hypertensive_year', $ncdAssessment?->is_hypertensive_year) }}"></td>
        <td><input type="text" name="is_hypertensive_meds" class="paper-line" placeholder="Gamot"
            value="{{ old('is_hypertensive_meds', $ncdAssessment?->is_hypertensive_meds) }}"></td>
        <td class="risk-area">
            <label class="check-group" style="color:#991B1B; font-weight:bold;">
                <input type="checkbox" name="risk_hpn" value="Yes"
                    {{ old('risk_hpn', $ncdAssessment?->risk_hpn) ? 'checked' : '' }}> HPN
            </label>
        </td>
    </tr>

    {{-- COPD --}}
    <tr>
        <td>
            <label class="check-group">
                <input type="checkbox" name="has_copd" value="Yes"
                    {{ old('has_copd', $ncdAssessment?->has_copd) ? 'checked' : '' }}>
                COPD
            </label>
        </td>
<td style="border:1px solid #ccc; padding:2px;">
    <input type="text" name="check_mark_diabetic" class="paper-line" style="width:100%; border:none; text-align:center;" maxlength="1">
</td>        <td><input type="text" name="has_copd_year" class="paper-line" placeholder="YYYY"
            value="{{ old('has_copd_year', $ncdAssessment?->has_copd_year) }}"></td>
        <td><input type="text" name="has_copd_meds" class="paper-line" placeholder="Gamot"
            value="{{ old('has_copd_meds', $ncdAssessment?->has_copd_meds) }}"></td>
        <td class="risk-area">
            <label class="check-group" style="color:#991B1B; font-weight:bold;">
                <input type="checkbox" name="risk_copd" value="Yes"
                    {{ old('risk_copd', $ncdAssessment?->risk_copd) ? 'checked' : '' }}> COPD
            </label>
        </td>
    </tr>

    {{-- Cancer --}}
    <tr>
        <td>
            <label class="check-group">
                <input type="checkbox" name="has_cancer" value="Yes"
                    {{ old('has_cancer', $ncdAssessment?->has_cancer) ? 'checked' : '' }}>
                Cancer
            </label>
            <input type="text" name="cancer_site_condition" placeholder="Site/Klase..."
                style="width:85%; margin-left:20px; font-size:12px; margin-top:4px;" class="paper-line"
                value="{{ old('cancer_site_condition', $ncdAssessment?->cancer_site_condition) }}">
        </td>
<td style="border:1px solid #ccc; padding:2px;">
    <input type="text" name="check_mark_diabetic" class="paper-line" style="width:100%; border:none; text-align:center;" maxlength="1">
</td>        <td><input type="text" name="cancer_year" class="paper-line" placeholder="YYYY"
            value="{{ old('cancer_year', $ncdAssessment?->cancer_year) }}"></td>
        <td><input type="text" name="cancer_meds" class="paper-line" placeholder="Gamot"
            value="{{ old('cancer_meds', $ncdAssessment?->cancer_meds) }}"></td>
        <td class="risk-area">
            <label class="check-group" style="color:#991B1B; font-weight:bold;">
                <input type="checkbox" name="risk_cancer" value="Yes"
                    {{ old('risk_cancer', $ncdAssessment?->risk_cancer) ? 'checked' : '' }}> Cancer
            </label>
        </td>
    </tr>

    {{-- Eye Disease --}}
    <tr>
        <td>
            <label class="check-group">
                <input type="checkbox" name="has_eye_disease" value="Yes"
                    {{ old('has_eye_disease', $ncdAssessment?->has_eye_disease) ? 'checked' : '' }}>
                Sakit sa mata
            </label>
        </td>
<td style="border:1px solid #ccc; padding:2px;">
    <input type="text" name="check_mark_diabetic" class="paper-line" style="width:100%; border:none; text-align:center;" maxlength="1">
</td>        <td><input type="text" name="eye_year" class="paper-line" placeholder="YYYY"
            value="{{ old('eye_year', $ncdAssessment?->eye_year) }}"></td>
        <td><input type="text" name="eye_meds" class="paper-line" placeholder="Gamot"
            value="{{ old('eye_meds', $ncdAssessment?->eye_meds) }}"></td>
        <td></td>
    </tr>
</table>


{{-- ================================================== --}}
{{-- CHEST PAIN / ANGINA --}}
{{-- ================================================== --}}
<table class="doh-table">
    <tr style="background:#F3F4F6;">
        <th>2. Pananakit ng dibdib (Chest Pain / Angina)</th>
        <th class="col-yes-no">Oo</th>
        <th class="col-yes-no">Hindi</th>
    </tr>
    @for ($i = 1; $i <= 8; $i++)
        @php $field = "cp$i"; @endphp
        <tr>
            <td>
                @if ($i === 1) 2.1 Nakakaramdam ka ba ng pananakit o kabigatan sa dibdib? (Kung Hindi, Go to Q2.8)
                @elseif ($i === 2) 2.2 Ang sakit ba ay nasa gitna, kaliwang bahagi hanggang kaliwang braso?
                @elseif ($i === 3) 2.3 Nararamdaman mo ba ito kung nagmamadali o naglalakad ng mabilis?
                @elseif ($i === 4) 2.4 Napapatigil ka ba sa paglalakad kapag sumasakit ang iyong dibdib?
                @elseif ($i === 5) 2.5 Nawawala ba ang sakit kapag hindi ka kumikilos o nag-nitroglycerin?
                @elseif ($i === 6) 2.6 Nawawala ba ang sakit sa loob ng 10 minuto?
                @elseif ($i === 7) 2.7 Sakit sa dibdib na tumatagal higit sa 30 minuto?
                @elseif ($i === 8) 2.8 Hirap sa pagsasalita, panghihina ng braso/binti, o pamamanhid sa kalahating bahagi?
                @endif
            </td>
            <td class="col-yes-no"><input type="radio" name="cp{{ $i }}" value="Yes" {{ old($field, $ncdAssessment?->$field) === 'Yes' ? 'checked' : '' }}></td>
            <td class="col-yes-no"><input type="radio" name="cp{{ $i }}" value="No" {{ old($field, $ncdAssessment?->$field) === 'No' ? 'checked' : '' }}></td>
        </tr>
    @endfor
    <tr style="background:#FEE2E2;">
        <td colspan="3" class="alert-note">⚠️ Kung Oo sa 2.4–2.7, dalhin agad sa doktor (Impending Heart Attack).</td>
    </tr>
</table>

        {{-- ================================================== --}}
        {{-- PART III: RISK FACTORS — NUTRITION --}}
        {{-- ================================================== --}}
        <div class="section-title">Part III. Assessment of Risk Factors</div>

        <table class="doh-table">
            <tr style="background:#E5E7EB;"><th colspan="4">B.1 Nutrisyon</th></tr>
            <tr>
                <td colspan="2"><b>1. Madalas mo bang kainin ang mga sumusunod kada araw?</b></td>
                <th class="col-yes-no">Oo</th>
                <th class="col-yes-no">Hindi</th>
            </tr>
            @foreach(['Gulay','Prutas','Isda','Karne','Processed food'] as $food)
                @php $key = 'diet_' . strtolower(str_replace(' ', '_', $food)); @endphp
            <tr>
                <td colspan="2">{{ $food }}</td>
                <td class="col-yes-no"><input type="radio" name="{{ $key }}" value="Yes" {{ old($key, $ncdAssessment?->$key) === 'Yes' ? 'checked' : '' }}></td>
                <td class="col-yes-no"><input type="radio" name="{{ $key }}" value="No" {{ old($key, $ncdAssessment?->$key) === 'No' ? 'checked' : '' }}></td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2"><b>2. Kumakain ka ba ng >2x kada linggo ng:</b></td>
                <th class="col-yes-no">Oo</th>
                <th class="col-yes-no">Hindi</th>
            </tr>
            @foreach(['Maalat na pagkain','Matatamis na pagkain','Mamantika na pagkain'] as $label)
                @php $key = 'diet_' . strtolower(explode(' ', $label)[0]); @endphp
            <tr>
                <td colspan="2">{{ $label }}</td>
                <td class="col-yes-no"><input type="radio" name="{{ $key }}" value="Yes" {{ old($key, $ncdAssessment?->$key) === 'Yes' ? 'checked' : '' }}></td>
                <td class="col-yes-no"><input type="radio" name="{{ $key }}" value="No" {{ old($key, $ncdAssessment?->$key) === 'No' ? 'checked' : '' }}></td>
            </tr>
            @endforeach
            <tr style="background:#FDF2F2;">
                <td colspan="2" style="font-weight:bold;">RISK STATUS — Nutrisyon</td>
                <td colspan="2">
                    <label class="check-group" style="color:#991B1B; font-weight:bold; display:inline-block; margin-right:15px;">
                        <input type="checkbox" name="r_diet" value="Yes"
                            {{ old('r_diet', $ncdAssessment?->r_diet) ? 'checked' : '' }}> Unhealthy Diet
                    </label>
                    <label class="check-group" style="color:#991B1B; font-weight:bold; display:inline-block;">
                        <input type="checkbox" name="r_salt" value="Yes"
                            {{ old('r_salt', $ncdAssessment?->r_salt) ? 'checked' : '' }}> High Salt Intake
                    </label>
                </td>
            </tr>
        </table>

        {{-- ================================================== --}}
        {{-- ALCOHOL SECTION --}}
        {{-- ================================================== --}}
        <div class="sub-section-title">B.2 Alcohol</div>
        <table class="doh-table">
            <tr>
                <th style="width:40%">Tanong</th>
                <th class="col-yes-no">Oo</th>
                <th class="col-yes-no">Hindi</th>
            </tr>
            <tr>
                <td><b>1. Umiinom ka ba ng alak?</b></td>
                <td class="col-yes-no"><input type="radio" name="alc_u" value="Oo" {{ old('alc_u', $ncdAssessment?->alc_u) === 'Oo' ? 'checked' : '' }}></td>
                <td class="col-yes-no"><input type="radio" name="alc_u" value="Hindi" {{ old('alc_u', $ncdAssessment?->alc_u) === 'Hindi' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td><b>Kung HINDI: Tumigil ka na ba ng higit sa 1 taon?</b></td>
                <td class="col-yes-no"><input type="radio" name="alc_q" value=">=1" {{ old('alc_q', $ncdAssessment?->alc_q) === '>=1' ? 'checked' : '' }}></td>
                <td class="col-yes-no"><input type="radio" name="alc_q" value="<1" {{ old('alc_q', $ncdAssessment?->alc_q) === '<1' ? 'checked' : '' }}></td>
            </tr>
        </table>

        <table class="doh-table" style="margin-top:15px;">
            <tr>
                <td colspan="3"><b>2. Anong klase ng alak?</b> (Pwede marami)</td>
            </tr>
            <tr>
                <td>
                    @php $alcT = old('alc_t', $ncdAssessment?->alc_t ?? []); @endphp
                    <label style="margin-right:15px;"><input type="checkbox" name="alc_t[]" value="Beer" {{ in_array('Beer', $alcT) ? 'checked' : '' }}> Beer</label>
                    <label style="margin-right:15px;"><input type="checkbox" name="alc_t[]" value="Wine" {{ in_array('Wine', $alcT) ? 'checked' : '' }}> Wine</label>
                    <label><input type="checkbox" name="alc_t[]" value="Gin/Whisky" {{ in_array('Gin/Whisky', $alcT) ? 'checked' : '' }}> Gin/Whisky</label>
                </td>
            </tr>
            <tr>
                <td><b>3. Gaano karami sa isang araw?</b></td>
                <td><b>4. Gaano kadalas sa isang linggo?</b></td>
                <td><b>5. Binge drinking (5+ bote/araw)?</b></td>
            </tr>
            <tr>
                <td>
                    Beer: <select name="amt_b">
                        <option value="">- Pili -</option>
                        @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('amt_b', $ncdAssessment?->amt_b) == $i ? 'selected' : '' }}>{{ $i }} bote</option>
                        @endfor
                    </select><br>
                    Wine: <select name="amt_w">
                        <option value="">- Pili -</option>
                        @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('amt_w', $ncdAssessment?->amt_w) == $i ? 'selected' : '' }}>{{ $i }} baso</option>
                        @endfor
                    </select><br>
                    Shots: <select name="amt_s">
                        <option value="">- Pili -</option>
                        @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('amt_s', $ncdAssessment?->amt_s) == $i ? 'selected' : '' }}>{{ $i }} shot</option>
                        @endfor
                    </select>
                </td>
                <td>
                    <label><input type="radio" name="alc_f" value="1-3" {{ old('alc_f', $ncdAssessment?->alc_f) === '1-3' ? 'checked' : '' }}> 1–3 beses</label><br>
                    <label><input type="radio" name="alc_f" value="4-6" {{ old('alc_f', $ncdAssessment?->alc_f) === '4-6' ? 'checked' : '' }}> 4–6 beses</label><br>
                    <label><input type="radio" name="alc_f" value="araw-araw" {{ old('alc_f', $ncdAssessment?->alc_f) === 'araw-araw' ? 'checked' : '' }}> Araw-araw</label>
                </td>
                <td class="risk-area">
                    <label class="check-group" style="color:#991B1B; font-weight:bold;">
                        <input type="checkbox" name="r_binge" value="Yes"
                            {{ old('r_binge', $ncdAssessment?->r_binge) ? 'checked' : '' }}> Oo — Binge Drinker
                    </label>
                </td>
            </tr>
        </table>

        {{-- ================================================== --}}
        {{-- PART IV: RISK SCREENING --}}
        {{-- ================================================== --}}
        <div class="section-title">Part IV. Risk Screening</div>
        <table class="doh-table" style="table-layout: fixed;">
            <thead>
                <tr><th style="width:20%">Assessment</th><th style="width:35%">Measurements</th><th style="width:25%">Status</th><th style="width:20%">Risk Status</th></tr>
            </thead>
            <tbody>
                {{-- Anthropometric / BMI --}}
                <tr>
                    <td rowspan="2"><b>4.1 Anthropometric</b><br><small>Formula: BMI = Wt ÷ (Ht in m)²</small></td>
                    <td>
                        <div style="display:flex; gap:8px; margin-bottom:8px;">
                            <div style="flex:1">
                                <label style="font-size:10px; font-weight:bold;">Timbang (kg)</label>
                                <input type="number" step="0.1" name="w" style="width:95%;" placeholder="kg"
                                    value="{{ old('w', $ncdAssessment?->w) }}">
                            </div>
                            <div style="flex:1">
                                <label style="font-size:10px; font-weight:bold;">Taas (cm)</label>
                                <input type="number" step="0.1" name="h" style="width:95%;" placeholder="cm"
                                    value="{{ old('h', $ncdAssessment?->h) }}">
                            </div>
                        </div>
                        <label style="font-size:10px; font-weight:bold;">BMI Result (Auto)</label>
                        <input type="text" name="bmi" style="width:95%; font-weight:bold; background:#F9FAFB;" readonly
                            value="{{ old('bmi', $ncdAssessment?->bmi) }}">
                    </td>
                    <td>
                        <label style="font-size:11px;"><input type="radio" name="bmi_s" value="U" {{ old('bmi_s', $ncdAssessment?->bmi_s) === 'U' ? 'checked' : '' }}> Kulang sa timbang (&lt;18.5)</label><br>
                        <label style="font-size:11px;"><input type="radio" name="bmi_s" value="N" {{ old('bmi_s', $ncdAssessment?->bmi_s) === 'N' ? 'checked' : '' }}> Normal (18.5–22.9)</label><br>
                        <label style="font-size:11px;"><input type="radio" name="bmi_s" value="O" {{ old('bmi_s', $ncdAssessment?->bmi_s) === 'O' ? 'checked' : '' }}> Sobra sa timbang (23–24.9)</label><br>
                        <label style="font-size:11px;"><input type="radio" name="bmi_s" value="B" {{ old('bmi_s', $ncdAssessment?->bmi_s) === 'B' ? 'checked' : '' }}> Obese (&ge;25)</label>
                    </td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_over" value="Y" {{ old('r_over', $ncdAssessment?->r_over) ? 'checked' : '' }}> Overweight
                        </label>
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_obese" value="Y" {{ old('r_obese', $ncdAssessment?->r_obese) ? 'checked' : '' }}> Obese
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="display:flex; gap:8px; margin-bottom:8px;">
                            <div style="flex:1">
                                <label style="font-size:10px; font-weight:bold;">Baywang (cm)</label>
                                <input type="number" step="0.1" name="waist" style="width:95%;"
                                    value="{{ old('waist', $ncdAssessment?->waist) }}">
                            </div>
                            <div style="flex:1">
                                <label style="font-size:10px; font-weight:bold;">Balakang (cm)</label>
                                <input type="number" step="0.1" name="hip" style="width:95%;"
                                    value="{{ old('hip', $ncdAssessment?->hip) }}">
                            </div>
                        </div>
                        <label style="font-size:10px; font-weight:bold;">Waist-Hip Ratio (Auto)</label>
                        <input type="text" name="whr" style="width:95%; font-weight:bold; background:#F9FAFB;" readonly
                            value="{{ old('whr', $ncdAssessment?->whr) }}">
                    </td>
                    <td>
                        <label style="font-size:11px;"><input type="radio" name="whr_s" value="N" {{ old('whr_s', $ncdAssessment?->whr_s) === 'N' ? 'checked' : '' }}> Walang Panganib</label><br>
                        <label style="font-size:11px;"><input type="radio" name="whr_s" value="R" {{ old('whr_s', $ncdAssessment?->whr_s) === 'R' ? 'checked' : '' }}> May Panganib</label>
                    </td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_whr" value="Y" {{ old('r_whr', $ncdAssessment?->r_whr) ? 'checked' : '' }}> At Risk
                        </label>
                    </td>
                </tr>

                {{-- Blood Sugar --}}
                <tr>
                    <td rowspan="2"><b>4.2 Blood Sugar</b></td>
                    <td>
                        <small>Fasting (CBG):</small>
                        <input type="number" step="0.01" name="fbs" style="width:95%;" placeholder="mg/dL"
                            value="{{ old('fbs', $ncdAssessment?->fbs) }}"><br>
                        <small>Venous Exam:</small>
                        <input type="number" step="0.01" name="vn" style="width:95%;" placeholder="mg/dL"
                            value="{{ old('vn', $ncdAssessment?->vn) }}">
                    </td>
                    <td>
                        <label style="font-size:11px;"><input type="radio" name="fbs_s" value="N" {{ old('fbs_s', $ncdAssessment?->fbs_s) === 'N' ? 'checked' : '' }}> Normal</label><br>
                        <label style="font-size:11px;"><input type="radio" name="fbs_s" value="I" {{ old('fbs_s', $ncdAssessment?->fbs_s) === 'I' ? 'checked' : '' }}> Impaired</label><br>
                        <label style="font-size:11px;"><input type="radio" name="fbs_s" value="D" {{ old('fbs_s', $ncdAssessment?->fbs_s) === 'D' ? 'checked' : '' }}> Diabetes</label>
                    </td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_predm" value="Y" {{ old('r_predm', $ncdAssessment?->r_predm) ? 'checked' : '' }}> Pre-Diabetes
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <small>Random Blood Sugar:</small><br>
                        <label style="font-size:11px;"><input type="radio" name="rbs_s" value="N" {{ old('rbs_s', $ncdAssessment?->rbs_s) === 'N' ? 'checked' : '' }}> Normal (&lt;140)</label><br>
                        <label style="font-size:11px;"><input type="radio" name="rbs_s" value="A" {{ old('rbs_s', $ncdAssessment?->rbs_s) === 'A' ? 'checked' : '' }}> Abnormal (&ge;200)</label>
                    </td>
                    <td>
                        <small>Classic Symptoms:</small><br>
                        <label style="font-size:10px;"><input type="checkbox" name="s_pol" value="Y" {{ old('s_pol', $ncdAssessment?->s_pol) ? 'checked' : '' }}> Madalas umihi</label><br>
                        <label style="font-size:10px;"><input type="checkbox" name="s_wgt" value="Y" {{ old('s_wgt', $ncdAssessment?->s_wgt) ? 'checked' : '' }}> Biglang pumayat</label>
                    </td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_dm_f" value="Y" {{ old('r_dm_f', $ncdAssessment?->r_dm_f) ? 'checked' : '' }}> Diabetes
                        </label>
                    </td>
                </tr>

                {{-- Blood Pressure --}}
                <tr>
                    <td><b>4.3 Blood Pressure</b></td>
                    <td>
                        <div style="display:flex; gap:4px;">
                            <input type="text" name="bp_l" placeholder="Kaliwa" style="width:30%"
                                value="{{ old('bp_l', $ncdAssessment?->bp_l) }}">
                            <input type="text" name="bp_r" placeholder="Kanan" style="width:30%"
                                value="{{ old('bp_r', $ncdAssessment?->bp_r) }}">
                            <input type="text" name="bp_b" placeholder="Base" style="width:35%; border-bottom:2px solid red;"
                                value="{{ old('bp_b', $ncdAssessment?->bp_b) }}">
                        </div>
                    </td>
                    <td>
                        <label style="font-size:10px;"><input type="radio" name="bp_s" value="N" {{ old('bp_s', $ncdAssessment?->bp_s) === 'N' ? 'checked' : '' }}> Normal</label><br>
                        <label style="font-size:10px;"><input type="radio" name="bp_s" value="P" {{ old('bp_s', $ncdAssessment?->bp_s) === 'P' ? 'checked' : '' }}> Pre-HPN</label><br>
                        <label style="font-size:10px;"><input type="radio" name="bp_s" value="H" {{ old('bp_s', $ncdAssessment?->bp_s) === 'H' ? 'checked' : '' }}> HPN</label>
                    </td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_hpn_f" value="Y" {{ old('r_hpn_f', $ncdAssessment?->r_hpn_f) ? 'checked' : '' }}> Hypertension
                        </label>
                    </td>
                </tr>

                {{-- Cholesterol --}}
                <tr>
                    <td><b>4.4 Cholesterol</b></td>
                    <td>
                        <input type="number" step="0.01" name="chol" style="width:95%;" placeholder="mg/dL"
                            value="{{ old('chol', $ncdAssessment?->chol) }}">
                    </td>
                    <td>
                        <label style="font-size:11px;"><input type="radio" name="ch_s" value="N" {{ old('ch_s', $ncdAssessment?->ch_s) === 'N' ? 'checked' : '' }}> Normal</label><br>
                        <label style="font-size:11px;"><input type="radio" name="ch_s" value="R" {{ old('ch_s', $ncdAssessment?->ch_s) === 'R' ? 'checked' : '' }}> Mataas</label>
                    </td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_chol" value="Y" {{ old('r_chol', $ncdAssessment?->r_chol) ? 'checked' : '' }}> Elevated
                        </label>
                    </td>
                </tr>

                {{-- Urine Dipstick --}}
                <tr>
                    <td><b>4.5 Urine Dipstick</b></td>
                    <td>
                        Protein:
                        <input type="radio" name="pro" value="+" {{ old('pro', $ncdAssessment?->pro) === '+' ? 'checked' : '' }}> +
                        <input type="radio" name="pro" value="-" {{ old('pro', $ncdAssessment?->pro) === '-' ? 'checked' : '' }}> −<br>
                        Ketones:
                        <input type="radio" name="ket" value="+" {{ old('ket', $ncdAssessment?->ket) === '+' ? 'checked' : '' }}> +
                        <input type="radio" name="ket" value="-" {{ old('ket', $ncdAssessment?->ket) === '-' ? 'checked' : '' }}> −
                    </td>
                    <td><small>Resulta</small></td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_pro" value="Y" {{ old('r_pro', $ncdAssessment?->r_pro) ? 'checked' : '' }}> (+) Protein
                        </label>
                    </td>
                </tr>

                {{-- Risk Profile --}}
                <tr style="background:#F3F4F6;">
                    <td><b>4.6 Risk Profile</b><br><small>(Doctor Only)</small></td>
                    <td colspan="2">
                        <div class="radio-inline" style="justify-content:space-between; flex-wrap:wrap;">
                            <label><input type="radio" name="rp" value="5" {{ old('rp', $ncdAssessment?->rp) === '5' ? 'checked' : '' }}> &lt;5%</label>
                            <label><input type="radio" name="rp" value="10" {{ old('rp', $ncdAssessment?->rp) === '10' ? 'checked' : '' }}> 5–10%</label>
                            <label><input type="radio" name="rp" value="20" {{ old('rp', $ncdAssessment?->rp) === '20' ? 'checked' : '' }}> 10–20%</label>
                            <label><input type="radio" name="rp" value="30" {{ old('rp', $ncdAssessment?->rp) === '30' ? 'checked' : '' }}> 20–30%</label>
                            <label><input type="radio" name="rp" value="31" {{ old('rp', $ncdAssessment?->rp) === '31' ? 'checked' : '' }}> &ge;30%</label>
                        </div>
                    </td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;">
                            <input type="checkbox" name="r_30" value="Y" {{ old('r_30', $ncdAssessment?->r_30) ? 'checked' : '' }}> &ge;30% Risk
                        </label>
                    </td>
                </tr>

                {{-- Cancer Screening --}}
                <tr>
                    <td><b>4.7 Cancer Screening</b><br><small>(Babae, 30+ taon)</small></td>
                    <td colspan="2">
                        <small>Nai-screen na ba sa Breast/Cervical Cancer?</small>
                        <div class="radio-inline">
                            <label><input type="radio" name="cs" value="Y" {{ old('cs', $ncdAssessment?->cs) === 'Y' ? 'checked' : '' }}> Oo (paalalahan)</label>
                            <label><input type="radio" name="cs" value="N" {{ old('cs', $ncdAssessment?->cs) === 'N' ? 'checked' : '' }}> Hindi (i-refer)</label>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        {{-- SAVE / CANCEL --}}
        <div class="footer">
            <a href="{{ url()->previous() }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">Finalize DOH Assessment</button>
        </div>
    </form>
</div>

{{-- Auto-Calculate BMI & WHR --}}
<script>
    const wInput = document.querySelector('input[name="w"]');
    const hInput = document.querySelector('input[name="h"]');
    const bmiInput = document.querySelector('input[name="bmi"]');
    const waistInput = document.querySelector('input[name="waist"]');
    const hipInput = document.querySelector('input[name="hip"]');
    const whrInput = document.querySelector('input[name="whr"]');

    function calcBMI() {
        const w = parseFloat(wInput?.value);
        const hCm = parseFloat(hInput?.value);
        if (w && hCm && hCm > 0) {
            const hM = hCm / 100;
            const bmi = w / (hM * hM);
            bmiInput.value = bmi.toFixed(1);
        } else {
            bmiInput.value = '';
        }
    }

    function calcWHR() {
        const waist = parseFloat(waistInput?.value);
        const hip = parseFloat(hipInput?.value);
        if (waist && hip && hip > 0) {
            whrInput.value = (waist / hip).toFixed(3);
        } else {
            whrInput.value = '';
        }
    }

    wInput?.addEventListener('input', calcBMI);
    hInput?.addEventListener('input', calcBMI);
    waistInput?.addEventListener('input', calcWHR);
    hipInput?.addEventListener('input', calcWHR);
</script>

</body>
</html>
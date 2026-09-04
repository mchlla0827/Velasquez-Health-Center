<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integrated NCD Risk Assessment - {{ $patient->first_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-soft: #eff6ff;
            --primary-line: #dbeafe;
            --surface: #ffffff;
            --bg: #f1f5f9;
            --line: #e8edf3;
            --line-strong: #dbe3ec;
            --text: #0f172a;
            --text-muted: #64748b;
            --risk-bg: #fff7f7;
            --risk-text: #b91c1c;
            --risk-line: #fecaca;
            --band: #f8fafc;
            --radius: 12px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0; padding: 0;
            background: var(--bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text);
            font-size: 13.5px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .paper-container {
            max-width: 1080px;
            margin: 28px auto 120px;
            padding: 0 20px;
        }

        /* App-style header */
        .doh-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #fff;
            border-radius: var(--radius);
            padding: 26px 30px;
            margin-bottom: 22px;
            box-shadow: 0 6px 20px rgba(37,99,235,0.22);
        }
        .doh-header h4 {
            margin: 0; font-size: 11.5px; font-weight: 600; color: rgba(255,255,255,0.82);
            text-transform: uppercase; letter-spacing: 1px;
        }
        .doh-header h3 {
            margin: 4px 0 0; font-size: 16px; font-weight: 700; color: #fff;
            text-transform: uppercase; letter-spacing: 0.8px;
        }
        .doh-header h5 { margin: 3px 0 0; font-size: 12px; font-weight: 400; color: rgba(255,255,255,0.78); }
        .form-title {
            margin-top: 16px; padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.22);
            font-size: 21px; font-weight: 700; letter-spacing: 0.2px; color: #fff;
        }

        /* Section cards */
        table.doc-table {
            width: 100%; border-collapse: separate; border-spacing: 0;
            margin-bottom: 18px; table-layout: fixed; background: var(--surface);
            border: 1px solid var(--line-strong);
            border-radius: var(--radius); overflow: hidden;
            box-shadow: 0 1px 2px rgba(15,23,42,0.04), 0 4px 14px rgba(15,23,42,0.05);
        }
        table.doc-table th, table.doc-table td {
            border-right: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            padding: 12px 14px; vertical-align: top; font-size: 13px;
        }
        table.doc-table tr > *:last-child { border-right: none; }
        table.doc-table tr:last-child > * { border-bottom: none; }
        table.doc-table th { background-color: var(--band); font-weight: 600; text-align: left; }

        .section-banner {
            background: #0f172a !important;
            color: #fff !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            padding: 13px 16px !important;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            border-right: none !important;
        }
        .subsection-banner {
            background: var(--primary-soft) !important;
            color: var(--primary-dark) !important;
            font-weight: 700 !important;
            font-size: 12.5px !important;
            letter-spacing: 0.3px;
            border-bottom: 1px solid var(--primary-line) !important;
        }

        /* Field labels inside cells */
        .cell-label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        input[type="text"], input[type="number"], select {
            width: 100%;
            border: 1px solid var(--line-strong);
            border-radius: 8px;
            padding: 9px 11px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text);
            background: #fbfcfe;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        input[type="text"]:hover, input[type="number"]:hover, select:hover { border-color: #c7d2e0; }
        input[type="text"]:focus, input[type="number"]:focus, select:focus {
            border-color: var(--primary); background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.13);
        }
        input[readonly] {
            background: #f1f5f9 !important; color: var(--text-muted) !important;
            cursor: not-allowed; border-style: dashed;
        }
        select { appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 10px center; background-size: 15px;
            padding-right: 32px;
        }

        /* Choice chips */
        .inline-choice {
            display: inline-flex; align-items: center; gap: 7px; cursor: pointer;
            margin: 3px 10px 3px 0; padding: 6px 12px 6px 10px;
            font-weight: 500; font-size: 12.5px; user-select: none;
            background: #f6f8fb; border: 1px solid var(--line-strong);
            border-radius: 999px; transition: all .13s;
        }
        .inline-choice:hover { background: var(--primary-soft); border-color: var(--primary-line); }
        .inline-choice input[type="radio"], .inline-choice input[type="checkbox"] {
            width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer; margin: 0;
        }
        .inline-choice:has(input:checked) {
            background: var(--primary-soft); border-color: var(--primary);
            color: var(--primary-dark); font-weight: 600;
        }
        td[style*="text-align:center"] input[type="radio"],
        td[style*="text-align: center"] input[type="radio"] {
            width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; margin: 4px 0;
        }
                /* Uniform choice grid - equal-size boxes */
        .choice-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 14px;
        }
        .choice-grid .inline-choice {
            width: 100%;
            margin: 0;
            min-height: 46px;
            padding: 8px 14px;
            line-height: 1.3;
        }
        .choice-grid .inline-choice.wide {
            grid-column: span 2;
            white-space: nowrap;
        }
                /* Plain grouped choices - no pill background */
        .choice-row {
            display: flex; gap: 26px; flex-wrap: wrap; align-items: center;
        }
        .plain-choice {
            display: inline-flex; align-items: center; gap: 8px; cursor: pointer;
            font-size: 12.5px; font-weight: 500; user-select: none;
            background: none; border: none; padding: 0; margin: 0;
        }
        .plain-choice input[type="checkbox"], .plain-choice input[type="radio"] {
            width: 16px; height: 16px; accent-color: var(--primary); cursor: pointer; margin: 0;
        }
        .plain-choice:has(input:checked) { color: var(--primary-dark); font-weight: 600; }
                /* B.4 smoking rows */
        .smoke-line {
            display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
            padding: 7px 0;
        }
        .smoke-line + .smoke-line { border-top: 1px solid var(--line); }
        .smoke-sub { padding-left: 24px; }
        .smoke-sub > span { color: black; }

        /* Question with its control on the same line */
        .inline-question {
            display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-top: 14px;
        }
        .inline-question > span { flex-shrink: 0; }
        .inline-question > select { flex: 1; min-width: 240px; max-width: 340px; margin: 0; }
        .other-field {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        }
        .other-field > span { white-space: nowrap; font-weight: 600; }
        .other-field > input { flex: 1; min-width: 220px; }

        @media (max-width: 700px) {
            .choice-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* Risk status column */
        .criteria-box {
            font-size: 11.5px; color: var(--text-muted); line-height: 1.5;
            background: var(--risk-bg);
            border-left: 3px solid var(--risk-line) !important;
        }
        .criteria-box .inline-choice { background: #fff; border-color: var(--risk-line); border-radius: 6px; }
        .criteria-box .inline-choice:has(input:checked) {
            background: #fee2e2; border-color: #f87171; color: var(--risk-text);
        }
        .criteria-note { font-style: italic; color: #94a3b8; }
        .risk-badge { font-weight: 700; color: var(--risk-text); }

        .alert-callout {
            background-color: #fef2f2 !important;
            border-left: 3px solid #ef4444 !important;
            color: var(--risk-text);
            padding: 12px 16px !important;
            font-weight: 600; font-size: 12.5px;
        }

        /* Sticky action bar */
        .footer-bar-wrap {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: rgba(255,255,255,0.94); backdrop-filter: blur(10px);
            border-top: 1px solid var(--line-strong);
            box-shadow: 0 -4px 20px rgba(15,23,42,0.07);
            z-index: 100;
        }
        .footer-bar {
            max-width: 1080px; margin: 0 auto; padding: 14px 24px;
            display: flex; justify-content: flex-end; align-items: center; gap: 12px;
        }
        .btn-save {
            padding: 11px 30px; background: var(--primary); border: none; border-radius: 9px;
            color: white; font-weight: 600; font-size: 13.5px; cursor: pointer;
            box-shadow: 0 2px 8px rgba(37,99,235,0.3); transition: all .15s;
        }
        .btn-save:hover { background: var(--primary-dark); box-shadow: 0 4px 12px rgba(37,99,235,0.36); }
        .btn-cancel {
            padding: 11px 24px; background: #fff; border: 1px solid var(--line-strong);
            border-radius: 9px; color: var(--text-muted); text-decoration: none;
            font-size: 13.5px; font-weight: 600; transition: all .15s;
        }
        .btn-cancel:hover { background: var(--band); color: var(--text); }

        .alert-box { padding: 14px 18px; border-radius: 10px; font-size: 13px; margin-bottom: 18px; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-weight: 600; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-error ul { margin: 6px 0 0; padding-left: 18px; }

        @media (max-width: 860px) {
            .paper-container { padding: 0 12px; margin-top: 14px; }
            .doh-header { padding: 20px; }
            table.doc-table, table.doc-table tbody, table.doc-table tr, table.doc-table td {
                display: block; width: 100% !important;
            }
            table.doc-table td { border-right: none; }
            .criteria-box { border-left: 3px solid var(--risk-line) !important; }
            .footer-bar { padding: 12px 16px; }
            .btn-save, .btn-cancel { flex: 1; text-align: center; }
        }

        @media print {
            body { background: white; }
            .paper-container { padding: 0; margin: 0; max-width: 100%; }
            table.doc-table { box-shadow: none; }
            .doh-header { background: none !important; color: #000 !important; box-shadow: none; border-bottom: 2px solid #000; border-radius: 0; }
            .doh-header h3, .doh-header h4, .doh-header h5, .form-title { color: #000 !important; }
            .footer-bar-wrap { display: none; }
        }
    </style>
</head>
<body>

<form action="{{ route('ncd.store', $patient->id) }}" method="POST">
    @csrf

    @php
        function ynChecked($fieldName, $ncdAssessment, $value) {
            $old = old($fieldName);
            if ($old !== null) {
                return $old === $value;
            }
            if ($ncdAssessment) {
                return (bool) $ncdAssessment->$fieldName === ($value === 'Yes');
            }
            return false;
        }
    @endphp

    <div class="paper-container">

        @if (session('success'))
            <div class="alert-box alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-box alert-error">
                <b>The form could not be saved. Please fix the following:</b>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="doh-header">
            <h4>Republic of the Philippines</h4>
            <h3>DEPARTMENT OF HEALTH</h3>
            <h5>Metro Manila Center for Health Development</h5>
            <div class="form-title">Integrated NCD Risk Assessment Form</div>
        </div>

        <table class="doc-table">
            <tr>
                <td style="width: 15%; font-weight: 600;">Health Facility:</td>
                <td style="width: 35%;"><input type="text" name="health_facility" value="{{ old('health_facility', $ncdAssessment?->health_facility) }}"></td>
                <td style="width: 25%; font-weight: 600;">Date of Assessment (mm/dd/yy):</td>
                <td style="width: 25%;"><input type="text" name="assessment_date" value="{{ old('assessment_date', date('m/d/Y')) }}" placeholder="mm/dd/yyyy"></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Family No.</td>
                <td colspan="3"><input type="text" name="family_no" value="{{ old('family_no', $ncdAssessment?->family_no ?? $patient->family_number) }}"></td>
            </tr>
        </table>

        <table class="doc-table">
            <tr>
                <td style="width: 20%; font-weight: 600;">ID No.</td>
                <td style="width: 27%; font-weight: 600;">Unang Pangalan:</td>
                <td style="width: 27%; font-weight: 600;">Gitnang Pangalan:</td>
                <td style="width: 26%; font-weight: 600;">Apelyido:</td>
            </tr>
            <tr>
                <td><input type="text" name="id_no" value="{{ old('id_no', $ncdAssessment?->id_no ?? $patient->patient_id) }}" readonly></td>
                <td><input type="text" name="first_name" value="{{ old('first_name', $ncdAssessment?->first_name ?? $patient->first_name) }}"></td>
                <td><input type="text" name="middle_name" value="{{ old('middle_name', $ncdAssessment?->middle_name ?? $patient->middle_name) }}"></td>
                <td><input type="text" name="last_name" value="{{ old('last_name', $ncdAssessment?->last_name ?? $patient->last_name) }}"></td>
            </tr>
        </table>

        <table class="doc-table">
            <tr>
                <td colspan="4" class="section-banner">Part I. Demographic-Socio-Economic Profile</td>
            </tr>
            <tr>
                <td style="width: 15%; font-weight: 600;">Address:</td>
                <td style="width: 35%;"><input type="text" name="address" value="{{ old('address', $ncdAssessment?->address ?? $patient->address) }}"></td>
                <td style="width: 15%; font-weight: 600;">Barangay:</td>
                <td style="width: 35%;"><input type="text" name="barangay" value="{{ old('barangay', $ncdAssessment?->barangay ?? $patient->barangay) }}"></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Birthday (mm/dd/yy):</td>
                <td><input type="text" name="birthday" value="{{ old('birthday', $ncdAssessment?->birthday) }}" placeholder="mm/dd/yyyy"></td>
                <td style="font-weight: 600;">Telepono:</td>
                <td><input type="text" name="telepono" value="{{ old('telepono', $ncdAssessment?->telepono ?? $patient->contact_number) }}"></td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Edad:</td>
                <td><input type="number" name="edad" value="{{ old('edad', $ncdAssessment?->edad ?? $patient->age) }}"></td>
                <td style="font-weight: 600;">Kasarian:</td>
                <td>
                    <select name="kasarian">
                        <option value="">-- Select --</option>
                        <option value="Male" {{ old('kasarian', $ncdAssessment?->kasarian) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('kasarian', $ncdAssessment?->kasarian) === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Relihiyon:</td>
                <td><input type="text" name="relihiyon" value="{{ old('relihiyon', $ncdAssessment?->relihiyon) }}"></td>
                <td style="font-weight: 600;">Estadong Sibil:</td>
                <td>
                    <select name="estadocivil">
                        <option value="">-- Select --</option>
                        <option value="Single" {{ old('estadocivil', $ncdAssessment?->estadocivil) === 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ old('estadocivil', $ncdAssessment?->estadocivil) === 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Widowed" {{ old('estadocivil', $ncdAssessment?->estadocivil) === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        <option value="Separated" {{ old('estadocivil', $ncdAssessment?->estadocivil) === 'Separated' ? 'selected' : '' }}>Separated</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Hanapbuhay:</td>
                <td colspan="3"><input type="text" name="occupation" value="{{ old('occupation', $ncdAssessment?->occupation) }}"></td>
            </tr>
        </table>

        <table class="doc-table">
            <tr>
                <td colspan="4" class="section-banner">Part II. Past Medical History</td>
                <td class="section-banner" style="width: 22%;">Risk Status</td>
            </tr>
            <tr style="background: var(--band); font-weight: 700; font-size: 11.5px;">
                <td style="width: 26%;">1.1 Karamdaman</td>
                <td style="width: 18%;">1.2 Lagyan ng tsek (/) kung may karamdaman</td>
                <td style="width: 14%;">1.3 Taon Nalaman ang Sakit</td>
                <td style="width: 20%;">1.4 Iniinom na Gamot. <br><span style="font-size:10.5px; font-weight:normal;">Isulat ang "Wala" kung walang iniinom.</span></td>
                <td></td>
            </tr>
            <tr>
                <td><b>&bull; Diabetes</b></td>
                <td>
                    <label class="inline-choice"><input type="radio" name="is_diabetic" value="Yes" {{ ynChecked('is_diabetic', $ncdAssessment, 'Yes') ? 'checked' : '' }}> Oo</label>
                    <label class="inline-choice"><input type="radio" name="is_diabetic" value="No" {{ ynChecked('is_diabetic', $ncdAssessment, 'No') ? 'checked' : '' }}> Hindi</label>
                </td>
                <td><input type="text" name="is_diabetic_year" value="{{ old('is_diabetic_year', $ncdAssessment?->is_diabetic_year) }}"></td>
                <td><input type="text" name="is_diabetic_meds" value="{{ old('is_diabetic_meds', $ncdAssessment?->is_diabetic_meds) }}"></td>
                <td><label class="inline-choice"><input type="checkbox" name="risk_dm" value="Yes" {{ old('risk_dm', $ncdAssessment?->risk_dm) ? 'checked' : '' }}> <span class="risk-badge">DM</span></label></td>
            </tr>
            <tr>
                <td><b>&bull; Hypertension</b></td>
                <td>
                    <label class="inline-choice"><input type="radio" name="is_hypertensive" value="Yes" {{ ynChecked('is_hypertensive', $ncdAssessment, 'Yes') ? 'checked' : '' }}> Oo</label>
                    <label class="inline-choice"><input type="radio" name="is_hypertensive" value="No" {{ ynChecked('is_hypertensive', $ncdAssessment, 'No') ? 'checked' : '' }}> Hindi</label>
                </td>
                <td><input type="text" name="is_hypertensive_year" value="{{ old('is_hypertensive_year', $ncdAssessment?->is_hypertensive_year) }}"></td>
                <td><input type="text" name="is_hypertensive_meds" value="{{ old('is_hypertensive_meds', $ncdAssessment?->is_hypertensive_meds) }}"></td>
                <td><label class="inline-choice"><input type="checkbox" name="risk_hpn" value="Yes" {{ old('risk_hpn', $ncdAssessment?->risk_hpn) ? 'checked' : '' }}> <span class="risk-badge">HPN</span></label></td>
            </tr>
            <tr>
                <td><b>&bull; Cancer</b></td>
                <td>
                    <label class="inline-choice"><input type="radio" name="has_cancer" value="Yes" {{ ynChecked('has_cancer', $ncdAssessment, 'Yes') ? 'checked' : '' }}> Oo</label>
                    <label class="inline-choice"><input type="radio" name="has_cancer" value="No" {{ ynChecked('has_cancer', $ncdAssessment, 'No') ? 'checked' : '' }}> Hindi</label>
                    <div style="margin-top: 5px; font-size: 11px; color: var(--text-muted);">Isulat ang site/lugar ng Cancer:</div>
                    <input type="text" name="cancer_site_condition" value="{{ old('cancer_site_condition', $ncdAssessment?->cancer_site_condition) }}">
                </td>
                <td><input type="text" name="cancer_year" value="{{ old('cancer_year', $ncdAssessment?->cancer_year) }}"></td>
                <td><input type="text" name="cancer_meds" value="{{ old('cancer_meds', $ncdAssessment?->cancer_meds) }}"></td>
                <td><label class="inline-choice"><input type="checkbox" name="risk_cancer" value="Yes" {{ old('risk_cancer', $ncdAssessment?->risk_cancer) ? 'checked' : '' }}> <span class="risk-badge">Cancer, specify site</span></label></td>
            </tr>
            <tr>
                <td><b>&bull; Sakit sa baga na hindi nakakahawa</b></td>
                <td>
                    <label class="inline-choice"><input type="radio" name="has_copd" value="Yes" {{ ynChecked('has_copd', $ncdAssessment, 'Yes') ? 'checked' : '' }}> Oo</label>
                    <label class="inline-choice"><input type="radio" name="has_copd" value="No" {{ ynChecked('has_copd', $ncdAssessment, 'No') ? 'checked' : '' }}> Hindi</label>
                </td>
                <td><input type="text" name="has_copd_year" value="{{ old('has_copd_year', $ncdAssessment?->has_copd_year) }}"></td>
                <td><input type="text" name="has_copd_meds" value="{{ old('has_copd_meds', $ncdAssessment?->has_copd_meds) }}"></td>
                <td><label class="inline-choice"><input type="checkbox" name="risk_copd" value="Yes" {{ old('risk_copd', $ncdAssessment?->risk_copd) ? 'checked' : '' }}> <span class="risk-badge">COPD</span></label></td>
            </tr>
            <tr>
                <td><b>&bull; Sakit sa mata</b></td>
                <td>
                    <label class="inline-choice"><input type="radio" name="has_eye_disease" value="Yes" {{ ynChecked('has_eye_disease', $ncdAssessment, 'Yes') ? 'checked' : '' }}> Oo</label>
                    <label class="inline-choice"><input type="radio" name="has_eye_disease" value="No" {{ ynChecked('has_eye_disease', $ncdAssessment, 'No') ? 'checked' : '' }}> Hindi</label>
                </td>
                <td><input type="text" name="eye_year" value="{{ old('eye_year', $ncdAssessment?->eye_year) }}"></td>
                <td><input type="text" name="eye_meds" value="{{ old('eye_meds', $ncdAssessment?->eye_meds) }}"></td>
                <td></td>
            </tr>
        </table>

        <table class="doc-table">
            <tr style="background:var(--band); font-weight:700;">
                <td style="width: 78%;">2. Pananakit ng dibdib</td>
                <td style="width: 11%; text-align: center;">Oo</td>
                <td style="width: 11%; text-align: center;">Hindi</td>
            </tr>
            <tr>
                <td>2.1 Nakakaramdam ka ba ng pananakit o kabigatan sa dibdib? <i>Kung Hindi, Go to Q2.8.</i></td>
                <td style="text-align: center;"><input type="radio" name="cp1" value="Yes" {{ old('cp1', $ncdAssessment?->cp1) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align: center;"><input type="radio" name="cp1" value="No" {{ old('cp1', $ncdAssessment?->cp1) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>2.2 Ang sakit ba ay nasa gitna ng dibdib, kaliwang bahagi ng dibdib hanggang sa kaliwang braso?</td>
                <td style="text-align: center;"><input type="radio" name="cp2" value="Yes" {{ old('cp2', $ncdAssessment?->cp2) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align: center;"><input type="radio" name="cp2" value="No" {{ old('cp2', $ncdAssessment?->cp2) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>2.3 Nararamdaman mo ba ito kung nagmamadali o naglalakad ng mabilis o paakyat?</td>
                <td style="text-align: center;"><input type="radio" name="cp3" value="Yes" {{ old('cp3', $ncdAssessment?->cp3) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align: center;"><input type="radio" name="cp3" value="No" {{ old('cp3', $ncdAssessment?->cp3) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>2.4 Napapatigil ka ba sa paglalakad kapag sumasakit ang iyong dibdib?</td>
                <td style="text-align: center;"><input type="radio" name="cp4" value="Yes" {{ old('cp4', $ncdAssessment?->cp4) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align: center;"><input type="radio" name="cp4" value="No" {{ old('cp4', $ncdAssessment?->cp4) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>2.5 Nawawala ba ang sakit kapag hindi ka kumikilos o naglagay ng gamot sa ilalim ng dila?</td>
                <td style="text-align: center;"><input type="radio" name="cp5" value="Yes" {{ old('cp5', $ncdAssessment?->cp5) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align: center;"><input type="radio" name="cp5" value="No" {{ old('cp5', $ncdAssessment?->cp5) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>2.6 Nawawala ba ng sakit sa loob ng 10 minuto?</td>
                <td style="text-align: center;"><input type="radio" name="cp6" value="Yes" {{ old('cp6', $ncdAssessment?->cp6) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align: center;"><input type="radio" name="cp6" value="No" {{ old('cp6', $ncdAssessment?->cp6) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>2.7 Nakakaramdam ka ba ng sakit sa dibdib na tumatagal higit sa 30 minuto?</td>
                <td style="text-align: center;"><input type="radio" name="cp7" value="Yes" {{ old('cp7', $ncdAssessment?->cp7) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align: center;"><input type="radio" name="cp7" value="No" {{ old('cp7', $ncdAssessment?->cp7) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td colspan="3" class="alert-callout">
                    Kung Oo sagot sa Q2.4-2.7, maaring may angina o impending heart attack. Dalhin kaagad sa doktor.
                </td>
            </tr>
            <tr>
                <td>2.8 Nakaramdam ka na ba ng hirap sa pagsasalita, panghihina nang braso at/o binti O pamamanhid sa kalahating bahagi ng katawan? <b>Kung Oo, dalhin agad ang pasyente sa Doktor</b></td>
                <td style="text-align: center;"><input type="radio" name="cp8" value="Yes" {{ old('cp8', $ncdAssessment?->cp8) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align: center;"><input type="radio" name="cp8" value="No" {{ old('cp8', $ncdAssessment?->cp8) === 'No' ? 'checked' : '' }}></td>
            </tr>
        </table>

        <table class="doc-table">
            <colgroup>
                <col style="width: 75%;">
                <col style="width: 25%;">
            </colgroup>
            <tr>
                <td colspan="2" class="section-banner">Part III. Assessment of Risk Factors</td>
            </tr>
            <tr>
                <td colspan="2" class="subsection-banner">A. Non-Modifiable Risk Factors</td>
            </tr>
            <tr>
                <td>
                    <div style="font-weight: 600; margin-bottom: 12px;">1. Sakit sa pamilya (first degree relatives) Lagyan ng Tsek (/)</div>
                    <div class="choice-grid">
                        <label class="inline-choice"><input type="checkbox" name="fam_hypertension" value="Yes" {{ old('fam_hypertension', $ncdAssessment?->fam_hypertension) ? 'checked' : '' }}> mataas na presyon</label>
                        <label class="inline-choice"><input type="checkbox" name="fam_heart_disease" value="Yes" {{ old('fam_heart_disease', $ncdAssessment?->fam_heart_disease) ? 'checked' : '' }}> sakit sa puso</label>
                        <label class="inline-choice"><input type="checkbox" name="fam_stroke" value="Yes" {{ old('fam_stroke', $ncdAssessment?->fam_stroke) ? 'checked' : '' }}> stroke</label>
                        <label class="inline-choice"><input type="checkbox" name="fam_diabetes" value="Yes" {{ old('fam_diabetes', $ncdAssessment?->fam_diabetes) ? 'checked' : '' }}> diabetes</label>
                        <label class="inline-choice"><input type="checkbox" name="fam_cancer" value="Yes" {{ old('fam_cancer', $ncdAssessment?->fam_cancer) ? 'checked' : '' }}> kanser</label>
                        <label class="inline-choice"><input type="checkbox" name="fam_kidney_disease" value="Yes" {{ old('fam_kidney_disease', $ncdAssessment?->fam_kidney_disease) ? 'checked' : '' }}> sakit sa bato</label>
                        <label class="inline-choice wide"><input type="checkbox" name="fam_lung_disease" value="Yes" {{ old('fam_lung_disease', $ncdAssessment?->fam_lung_disease) ? 'checked' : '' }}> sakit sa baga na hindi nakakahawa</label>
                    </div>
                    <div class="other-field">
                        <span>Iba pang sakit, isulat:</span>
                        <input type="text" name="fam_other" value="{{ old('fam_other', $ncdAssessment?->fam_other) }}">
                    </div>
                </td>
                <td style="vertical-align: top;" class="criteria-box">
                    <b>*First degree relatives</b><br>
                    (magulang at mga kapatid)
                </td>
            </tr>
        </table>

        <table class="doc-table">
            <tr>
                <td colspan="4" class="section-banner">B. Modifiable Risk Factors</td>
            </tr>
            <tr>
                <td colspan="4" class="subsection-banner">B.1 Nutrisyon</td>
            </tr>
            <tr style="background:var(--band); font-weight:600; font-size:12px;">
                <td style="width: 58%;">1. Madalas mo bang kainin ang mga sumusunod kada araw?</td>
                <td style="width: 8%; text-align:center;">Oo</td>
                <td style="width: 8%; text-align:center;">Hindi</td>
                <td style="width: 26%;" rowspan="6" class="criteria-box">
                    <label class="inline-choice" style="margin-bottom:6px;"><input type="checkbox" name="r_diet" value="Yes" {{ ynChecked('r_diet', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">Unhealthy Diet</b></label><br>
                    Kung ang prutas at gulay na kinakain ay mas mababa sa 400 grams (limang portions ng prutas at gulay kada araw) sa nakaraang tatlong (3) buwan)
                </td>
            </tr>
            <tr>
                <td>a. gulay</td>
                <td style="text-align:center;"><input type="radio" name="diet_gulay" value="Yes" {{ old('diet_gulay', $ncdAssessment?->diet_gulay) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align:center;"><input type="radio" name="diet_gulay" value="No" {{ old('diet_gulay', $ncdAssessment?->diet_gulay) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>b. prutas</td>
                <td style="text-align:center;"><input type="radio" name="diet_prutas" value="Yes" {{ old('diet_prutas', $ncdAssessment?->diet_prutas) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align:center;"><input type="radio" name="diet_prutas" value="No" {{ old('diet_prutas', $ncdAssessment?->diet_prutas) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>c. isda</td>
                <td style="text-align:center;"><input type="radio" name="diet_isda" value="Yes" {{ old('diet_isda', $ncdAssessment?->diet_isda) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align:center;"><input type="radio" name="diet_isda" value="No" {{ old('diet_isda', $ncdAssessment?->diet_isda) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>d. karne</td>
                <td style="text-align:center;"><input type="radio" name="diet_karne" value="Yes" {{ old('diet_karne', $ncdAssessment?->diet_karne) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align:center;"><input type="radio" name="diet_karne" value="No" {{ old('diet_karne', $ncdAssessment?->diet_karne) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>e. processed food</td>
                <td style="text-align:center;"><input type="radio" name="diet_processed_food" value="Yes" {{ old('diet_processed_food', $ncdAssessment?->{'diet_processed_food'}) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align:center;"><input type="radio" name="diet_processed_food" value="No" {{ old('diet_processed_food', $ncdAssessment?->{'diet_processed_food'}) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr style="background:var(--band); font-weight:600; font-size:12px;">
                <td>2. Kumakain ka ba ng higit sa 2 beses kada linggo ng:</td>
                <td style="text-align:center;">Oo</td>
                <td style="text-align:center;">Hindi</td>
                <td rowspan="4" class="criteria-box">
                    <label class="inline-choice" style="margin-bottom:6px;"><input type="checkbox" name="r_salt" value="Yes" {{ ynChecked('r_salt', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">With high salt intake</b></label><br>
                    Kung kumakain ng maalat na pagkain higit sa 2 beses sa isang linggo
                </td>
            </tr>
            <tr>
                <td>a. maalat na pagkain</td>
                <td style="text-align:center;"><input type="radio" name="diet_maalat" value="Yes" {{ old('diet_maalat', $ncdAssessment?->diet_maalat) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align:center;"><input type="radio" name="diet_maalat" value="No" {{ old('diet_maalat', $ncdAssessment?->diet_maalat) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>b. matatamis na pagkain</td>
                <td style="text-align:center;"><input type="radio" name="diet_matatamis" value="Yes" {{ old('diet_matatamis', $ncdAssessment?->diet_matatamis) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align:center;"><input type="radio" name="diet_matatamis" value="No" {{ old('diet_matatamis', $ncdAssessment?->diet_matatamis) === 'No' ? 'checked' : '' }}></td>
            </tr>
            <tr>
                <td>c. mamantikang pagkain</td>
                <td style="text-align:center;"><input type="radio" name="diet_mamantika" value="Yes" {{ old('diet_mamantika', $ncdAssessment?->diet_mamantika) === 'Yes' ? 'checked' : '' }}></td>
                <td style="text-align:center;"><input type="radio" name="diet_mamantika" value="No" {{ old('diet_mamantika', $ncdAssessment?->diet_mamantika) === 'No' ? 'checked' : '' }}></td>
            </tr>

            <tr>
                <td colspan="4" class="subsection-banner">B.2 Alcohol</td>
            </tr>
            <tr>
                <td colspan="2">
                    1. Umiinom ka ba ng alak? 
                    <label class="inline-choice" style="margin-left:8px;"><input type="radio" name="alc_u" value="Oo" {{ old('alc_u', $ncdAssessment?->alc_u) === 'Oo' ? 'checked' : '' }}> Oo</label>
                    <label class="inline-choice"><input type="radio" name="alc_u" value="Hindi" {{ old('alc_u', $ncdAssessment?->alc_u) === 'Hindi' ? 'checked' : '' }}> Hindi</label>
                </td>
                <td colspan="2">
                    Kung hindi, gaano katagal ka na tumigil sa pag-inom?<br>
                    <label class="inline-choice"><input type="radio" name="alc_q" value="wala pang 1 taon" {{ old('alc_q', $ncdAssessment?->alc_q) === 'wala pang 1 taon' ? 'checked' : '' }}> wala pang 1 taon</label>
                    <label class="inline-choice"><input type="radio" name="alc_q" value=">= 1 taon" {{ old('alc_q', $ncdAssessment?->alc_q) === '>= 1 taon' ? 'checked' : '' }}> &ge; 1 taon</label>
                </td>
            </tr>
            <tr>
                <td>2. Anong klase?</td>
                <td colspan="2">
                    <div class="choice-row">
                        <label class="plain-choice"><input type="checkbox" name="alc_t[]" value="Beer"> Beer</label>
                        <label class="plain-choice"><input type="checkbox" name="alc_t[]" value="Wine"> Wine</label>
                        <label class="plain-choice"><input type="checkbox" name="alc_t[]" value="Whisky/Gin/Brandy"> Whisky/Gin/Brandy</label>
                    </div>
                </td>
                <td rowspan="4" class="criteria-box">
                    <label class="inline-choice" style="margin-bottom:6px;"><input type="checkbox" name="r_binge" value="Yes" {{ ynChecked('r_binge', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">Binge Drinker</b></label><br>
                    (kung ang lalaki ay umiinom ng 5 o higit na "standard na alak" sa isang okasyon sa nakaraang taon <b>OR</b> kung ang babae ay umiinom ng 4 o higit na beses sa isang okasyon sa nakaraang taon)
                </td>
            </tr>
            <tr>
                <td>3. Gaano karami sa isang araw?</td>
                <td>
                    <input type="text" name="amt_b" value="{{ old('amt_b', $ncdAssessment?->amt_b) }}" placeholder="e.g. 1 / 2 / &ge;3 bote">
                </td>
                <td>
                    <input type="text" name="amt_w" value="{{ old('amt_w', $ncdAssessment?->amt_w) }}" placeholder="e.g. &le;3 / &gt;4 glasses">
                </td>
            </tr>
            <tr>
                <td>4. Gaano kadalas ka umiinom ng alak sa isang linggo?</td>
                <td colspan="2">
                    <select name="alc_f">
                        <option value="">-- Select --</option>
                        <option value="1-3 beses/linggo" {{ old('alc_f', $ncdAssessment?->alc_f) === '1-3 beses/linggo' ? 'selected' : '' }}>1-3 beses/linggo</option>
                        <option value="apat na beses" {{ old('alc_f', $ncdAssessment?->alc_f) === 'apat na beses' ? 'selected' : '' }}>apat na beses sa isang linggo</option>
                        <option value="lima o higit" {{ old('alc_f', $ncdAssessment?->alc_f) === 'lima o higit' ? 'selected' : '' }}>limang beses o higit sa isang linggo</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>5. Sa isang okasyon, ilang bote ng alak ang naiinom mo?</td>
                <td colspan="2">
                    <select name="alc_b">
                        <option value="">-- Select --</option>
                        <option value="< 4" {{ old('alc_b', $ncdAssessment?->alc_b) === '< 4' ? 'selected' : '' }}>&lt; 4</option>
                        <option value="4" {{ old('alc_b', $ncdAssessment?->alc_b) === '4' ? 'selected' : '' }}>4</option>
                        <option value="5 o higit" {{ old('alc_b', $ncdAssessment?->alc_b) === '5 o higit' ? 'selected' : '' }}>5 o higit</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="4" class="subsection-banner">B.3 Ehersisyo</td>
            </tr>
            <tr>
                <td colspan="3">
                    1. May sapat ka bang ehersisyo? 
                    <label class="inline-choice" style="margin-left:8px;"><input type="radio" name="has_exercise" value="Mayroon" {{ old('has_exercise', $ncdAssessment?->has_exercise) === 'Mayroon' ? 'checked' : '' }}> Mayroon</label>
                    <label class="inline-choice"><input type="radio" name="has_exercise" value="Wala" {{ old('has_exercise', $ncdAssessment?->has_exercise) === 'Wala' ? 'checked' : '' }}> Wala</label>
                    <div style="font-size:11px; color:var(--text-muted); margin-top:3px;">(150 minuto sa loob ng isang linggo na hindi lalampas ng 2 araw na pagitan)</div>
                    <div class="inline-question">
                        <span>2. Anong klaseng ehersisyo ang iyong ginagawa?</span>
                        <select name="exercise_type">
                            <option value="">-- Select --</option>
                            <option value="Moderate Intensity" {{ old('exercise_type', $ncdAssessment?->exercise_type) === 'Moderate Intensity' ? 'selected' : '' }}>Moderate Intensity</option>
                            <option value="Vigorous Intensity" {{ old('exercise_type', $ncdAssessment?->exercise_type) === 'Vigorous Intensity' ? 'selected' : '' }}>Vigorous Intensity</option>
                            <option value="Kombinasyon ng dalawa" {{ old('exercise_type', $ncdAssessment?->exercise_type) === 'Kombinasyon ng dalawa' ? 'selected' : '' }}>Kombinasyon ng dalawa</option>
                        </select>
                    </div>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice" style="margin-bottom:6px;"><input type="checkbox" name="risk_activity" value="Yes" {{ ynChecked('risk_activity', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">Insufficient Physical Activity</b></label><br>
                    (kung "wala" <b>OR</b> "wala pang 150 minuto sa loob ng isang linggo na hindi lalampas sa 2 araw na pagitan sa nakaraang tatlong (3) buwan)
                </td>
            </tr>

            <tr>
                <td colspan="4" class="subsection-banner">B.4 Paninigarilyo</td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="font-weight:600; margin-bottom:10px;">1. Ikaw ba ay naninigarilyo?</div>
                    <div class="smoke-line">
                        <label class="plain-choice"><input type="radio" name="smoke_status" value="current" {{ old('smoke_status', $ncdAssessment?->smoke_status) === 'current' ? 'checked' : '' }}> Oo, Gaano kadami ang sigarilyo sa isang araw?</label>
                        <input type="text" name="smoke_sticks_per_day" value="{{ old('smoke_sticks_per_day', $ncdAssessment?->smoke_sticks_per_day) }}" placeholder="(number of sticks kada araw)" style="width:210px; flex:0 0 auto;">
                    </div>
                    <div class="smoke-line">
                        <label class="plain-choice"><input type="radio" name="smoke_status" value="quit" {{ old('smoke_status', $ncdAssessment?->smoke_status) === 'quit' ? 'checked' : '' }}> Oo, pero tumigil na. Gaano ka na katagal tumigil manigarilyo?</label>
                        <label class="plain-choice"><input type="radio" name="smoke_quit_duration" value="wala pang 1 taon" {{ old('smoke_quit_duration', $ncdAssessment?->smoke_quit_duration) === 'wala pang 1 taon' ? 'selected' : '' }}> wala pang 1 taon</label>
                        <label class="plain-choice"><input type="radio" name="smoke_quit_duration" value=">= 1 taon" {{ old('smoke_quit_duration', $ncdAssessment?->smoke_quit_duration) === '>= 1 taon' ? 'selected' : '' }}> &ge; 1 taon</label>
                    </div>
                    <div class="smoke-line smoke-sub">
                        <span>Ikaw ba ay naka-100 sticks o higit pa mula ng tumigil manigarilyo?</span>
                        <label class="plain-choice"><input type="radio" name="smoke_100_sticks" value="Yes" {{ old('smoke_100_sticks', $ncdAssessment?->smoke_100_sticks) === 'Yes' ? 'checked' : '' }}> Oo</label>
                        <label class="plain-choice"><input type="radio" name="smoke_100_sticks" value="No" {{ old('smoke_100_sticks', $ncdAssessment?->smoke_100_sticks) === 'No' ? 'checked' : '' }}> Hindi</label>
                    </div>
                    <div class="smoke-line">
                        <label class="plain-choice"><input type="radio" name="smoke_status" value="never" {{ old('smoke_status', $ncdAssessment?->smoke_status) === 'never' ? 'checked' : '' }}> Hindi, pero lantad ka ba sa usok ng sigarilyo?</label>
                        <label class="plain-choice"><input type="radio" name="smoke_exposed" value="Yes" {{ old('smoke_exposed', $ncdAssessment?->smoke_exposed) === 'Yes' ? 'checked' : '' }}> Oo</label>
                        <label class="plain-choice"><input type="radio" name="smoke_exposed" value="No" {{ old('smoke_exposed', $ncdAssessment?->smoke_exposed) === 'No' ? 'checked' : '' }}> Hindi</label>
                    </div>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice" style="margin-bottom:6px;"><input type="checkbox" name="risk_smoking_history" value="Yes" {{ ynChecked('risk_smoking_history', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">With History of Smoking</b></label><br>
                    (kung naka-100 sticks o higit pa mula ng tumigil manigarilyo)<br><br>
                    <label class="inline-choice" style="margin-bottom:6px;"><input type="checkbox" name="risk_smoker" value="Yes" {{ ynChecked('risk_smoker', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">Smoker</b></label><br>
                    (kung naninigarilyo o wala pang isang taon tumigil sa paninigarilyo)
                </td>
            </tr>

            <tr>
                <td colspan="4" class="subsection-banner">B.5 Stress</td>
            </tr>
            <tr>
                <td colspan="3">
                    1. Madalas ka bang ma-stress? 
                    <label class="inline-choice" style="margin-left:8px;"><input type="radio" name="stress_frequent" value="Yes" {{ old('stress_frequent', $ncdAssessment?->stress_frequent) === 'Yes' ? 'checked' : '' }}> Oo</label>
                    <label class="inline-choice"><input type="radio" name="stress_frequent" value="No" {{ old('stress_frequent', $ncdAssessment?->stress_frequent) === 'No' ? 'checked' : '' }}> Hindi</label>
                    <div style="margin-top:8px;">
                        2. Ano or Sino ang dahilan ng iyong stress? 
                        <input type="text" name="stress_cause" value="{{ old('stress_cause', $ncdAssessment?->stress_cause) }}" style="width:250px; display:inline-block; margin-left:6px;">
                    </div>
                    <div style="margin-top:8px;">
                        3. Naaapektuhan ba ang iyong araw-araw na pamumuhay O paggalaw dahil dito? 
                        <label class="inline-choice" style="margin-left:8px;"><input type="radio" name="stress_affects_life" value="Yes" {{ old('stress_affects_life', $ncdAssessment?->stress_affects_life) === 'Yes' ? 'checked' : '' }}> Oo</label>
                        <label class="inline-choice"><input type="radio" name="stress_affects_life" value="No" {{ old('stress_affects_life', $ncdAssessment?->stress_affects_life) === 'No' ? 'checked' : '' }}> Hindi</label>
                    </div>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice" style="margin-bottom:6px;"><input type="checkbox" name="risk_stress" value="Yes" {{ ynChecked('risk_stress', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">Stressed</b></label><br>
                    (kung naapektuhan ang araw-araw na pamumuhay)
                </td>
            </tr>
        </table>

        <table class="doc-table">
            <tr>
                <td colspan="6" class="section-banner">Part IV. Risk Screening</td>
            </tr>
            <tr>
                <td style="width: 20%;" rowspan="2">
                    <b>4.1 Anthropometric Measurement</b><br><br>
                    <div class="criteria-box" style="background:transparent; border-left:none !important;">
                        <b>Formula:</b><br>
                        <u>BMI:</u> (Wt(kg)/Ht(cm)/Ht(cm)) x 10,000<br><br>
                        <u>Waist - Hip Ratio:</u> W(cm)/H(cm)
                    </div>
                </td>
                <td style="width: 13%;">
                    <span class="cell-label">Weight</span>
                    <input type="number" step="0.1" name="w" value="{{ old('w', $ncdAssessment?->w) }}" placeholder="kg">
                </td>
                <td style="width: 13%;">
                    <span class="cell-label">Height</span>
                    <input type="number" step="0.1" name="h" value="{{ old('h', $ncdAssessment?->h) }}" placeholder="cm">
                </td>
                <td style="width: 13%;">
                    <span class="cell-label">BMI</span>
                    <input type="text" name="bmi" value="{{ old('bmi', $ncdAssessment?->bmi) }}" readonly placeholder="Auto">
                </td>
                <td style="width: 22%;">
                    <span class="cell-label">Status</span>
                    <select name="bmi_s">
                        <option value="">-- Select --</option>
                        <option value="Underweight" {{ old('bmi_s', $ncdAssessment?->bmi_s) === 'Underweight' ? 'selected' : '' }}>Underweight (&lt;18.5)</option>
                        <option value="Normal" {{ old('bmi_s', $ncdAssessment?->bmi_s) === 'Normal' ? 'selected' : '' }}>Normal (18.5-22.9)</option>
                        <option value="Overweight" {{ old('bmi_s', $ncdAssessment?->bmi_s) === 'Overweight' ? 'selected' : '' }}>Overweight (&ge;23-24.9)</option>
                        <option value="Obese" {{ old('bmi_s', $ncdAssessment?->bmi_s) === 'Obese' ? 'selected' : '' }}>Obese (&ge;25)</option>
                    </select>
                </td>
                <td style="width: 20%;" class="criteria-box">
                    <label class="inline-choice"><input type="checkbox" name="r_over" value="Yes" {{ ynChecked('r_over', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">Overweight</b></label><br>
                    <label class="inline-choice" style="margin-top:6px;"><input type="checkbox" name="r_obese" value="Yes" {{ ynChecked('r_obese', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">Obese</b></label>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="cell-label">Waist (cm)</span>
                    <input type="number" step="0.1" name="waist" value="{{ old('waist', $ncdAssessment?->waist) }}">
                </td>
                <td>
                    <span class="cell-label">Hip (cm)</span>
                    <input type="number" step="0.1" name="hip" value="{{ old('hip', $ncdAssessment?->hip) }}">
                </td>
                <td>
                    <span class="cell-label">W/H Ratio</span>
                    <input type="text" name="whr" value="{{ old('whr', $ncdAssessment?->whr) }}">
                </td>
                <td>
                    <span class="cell-label">Status</span>
                    <select name="whr_s">
                        <option value="">-- Select --</option>
                        <option value="No risk" {{ old('whr_s', $ncdAssessment?->whr_s) === 'No risk' ? 'selected' : '' }}>No risk (Male &lt;1.0 cm, Female &lt;0.85 cm)</option>
                        <option value="At risk" {{ old('whr_s', $ncdAssessment?->whr_s) === 'At risk' ? 'selected' : '' }}>At risk (Male &ge;1 cm, Female &ge;0.85 cm)</option>
                    </select>
                </td>
                <td class="criteria-box">
                    <span class="criteria-note">Waist - Hip Ratio risk is set using the Status dropdown.</span>
                </td>
            </tr>

            <tr>
                <td rowspan="2"><b>4.2 Blood Sugar</b></td>
                <td colspan="2">
                    <span class="cell-label">Fasting Blood Sugar (CBG)</span>
                    <input type="number" step="0.1" name="fbs" value="{{ old('fbs', $ncdAssessment?->fbs) }}">
                </td>
                <td>
                    <span class="cell-label">FBS Venous Extraction</span>
                    <input type="number" step="0.1" name="vn" value="{{ old('vn', $ncdAssessment?->vn) }}">
                </td>
                <td>
                    <span class="cell-label">Status</span>
                    <select name="fbs_s">
                        <option value="">-- Select --</option>
                        <option value="Normal" {{ old('fbs_s', $ncdAssessment?->fbs_s) === 'Normal' ? 'selected' : '' }}>Normal (&lt; 100 mg/dL)</option>
                        <option value="Impaired" {{ old('fbs_s', $ncdAssessment?->fbs_s) === 'Impaired' ? 'selected' : '' }}>Impaired fasting glucose (100-125 mg/dL)</option>
                        <option value="DM" {{ old('fbs_s', $ncdAssessment?->fbs_s) === 'DM' ? 'selected' : '' }}>DM (&ge;126 mg/dL)</option>
                    </select>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice"><input type="checkbox" name="r_predm" value="Yes" {{ ynChecked('r_predm', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">Pre-Diabetes</b></label><br>
                    (High FBS: CBG (100-125 mg/dL)
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="cell-label">Random Blood Sugar</span>
                    <select name="rbs_s">
                        <option value="">-- Select --</option>
                        <option value="Normal" {{ old('rbs_s', $ncdAssessment?->rbs_s) === 'Normal' ? 'selected' : '' }}>Normal: &lt; 140 mg/dL</option>
                        <option value="Abnormal" {{ old('rbs_s', $ncdAssessment?->rbs_s) === 'Abnormal' ? 'selected' : '' }}>Abnormal: &ge; 200 mg/dL</option>
                    </select>
                </td>
                <td colspan="2">
                    <span class="cell-label">Check if clinical symptom is present</span>
                    <div class="choice-row">
                        <label class="plain-choice"><input type="checkbox" name="s_pol" value="Yes" {{ old('s_pol', $ncdAssessment?->s_pol) ? 'checked' : '' }}> Polyuria</label>
                        <label class="plain-choice"><input type="checkbox" name="s_pdp" value="Yes" {{ old('s_pdp', $ncdAssessment?->s_pdp) ? 'checked' : '' }}> Polydipsia</label>
                        <label class="plain-choice"><input type="checkbox" name="s_pph" value="Yes" {{ old('s_pph', $ncdAssessment?->s_pph) ? 'checked' : '' }}> Polyphagia</label>
                        <label class="plain-choice"><input type="checkbox" name="s_wgt" value="Yes" {{ old('s_wgt', $ncdAssessment?->s_wgt) ? 'checked' : '' }}> Weight loss</label>
                    </div>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice"><input type="checkbox" name="r_dm_f" value="Yes" {{ ynChecked('r_dm_f', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">DM</b></label><br>
                    (FBS &ge; 126mg/dl <b>OR</b> RBS &ge; 200 mg/dL with any classic symptoms)
                </td>
            </tr>

            <tr>
                <td><b>4.3 Blood Pressure</b></td>
                <td><span class="cell-label">Left arm mean BP</span><input type="text" name="bp_l" value="{{ old('bp_l', $ncdAssessment?->bp_l) }}" placeholder="120/80"></td>
                <td><span class="cell-label">Right arm mean BP</span><input type="text" name="bp_r" value="{{ old('bp_r', $ncdAssessment?->bp_r) }}" placeholder="120/80"></td>
                <td><span class="cell-label">Baseline BP</span><input type="text" name="bp_b" value="{{ old('bp_b', $ncdAssessment?->bp_b) }}" placeholder="120/80"></td>
                <td>
                    <span class="cell-label">Status</span>
                    <select name="bp_s">
                        <option value="">-- Select --</option>
                        <option value="Normal" {{ old('bp_s', $ncdAssessment?->bp_s) === 'Normal' ? 'selected' : '' }}>Normal (&lt; 120 mmHg/&lt; 80 mmHg)</option>
                        <option value="Pre-HPN" {{ old('bp_s', $ncdAssessment?->bp_s) === 'Pre-HPN' ? 'selected' : '' }}>Pre-HPN (120-139 mmHg/80-89 mmHg)</option>
                        <option value="Stage 1" {{ old('bp_s', $ncdAssessment?->bp_s) === 'Stage 1' ? 'selected' : '' }}>Stage 1 (140-159 mmHg/90-99 mmHg)</option>
                        <option value="Stage 2" {{ old('bp_s', $ncdAssessment?->bp_s) === 'Stage 2' ? 'selected' : '' }}>Stage 2 (&ge;160 / &ge;100 mmHg)</option>
                    </select>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice"><input type="checkbox" name="r_hpn_pre" value="Yes" {{ old('bp_s', $ncdAssessment?->bp_s) === 'Pre-HPN' ? 'checked' : '' }}> <b class="risk-badge">Pre-HPN</b></label> (Elevated BP)<br>
                    <label class="inline-choice" style="margin-top:6px;"><input type="checkbox" name="r_hpn_f" value="Yes" {{ ynChecked('r_hpn_f', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">HPN</b></label> (Stage 1 or Stage 2)
                </td>
            </tr>

            <tr>
                <td><b>4.4 Cholesterol Level</b></td>
                <td><span class="cell-label">Result</span><input type="number" step="0.1" name="chol" value="{{ old('chol', $ncdAssessment?->chol) }}"></td>
                <td colspan="3">
                    <span class="cell-label">Status</span>
                    <select name="ch_s">
                        <option value="">-- Select --</option>
                        <option value="Normal" {{ old('ch_s', $ncdAssessment?->ch_s) === 'Normal' ? 'selected' : '' }}>Normal (&lt; 200 mg/100 ml)</option>
                        <option value="Elevated - at risk" {{ old('ch_s', $ncdAssessment?->ch_s) === 'Elevated - at risk' ? 'selected' : '' }}>Elevated, maybe at risk (200-239/100 ml)</option>
                        <option value="Elevated" {{ old('ch_s', $ncdAssessment?->ch_s) === 'Elevated' ? 'selected' : '' }}>Elevated, at risk (240/100 ml and above)</option>
                    </select>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice"><input type="checkbox" name="r_chol" value="Yes" {{ ynChecked('r_chol', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">with high cholesterol level</b></label><br>
                    (elevated, at risk)
                </td>
            </tr>

            <tr>
                <td rowspan="2"><b>4.5 Urine Dipstick Test</b></td>
                <td><span class="cell-label">Protein</span></td>
                <td colspan="3">
                    <label class="inline-choice"><input type="radio" name="pro" value="+" {{ old('pro', $ncdAssessment?->pro) === '+' ? 'checked' : '' }}> (+) urine protein</label>
                    <label class="inline-choice"><input type="radio" name="pro" value="-" {{ old('pro', $ncdAssessment?->pro) === '-' ? 'checked' : '' }}> (-) urine protein</label>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice"><input type="checkbox" name="r_pro" value="Yes" {{ ynChecked('r_pro', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">(+) urine protein</b></label>
                </td>
            </tr>
            <tr>
                <td><span class="cell-label">Ketones</span></td>
                <td colspan="3">
                    <label class="inline-choice"><input type="radio" name="ket" value="+" {{ old('ket', $ncdAssessment?->ket) === '+' ? 'checked' : '' }}> (+) urine ketones</label>
                    <label class="inline-choice"><input type="radio" name="ket" value="-" {{ old('ket', $ncdAssessment?->ket) === '-' ? 'checked' : '' }}> (-) urine ketones</label>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice"><input type="checkbox" name="r_ket" value="Yes" {{ old('ket', $ncdAssessment?->ket) === '+' ? 'checked' : '' }}> <b class="risk-badge">(+) urine ketones</b></label>
                </td>
            </tr>

            <tr>
                <td>
                    <b>4.6 Risk Profile</b><br>
                    <span style="font-size:11px; color:var(--text-muted); font-style:italic;">For Doctors Only</span>
                </td>
                <td colspan="4">
                    <label class="inline-choice"><input type="radio" name="rp" value="<5%" {{ old('rp', $ncdAssessment?->rp) === '<5%' ? 'checked' : '' }}> &lt;5%</label>
                    <label class="inline-choice"><input type="radio" name="rp" value="5-<10%" {{ old('rp', $ncdAssessment?->rp) === '5-<10%' ? 'checked' : '' }}> 5-&lt;10%</label>
                    <label class="inline-choice"><input type="radio" name="rp" value="10%-<20%" {{ old('rp', $ncdAssessment?->rp) === '10%-<20%' ? 'checked' : '' }}> 10%-&lt;20%</label>
                    <label class="inline-choice"><input type="radio" name="rp" value="20-<30" {{ old('rp', $ncdAssessment?->rp) === '20-<30' ? 'checked' : '' }}> 20-&lt;30%</label>
                    <label class="inline-choice"><input type="radio" name="rp" value=">=30%" {{ old('rp', $ncdAssessment?->rp) === '>=30%' ? 'checked' : '' }}> &ge;30%</label>
                </td>
                <td class="criteria-box">
                    <label class="inline-choice"><input type="checkbox" name="r_30" value="Yes" {{ ynChecked('r_30', $ncdAssessment, 'Yes') ? 'checked' : '' }}> <b class="risk-badge">&ge; 30% risk</b></label>
                </td>
            </tr>

            <tr>
                <td colspan="5">
                    <b>4.7 Cancer Screening (Babae na 30 years old and above)</b><br>
                    <div style="margin-top:6px;">
                        1. Ikaw ba ay nai-screen na sa Breast/Cervical Cancer?
                        <label class="inline-choice" style="margin-left:12px;"><input type="radio" name="cs" value="Oo" {{ old('cs', $ncdAssessment?->cs) === 'Oo' ? 'checked' : '' }}> Oo (paalalahanan sa next visit)</label>
                        <label class="inline-choice"><input type="radio" name="cs" value="Hindi" {{ old('cs', $ncdAssessment?->cs) === 'Hindi' ? 'checked' : '' }}> Hindi (i-refer)</label>
                    </div>
                </td>
                <td class="criteria-box"></td>
            </tr>
        </table>

        <table class="doc-table" style="margin-top: 20px;">
            <tr>
                <td style="width: 38%;">
                    <span class="cell-label">Interviewed / Assessed by</span>
                    <input type="text" value="{{ auth()->user()->name ?? '' }}" readonly>
                </td>
                <td style="width: 32%;">
                    <span class="cell-label">Designation</span>
                    <input type="text" name="designation" value="{{ old('designation', $ncdAssessment?->designation) }}" placeholder="e.g. Physician, Nurse, BHW">
                </td>
                <td style="width: 30%;">
                    <span class="cell-label">Date</span>
                    <input type="text" name="sign_date" value="{{ old('sign_date', date('m/d/Y')) }}">
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="cell-label">Signature of Patient</span>
                    <div style="height:44px; border-bottom:1px solid var(--line-strong); max-width:420px;"></div>
                </td>
            </tr>
        </table>

    </div>

    <div class="footer-bar-wrap">
        <div class="footer-bar">
            <a href="{{ route(strtolower(auth()->user()->role) . '.patient-records') }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">Save Assessment Form</button>
        </div>
    </div>
</form>

</body>
</html>
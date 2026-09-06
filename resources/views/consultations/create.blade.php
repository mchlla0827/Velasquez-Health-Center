<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Consultation - {{ $patient->first_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb; --primary-dark: #1d4ed8; --primary-soft: #eff6ff; --primary-line: #dbeafe;
            --bg: #f1f5f9; --line: #e5e7eb; --line-strong: #d1d5db;
            --text: #0f172a; --text-muted: #6b7280; --band: #f8fafc;
        }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background: var(--bg); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 13.5px; color: var(--text); line-height: 1.5; }

        .top-bar { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color: #fff; padding: 22px 28px; }
        .top-bar h2 { margin: 0; font-size: 19px; font-weight: 700; }
        .top-bar .patient-line { margin-top: 4px; font-size: 13px; opacity: .9; }

        .wrap { max-width: 900px; margin: 24px auto 110px; padding: 0 20px; }

        .alert-box { padding: 14px 18px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-weight: 600; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-error ul { margin: 6px 0 0; padding-left: 18px; }

        .card { background: #fff; border: 1px solid var(--line-strong); border-radius: 10px; margin-bottom: 16px; overflow: hidden; }
        .card-title { background: #111827; color: #fff; padding: 11px 16px; font-size: 13px; font-weight: 700; letter-spacing: .3px; text-transform: uppercase; }
        .card-body { padding: 18px; }

        .type-picker { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .type-option { border: 2px solid var(--line-strong); border-radius: 8px; padding: 12px 14px; cursor: pointer; transition: all .12s; }
        .type-option:hover { border-color: var(--primary-line); background: var(--primary-soft); }
        .type-option input[type="radio"] { display: none; }
        .type-option.selected { border-color: var(--primary); background: var(--primary-soft); }
        .type-option .t-name { font-weight: 700; font-size: 13.5px; color: var(--text); }
        .type-option .t-desc { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

        .field-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px 16px; }
        .field-grid.c3 { grid-template-columns: repeat(3, 1fr); }
        .field-grid.c4 { grid-template-columns: repeat(4, 1fr); }
        .field { display: flex; flex-direction: column; }
        .field.full { grid-column: 1 / -1; }
        .field label { font-size: 11.5px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .3px; margin-bottom: 5px; }
        .field input[type="text"], .field input[type="number"], .field input[type="date"], .field select, .field textarea {
            border: 1px solid var(--line-strong); border-radius: 7px; padding: 8px 10px; font-size: 13px; font-family: inherit; color: var(--text); background: #fbfcfe;
        }
        .field textarea { resize: vertical; min-height: 60px; }
        .field input:focus, .field select:focus, .field textarea:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,.12); background: #fff; }
        .field input[readonly] { background: var(--band); color: var(--text-muted); }

        .type-section { display: none; }
        .type-section.active { display: block; }

        .ncd-note { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; border-radius: 8px; padding: 14px 16px; font-size: 12.5px; line-height: 1.5; }
        .ncd-note b { display: block; margin-bottom: 4px; font-size: 13px; }

        .review-box { display: none; }
        .review-box.active { display: block; }
        .review-row { display: flex; gap: 16px; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
        .review-row:last-child { border-bottom: none; }
        .review-row .k { flex: 0 0 200px; font-size: 12px; color: var(--text-muted); }
        .review-row .v { flex: 1; font-size: 13px; font-weight: 500; }

        .footer-bar { position: fixed; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,.95); backdrop-filter: blur(8px); border-top: 1px solid var(--line-strong); padding: 14px 20px; display: flex; justify-content: flex-end; gap: 10px; }
        .btn-primary { padding: 10px 24px; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 13.5px; cursor: pointer; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary { padding: 10px 24px; background: #fff; color: var(--text-muted); border: 1px solid var(--line-strong); border-radius: 8px; font-weight: 600; font-size: 13.5px; cursor: pointer; text-decoration: none; }
        .btn-secondary:hover { background: var(--band); }

        @media (max-width: 700px) {
            .field-grid, .field-grid.c3, .field-grid.c4, .type-picker { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h2>New Consultation</h2>
    <div class="patient-line">{{ $patient->last_name }}, {{ $patient->first_name }} &middot; {{ $patient->patient_id }} &middot; {{ $patient->age }} y/o &middot; {{ $patient->gender }}</div>
</div>

<form action="{{ route('consultations.store', $patient->id) }}" method="POST" id="consultForm">
    @csrf

    <div class="wrap">

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

        <div id="formView">

            <div class="card">
                <div class="card-title">Consultation Type</div>
                <div class="card-body">
                    <div class="type-picker" id="typePicker">
                        <label class="type-option" data-type="general">
                            <input type="radio" name="consultation_type" value="general" required>
                            <div class="t-name">General Consultation</div>
                            <div class="t-desc">Regular visit, symptoms, diagnosis, treatment</div>
                        </label>
                        <label class="type-option" data-type="ncd_risk_assessment">
                            <input type="radio" name="consultation_type" value="ncd_risk_assessment">
                            <div class="t-name">NCD Risk Assessment</div>
                            <div class="t-desc">Full DOH risk screening form</div>
                        </label>
                        <label class="type-option" data-type="ncd_followup">
                            <input type="radio" name="consultation_type" value="ncd_followup">
                            <div class="t-name">NCD Follow-up</div>
                            <div class="t-desc">Monitoring a known NCD condition</div>
                        </label>
                        <label class="type-option" data-type="followup_recheck">
                            <input type="radio" name="consultation_type" value="followup_recheck">
                            <div class="t-name">Follow-up / Re-check</div>
                            <div class="t-desc">Checking progress on a prior visit</div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Visit Information</div>
                <div class="card-body">
                    <div class="field-grid">
                        <div class="field">
                            <label>Consultation Date</label>
                            <input type="date" name="consultation_date" value="{{ old('consultation_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="field">
                            <label>Reason for Visit</label>
                            <input type="text" name="reason_for_visit" value="{{ old('reason_for_visit') }}">
                        </div>
                        <div class="field full">
                            <label>Chief Complaint</label>
                            <textarea name="chief_complaint">{{ old('chief_complaint') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Vital Signs</div>
                <div class="card-body">
                    <div class="field-grid c4">
                        <div class="field"><label>Blood Pressure</label><input type="text" name="vital_bp" placeholder="120/80" value="{{ old('vital_bp') }}"></div>
                        <div class="field"><label>Temperature (&deg;C)</label><input type="text" name="vital_temp" value="{{ old('vital_temp') }}"></div>
                        <div class="field"><label>Pulse Rate</label><input type="text" name="vital_pulse" value="{{ old('vital_pulse') }}"></div>
                        <div class="field"><label>Resp. Rate</label><input type="text" name="vital_resp_rate" value="{{ old('vital_resp_rate') }}"></div>
                        <div class="field"><label>O2 Saturation</label><input type="text" name="vital_o2sat" value="{{ old('vital_o2sat') }}"></div>
                        <div class="field"><label>Weight (kg)</label><input type="number" step="0.1" id="vw" name="vital_weight" value="{{ old('vital_weight') }}"></div>
                        <div class="field"><label>Height (cm)</label><input type="number" step="0.1" id="vh" name="vital_height" value="{{ old('vital_height') }}"></div>
                        <div class="field"><label>BMI (auto)</label><input type="text" id="vbmi" readonly placeholder="Auto"></div>
                    </div>
                </div>
            </div>
            <!-- ========== GENERAL CONSULTATION ========== -->
            <div class="card type-section" id="section-general">
                <div class="card-title">General Consultation Details</div>
                <div class="card-body">
                    <div class="field-grid">
                        <div class="field"><label>Duration of Symptoms</label><input type="text" name="details[duration_of_symptoms]" placeholder="e.g. 3 days"></div>
                        <div class="field"><label>Symptoms</label><input type="text" name="details[symptoms]"></div>
                        <div class="field full"><label>History of Present Illness</label><textarea name="details[hpi]"></textarea></div>
                        <div class="field full"><label>Physical Examination</label><textarea name="details[physical_exam]"></textarea></div>
                    </div>
                </div>
            </div>

            <!-- ========== NCD RISK ASSESSMENT ========== -->
            <div class="card type-section" id="section-ncd_risk_assessment">
                <div class="card-title">NCD Risk Assessment</div>
                <div class="card-body">
                    <div class="ncd-note">
                        <b>This uses the full DOH NCD Risk Assessment form.</b>
                        After you save this consultation, you'll be taken straight to the complete NCD Risk Assessment form to fill in the detailed screening. The two records will be linked automatically.
                    </div>
                </div>
            </div>

            <!-- ========== NCD FOLLOW-UP ========== -->
            <div class="card type-section" id="section-ncd_followup">
                <div class="card-title">NCD Follow-up Details</div>
                <div class="card-body">
                    <div class="field-grid">
                        <div class="field full">
                            <label>Previous NCD Assessment</label>
                            <select name="related_ncd_assessment_id">
                                <option value="">-- Select a previous assessment --</option>
                                @foreach ($ncdAssessments as $a)
                                    <option value="{{ $a->id }}">
                                        {{ $a->assessment_date }}
                                        @if ($a->is_diabetic) &middot; Diabetes @endif
                                        @if ($a->is_hypertensive) &middot; Hypertension @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field"><label>Current Symptoms</label><input type="text" name="details[current_symptoms]"></div>
                        <div class="field"><label>Medication Compliance</label>
                            <select name="details[medication_compliance]">
                                <option value="">-- Select --</option>
                                <option value="Compliant">Compliant</option>
                                <option value="Partially Compliant">Partially Compliant</option>
                                <option value="Non-compliant">Non-compliant</option>
                            </select>
                        </div>
                        <div class="field full"><label>Medication Side Effects</label><input type="text" name="details[medication_side_effects]"></div>
                        <div class="field full"><label>Disease Monitoring Notes</label><textarea name="details[disease_monitoring]"></textarea></div>
                        <div class="field"><label>Hypertension Status</label>
                            <select name="details[hpn_status]">
                                <option value="">-- Select --</option>
                                <option value="Controlled">Controlled</option>
                                <option value="Uncontrolled">Uncontrolled</option>
                                <option value="Not applicable">Not applicable</option>
                            </select>
                        </div>
                        <div class="field"><label>Diabetes Status</label>
                            <select name="details[dm_status]">
                                <option value="">-- Select --</option>
                                <option value="Controlled">Controlled</option>
                                <option value="Uncontrolled">Uncontrolled</option>
                                <option value="Not applicable">Not applicable</option>
                            </select>
                        </div>
                        <div class="field full"><label>Medication Action</label><input type="text" name="details[medication_action]" placeholder="e.g. Continue, adjust dose, add medication"></div>
                        <div class="field full"><label>Treatment Plan</label><textarea name="details[treatment_plan]"></textarea></div>
                        <div class="field full"><label>Lifestyle Advice</label><textarea name="details[lifestyle_advice]"></textarea></div>
                        <div class="field full"><label>Laboratory Request</label><input type="text" name="details[lab_request]"></div>
                    </div>
                </div>
            </div>

            <!-- ========== FOLLOW-UP / RE-CHECK ========== -->
            <div class="card type-section" id="section-followup_recheck">
                <div class="card-title">Follow-up / Re-check Details</div>
                <div class="card-body">
                    <div class="field-grid">
                        <div class="field full">
                            <label>Previous Consultation</label>
                            <select name="related_consultation_id" id="prevConsultSelect">
                                <option value="">-- Select a previous consultation --</option>
                                @foreach ($previousConsultations as $c)
                                    <option value="{{ $c->id }}" data-diagnosis="{{ $c->assessment_diagnosis }}">
                                        {{ $c->consultation_date->format('Y-m-d') }} &middot; {{ \App\Models\Consultation::TYPE_LABELS[$c->consultation_type] ?? $c->consultation_type }}
                                        @if ($c->assessment_diagnosis) &middot; {{ \Illuminate\Support\Str::limit($c->assessment_diagnosis, 40) }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field full"><label>Previous Diagnosis (from selected visit)</label><input type="text" id="prevDiagnosisDisplay" readonly placeholder="Select a previous consultation above"></div>
                        <div class="field"><label>Patient Response to Treatment</label>
                            <select name="details[patient_response]">
                                <option value="">-- Select --</option>
                                <option value="Improved">Improved</option>
                                <option value="No Change">No Change</option>
                                <option value="Worsened">Worsened</option>
                            </select>
                        </div>
                        <div class="field"><label>Current Symptoms</label><input type="text" name="details[current_symptoms_fu]"></div>
                        <div class="field full"><label>Current Findings</label><textarea name="details[current_findings]"></textarea></div>
                        <div class="field full"><label>Treatment Continuation / Modification</label><textarea name="details[treatment_continuation]"></textarea></div>
                    </div>
                </div>
            </div>
            <!-- ========== ASSESSMENT, TREATMENT & FOLLOW-UP (shared by General / NCD Follow-up / Follow-up-Recheck) ========== -->
            <div class="card type-section" id="section-common-assessment">
                <div class="card-title">Assessment, Treatment &amp; Follow-up</div>
                <div class="card-body">
                    <div class="field-grid">
                        <div class="field full"><label>Assessment / Diagnosis</label><textarea name="assessment_diagnosis">{{ old('assessment_diagnosis') }}</textarea></div>
                        <div class="field full"><label>Treatment</label><textarea name="treatment">{{ old('treatment') }}</textarea></div>
                        <div class="field full"><label>Medicines</label><textarea name="medicines">{{ old('medicines') }}</textarea></div>
                        <div class="field full"><label>Health Advice / Counseling</label><textarea name="health_advice">{{ old('health_advice') }}</textarea></div>
                        <div class="field"><label>Status / Risk Classification</label><input type="text" name="status_classification" placeholder="e.g. Risk: High, Status: Improved" value="{{ old('status_classification') }}"></div>
                        <div class="field"><label>Follow-up Date</label><input type="date" name="follow_up_date" value="{{ old('follow_up_date') }}"></div>
                        <div class="field full"><label>Referral</label><input type="text" name="referral" value="{{ old('referral') }}"></div>
                        <div class="field full"><label>Notes</label><textarea name="notes">{{ old('notes') }}</textarea></div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /#formView -->

        <div id="reviewView" class="review-box">
            <div class="card">
                <div class="card-title">Review Before Saving</div>
                <div class="card-body" id="reviewContent">
                    <!-- populated by JS -->
                </div>
            </div>
        </div>

    </div>
    <!-- /.wrap -->

    <div class="footer-bar">
        <a href="{{ route(strtolower(auth()->user()->role) . '.patient-records', ['view_patient' => $patient->id, 'tab' => 'medical-history']) }}" class="btn-secondary">Cancel</a>
        <button type="button" class="btn-secondary" id="btnBackToForm" style="display:none;">Back / Edit</button>
        <button type="button" class="btn-primary" id="btnReview">Review</button>
        <button type="submit" class="btn-primary" id="btnSubmit" style="display:none;">Save Consultation</button>
    </div>
</form>
<script>
    // ---------- Type picker: show/hide sections ----------
    const typeOptions = document.querySelectorAll('.type-option');
    const typeSections = {
        general: ['section-general', 'section-common-assessment'],
        ncd_risk_assessment: ['section-ncd_risk_assessment'],
        ncd_followup: ['section-ncd_followup', 'section-common-assessment'],
        followup_recheck: ['section-followup_recheck', 'section-common-assessment'],
    };

    function showSectionsFor(type) {
        document.querySelectorAll('.type-section').forEach(s => s.classList.remove('active'));
        (typeSections[type] || []).forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('active');
        });
    }

    typeOptions.forEach(opt => {
        opt.addEventListener('click', () => {
            typeOptions.forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');
            opt.querySelector('input[type="radio"]').checked = true;
            showSectionsFor(opt.dataset.type);
        });
    });

    // ---------- BMI auto-calculation ----------
    function recalcBmi() {
        const w = parseFloat(document.getElementById('vw').value);
        const h = parseFloat(document.getElementById('vh').value);
        const bmiField = document.getElementById('vbmi');
        if (w > 0 && h > 0) {
            const bmi = w / ((h / 100) ** 2);
            bmiField.value = bmi.toFixed(1);
        } else {
            bmiField.value = '';
        }
    }
    document.getElementById('vw').addEventListener('input', recalcBmi);
    document.getElementById('vh').addEventListener('input', recalcBmi);

    // ---------- Previous consultation diagnosis auto-fill (Follow-up/Re-check) ----------
    const prevSelect = document.getElementById('prevConsultSelect');
    if (prevSelect) {
        prevSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            document.getElementById('prevDiagnosisDisplay').value = opt.dataset.diagnosis || '(no diagnosis recorded)';
        });
    }

    // ---------- Review screen ----------
    function fieldLabel(el) {
        const field = el.closest('.field');
        return field ? field.querySelector('label')?.textContent.trim() : '';
    }
    function esc(s) {
        return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    }

    document.getElementById('btnReview').addEventListener('click', function () {
        const type = document.querySelector('input[name="consultation_type"]:checked');
        if (!type) {
            alert('Please select a Consultation Type first.');
            return;
        }

        let html = '';
        html += '<div class="review-row"><div class="k">Consultation Type</div><div class="v">' + esc(type.closest('.type-option').querySelector('.t-name').textContent) + '</div></div>';
        html += '<div class="review-row"><div class="k">Consultation Date</div><div class="v">' + esc(document.querySelector('[name="consultation_date"]').value || '-') + '</div></div>';
        html += '<div class="review-row"><div class="k">Chief Complaint</div><div class="v">' + esc(document.querySelector('[name="chief_complaint"]').value || '-') + '</div></div>';

        const bp = document.querySelector('[name="vital_bp"]').value;
        const bmi = document.getElementById('vbmi').value;
        html += '<div class="review-row"><div class="k">Vitals</div><div class="v">BP: ' + esc(bp || '-') + ' &middot; BMI: ' + esc(bmi || '-') + '</div></div>';

        // Visible type-specific fields
        document.querySelectorAll('.type-section.active input, .type-section.active select, .type-section.active textarea').forEach(el => {
            if (!el.value) return;
            const label = fieldLabel(el);
            if (!label) return;
            let val = el.value;
            if (el.tagName === 'SELECT') val = el.options[el.selectedIndex].text;
            html += '<div class="review-row"><div class="k">' + esc(label) + '</div><div class="v">' + esc(val) + '</div></div>';
        });

        document.getElementById('reviewContent').innerHTML = html || '<p style="color:#6b7280;font-size:13px;">No additional details entered.</p>';
        document.getElementById('formView').style.display = 'none';
        document.getElementById('reviewView').classList.add('active');
        document.getElementById('btnReview').style.display = 'none';
        document.getElementById('btnBackToForm').style.display = 'inline-block';
        document.getElementById('btnSubmit').style.display = 'inline-block';
        window.scrollTo(0, 0);
    });

    document.getElementById('btnBackToForm').addEventListener('click', function () {
        document.getElementById('formView').style.display = 'block';
        document.getElementById('reviewView').classList.remove('active');
        document.getElementById('btnReview').style.display = 'inline-block';
        document.getElementById('btnBackToForm').style.display = 'none';
        document.getElementById('btnSubmit').style.display = 'none';
        window.scrollTo(0, 0);
    });
</script>

</body>
</html>
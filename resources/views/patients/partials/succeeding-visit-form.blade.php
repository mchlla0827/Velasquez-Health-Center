<style>
:root {
    --primary: #2563eb;
    --primary-hover: #1d4ed8;
    --surface: #ffffff;
    --background: #f8fafc;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --border: #e2e8f0;
    --radius: 8px;
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
}

.mh-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 1.5rem;
    color: var(--text-main);
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.mh-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.mh-title { font-size: 1.5rem; font-weight: 700; margin: 0; }
.mh-subtitle { color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem; margin-bottom: 0; }

.mh-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.25rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow);
}

.mh-card-accent {
    background: #f0f9ff;
    border-color: #bae6fd;
}

.mh-card-title {
    font-size: 1rem;
    font-weight: 600;
    margin-top: 0;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border);
}

.mh-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 0;
}

.mh-summary-item dt { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
.mh-summary-item dd { font-size: 1rem; font-weight: 600; margin: 0.25rem 0 0 0; }

code {
    background: #f1f5f9;
    padding: 0.125rem 0.375rem;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.875rem;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background: #e0f2fe;
    color: #0369a1;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 4px;
}

.mh-history-body { display: flex; flex-direction: column; gap: 0.5rem; }
.mh-history-meta { display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; color: var(--text-muted); }
.mh-history-text { margin: 0; font-size: 0.875rem; }

.form-grid { display: grid; gap: 1rem; }
.form-grid-2 { grid-template-columns: repeat(2, 1fr); }
.form-grid-4 { grid-template-columns: repeat(4, 1fr); }

.form-group { display: flex; flex-direction: column; }
.form-label { font-size: 0.875rem; font-weight: 500; margin-bottom: 0.375rem; }
.form-label.required::after { content: " *"; color: #ef4444; }

.form-control {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border);
    border-radius: calc(var(--radius) - 2px);
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    background-color: #fff;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

textarea.form-control { resize: vertical; }

.readonly-input { background-color: #f1f5f9; color: var(--text-muted); cursor: not-allowed; }

.vitals-fieldset {
    border: 1px dashed var(--border);
    border-radius: var(--radius);
    padding: 1rem;
    margin: 1rem 0;
}

.vitals-legend { font-size: 0.875rem; font-weight: 600; padding: 0 0.5rem; color: var(--text-muted); }

.mh-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1.5rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: var(--radius);
    border: 1px solid transparent;
    cursor: pointer;
    transition: background-color 0.15s ease;
}

.btn-primary { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-hover); }
.btn-primary:disabled { background: #93c5fd; cursor: not-allowed; }
.btn-secondary { background: #fff; border-color: var(--border); color: var(--text-main); }
.btn-secondary:hover { background: #f1f5f9; }
.icon { width: 1rem; height: 1rem; }

.mh-container .mh-form-section {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.25rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow);
}

.mh-container .mh-section-header {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.25rem;
}

.mh-container .mh-section-header > div:first-child {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.mh-container .mh-section-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 2rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #eff6ff;
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 700;
}

.mh-container .mh-section-header h5 {
    margin: 0;
    color: var(--text-main);
    font-size: 1rem;
    font-weight: 700;
}

.mh-container .mh-section-header p {
    margin: 0.2rem 0 0;
    color: var(--text-muted);
    font-size: 0.8rem;
}

.mh-container .mh-form-grid {
    display: grid;
    gap: 1rem;
}

.mh-container .mh-grid-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.mh-container .mh-form-field {
    display: flex;
    flex-direction: column;
    min-width: 0;
    color: var(--text-main);
    font-size: 0.8125rem;
    font-weight: 600;
}

.mh-container .mh-form-field > span {
    margin-bottom: 0.4rem;
}

.mh-container .mh-form-field em {
    color: #dc2626;
    font-style: normal;
}

.mh-container .mh-form-field input,
.mh-container .mh-form-field select,
.mh-container .mh-form-field textarea {
    width: 100%;
    box-sizing: border-box;
    min-height: 2.5rem;
    padding: 0.6rem 0.75rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: #fff;
    color: var(--text-main);
    font: inherit;
    font-weight: 400;
    line-height: 1.4;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.mh-container .mh-form-field textarea {
    min-height: 5.25rem;
    resize: vertical;
}

.mh-container .mh-form-field input::placeholder,
.mh-container .mh-form-field textarea::placeholder {
    color: #94a3b8;
}

.mh-container .mh-form-field input:focus,
.mh-container .mh-form-field select:focus,
.mh-container .mh-form-field textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.mh-container .mh-field-full {
    grid-column: 1 / -1;
    margin-top: 1rem;
}

.mh-container .mh-subsection-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 1.25rem 0 0.75rem;
    color: var(--text-muted);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.mh-container .mh-subsection-title::after {
    content: "";
    height: 1px;
    flex: 1;
    background: var(--border);
}

.mh-container .mh-vitals-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.85rem;
}

.mh-container .mh-input-unit {
    display: flex;
    align-items: stretch;
}

.mh-container .mh-input-unit input {
    min-width: 0;
    border-radius: 6px 0 0 6px;
}

.mh-container .mh-input-unit small {
    display: inline-flex;
    align-items: center;
    padding: 0 0.6rem;
    border: 1px solid var(--border);
    border-left: 0;
    border-radius: 0 6px 6px 0;
    background: #f8fafc;
    color: var(--text-muted);
    font-size: 0.72rem;
    white-space: nowrap;
}

.mh-container .mh-input-readonly input {
    background: #f1f5f9;
    color: var(--text-muted);
    cursor: not-allowed;
}

.mh-container .mh-reference-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
    padding: 1rem 1.25rem;
    border: 1px solid #bae6fd;
    border-left: 4px solid #0284c7;
    border-radius: var(--radius);
    background: #f0f9ff;
}

.mh-container .mh-reference-date {
    color: #0369a1;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.mh-container .mh-reference-card h6 {
    margin: 0.25rem 0;
    color: var(--text-main);
    font-size: 0.95rem;
}

.mh-container .mh-reference-card p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.8125rem;
}

.mh-container .mh-reference-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    margin-top: 0.75rem;
}

.mh-container .mh-reference-stats span {
    display: block;
    color: var(--text-muted);
    font-size: 0.7rem;
    text-transform: uppercase;
}

.mh-container .mh-reference-stats strong {
    display: block;
    margin-top: 0.15rem;
    color: var(--text-main);
    font-size: 0.9rem;
}

.mh-container .mh-monitoring-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.mh-container .mh-monitoring-card {
    padding: 1rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: #f8fafc;
}

.mh-container .mh-monitoring-card-head {
    margin-bottom: 1rem;
}

.mh-container .mh-monitoring-card-head > div {
    display: flex;
    align-items: center;
    gap: 0.65rem;
}

.mh-container .mh-monitoring-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 6px;
    background: #dbeafe;
    color: #1d4ed8;
    font-size: 0.7rem;
    font-weight: 800;
}

.mh-container .mh-monitoring-card strong,
.mh-container .mh-monitoring-card small {
    display: block;
}

.mh-container .mh-monitoring-card small {
    margin-top: 0.15rem;
    color: var(--text-muted);
    font-size: 0.72rem;
    font-weight: 400;
}

.mh-container .mh-form-actions {
    padding-top: 0.25rem;
}

/* Hide dynamic sections initially to prevent flash of content before JS runs */
.mh-container [data-visit-types],
.mh-container [data-reasons] {
    display: none;
}

@media (max-width: 900px) {
    .mh-container .mh-vitals-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 768px) {
    .form-grid-2, .form-grid-4,
    .mh-container .mh-grid-2,
    .mh-container .mh-monitoring-grid,
    .mh-container .mh-vitals-grid { grid-template-columns: 1fr; }
    .mh-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    .mh-container .mh-reference-card { align-items: flex-start; flex-direction: column; }
    .mh-container .mh-field-full { grid-column: auto; }
}
</style>

<div class="mh-container">
    <header class="mh-header">
        <div>
            <h1 class="mh-title">Record Succeeding Visit</h1>
            <p class="mh-subtitle">Document medical findings and updates for this session.</p>
        </div>
        <button type="button" class="btn btn-secondary" id="btnBackHeader">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back
        </button>
    </header>

    <form id="succeedingVisitForm" class="mh-form" novalidate>
        @csrf

        <section class="mh-card">
            <h2 class="mh-card-title">Patient Summary</h2>
            <dl class="mh-summary-grid">
                <div class="mh-summary-item">
                    <dt>Patient Name</dt>
                    <dd>{{ trim("{$patient->first_name} {$patient->middle_name} {$patient->last_name}") }}</dd>
                </div>
                <div class="mh-summary-item">
                    <dt>Patient ID</dt>
                    <dd><code>{{ $patient->patient_id }}</code></dd>
                </div>
                <div class="mh-summary-item">
                    <dt>Age / Gender</dt>
                    <dd>{{ $patient->age }} yrs &bull; {{ ucfirst($patient->gender) }}</dd>
                </div>
            </dl>
        </section>

        {{-- Latest Recorded History: shown only for follow-up reasons --}}
        @if($assessment || $previousVisit)
            <section class="mh-card mh-card-accent" data-visit-types="consultation" data-reasons="NCD Follow-up,Follow-up / Re-check">
                <h2 class="mh-card-title">Latest Recorded History</h2>
                @if($previousVisit)
                    <div class="mh-history-body">
                        <div class="mh-history-meta">
                            <span class="badge">{{ str_replace('_', ' ', ucwords($previousVisit->consultation_type, '_')) }}</span>
                            <time>{{ $previousVisit->consultation_date->format('M d, Y') }}</time>
                        </div>
                        <p class="mh-history-text"><strong>Assessment:</strong> {{ $previousVisit->assessment }}</p>
                    </div>
                @else
                    <div class="mh-history-body">
                        <div class="mh-history-meta">
                            <span class="badge">Initial NCD Risk Assessment</span>
                            <time>{{ $assessment->assessment_date ?: 'Undated' }}</time>
                        </div>
                        <p class="mh-history-text">
                            <strong>BP:</strong> {{ $assessment->bp_b ?: '---' }} &ensp;|&ensp; 
                            <strong>FBS:</strong> {{ $assessment->fbs ?: '---' }} &ensp;|&ensp; 
                            <strong>BMI:</strong> {{ $assessment->bmi ?: '---' }}
                        </p>
                    </div>
                @endif
            </section>
        @endif

        {{-- Visit Information: always visible — this is the trigger step --}}
        <section class="mh-form-section">
            <div class="mh-section-header">
                <div>
                    <span class="mh-section-number">03</span>
                    <div>
                        <h5>Visit Information</h5>
                        <p>Identify when and why the patient is being seen.</p>
                    </div>
                </div>
            </div>

            <div class="mh-form-grid mh-grid-2">
                <label class="mh-form-field">
                    <span>Visit Date <em>*</em></span>
                    <input
                        type="datetime-local"
                        id="consultationDate"
                        name="consultation_date"
                        required
                        value="{{ now()->format('Y-m-d\TH:i') }}">
                </label>

                <label class="mh-form-field">
                    <span>Attending Provider <em>*</em></span>
                    <select id="attendedBy" name="attended_by" required>
                        <option value="">Select provider...</option>
                        @foreach($providers as $provider)
                            <option
                                value="{{ $provider->id }}"
                                @selected($provider->id === auth()->id())>
                                {{ $provider->name }} ({{ ucfirst($provider->role) }})
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="mh-form-field">
                    <span>Visit Type <em>*</em></span>
                    <select id="visitType" name="consultation_type" required>
                        <option value="">Select visit type...</option>
                        <option value="consultation">Consultation</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="ecg">ECG</option>
                        <option value="ultrasound">Ultrasound</option>
                        <option value="dental">Dental</option>
                        <option value="laboratory">Laboratory</option>
                        <option value="counseling_adolescent">Counseling Adolescent</option>
                    </select>
                </label>

                <label class="mh-form-field" data-visit-types="consultation">
                    <span>Reason for Visit <em>*</em></span>
                    <select id="reasonForVisit" name="reason_for_visit" required>
                        <option value="">Select reason...</option>
                    </select>
                </label>

                <label class="mh-form-field" data-visit-types="laboratory">
                    <span>Laboratory Service <em>*</em></span>
                    <select id="laboratoryService" name="details[laboratory_service]" required>
                        <option value="">Select laboratory service...</option>
                        <option value="Blood Extraction">Blood Extraction</option>
                        <option value="Sputum Exam">Sputum Exam</option>
                        <option value="Tuberculosis Program">Tuberculosis Program</option>
                    </select>
                </label>
            </div>
        </section>

        <section class="mh-form-section mh-service-section" data-visit-types="maintenance">
            <div class="mh-section-header"><div><span class="mh-section-number">04</span><div><h5>Maintenance Care</h5><p>Record routine maintenance and continuing care.</p></div></div></div>
            <div class="mh-form-grid mh-grid-2"><label class="mh-form-field"><span>Condition Being Maintained</span><textarea name="details[maintenance_condition]" rows="2"></textarea></label><label class="mh-form-field"><span>Current Status</span><textarea name="details[maintenance_status]" rows="2"></textarea></label><label class="mh-form-field"><span>Vital Signs</span><textarea name="details[maintenance_vitals]" rows="2" placeholder="BP, temperature, pulse, or other relevant values"></textarea></label><label class="mh-form-field"><span>Medication / Maintenance Given</span><textarea name="details[maintenance_given]" rows="2"></textarea></label><label class="mh-form-field"><span>Instructions</span><textarea name="details[maintenance_instructions]" rows="2"></textarea></label><label class="mh-form-field"><span>Next Visit</span><input type="date" name="follow_up_date"></label><label class="mh-form-field mh-field-full"><span>Notes</span><textarea name="details[notes]" rows="3"></textarea></label></div>
        </section>

        <section class="mh-form-section mh-service-section" data-visit-types="ecg">
            <div class="mh-section-header"><div><span class="mh-section-number">04</span><div><h5>ECG Record</h5><p>Document the ECG service and its clinical interpretation.</p></div></div></div>
            <div class="mh-form-grid mh-grid-2"><label class="mh-form-field"><span>ECG Performed</span><select name="details[ecg_performed]"><option value="">Select</option><option>Yes</option><option>No</option></select></label><label class="mh-form-field"><span>ECG Result / Findings</span><textarea name="details[ecg_findings]" rows="3"></textarea></label><label class="mh-form-field"><span>Impression</span><textarea name="details[ecg_impression]" rows="3"></textarea></label><label class="mh-form-field"><span>Recommendation</span><textarea name="details[ecg_recommendation]" rows="3"></textarea></label><label class="mh-form-field"><span>Follow-up Date</span><input type="date" name="follow_up_date"></label><label class="mh-form-field"><span>Notes</span><textarea name="details[notes]" rows="3"></textarea></label></div>
        </section>

        <section class="mh-form-section mh-service-section" data-visit-types="ultrasound">
            <div class="mh-section-header"><div><span class="mh-section-number">04</span><div><h5>Ultrasound Record</h5><p>Document the ultrasound service and report summary.</p></div></div></div>
            <div class="mh-form-grid mh-grid-2"><label class="mh-form-field"><span>Type of Ultrasound</span><input name="details[ultrasound_type]"></label><label class="mh-form-field"><span>Findings</span><textarea name="details[ultrasound_findings]" rows="3"></textarea></label><label class="mh-form-field"><span>Impression</span><textarea name="details[ultrasound_impression]" rows="3"></textarea></label><label class="mh-form-field"><span>Recommendation</span><textarea name="details[ultrasound_recommendation]" rows="3"></textarea></label><label class="mh-form-field"><span>Follow-up Date</span><input type="date" name="follow_up_date"></label><label class="mh-form-field"><span>Notes</span><textarea name="details[notes]" rows="3"></textarea></label></div>
        </section>

        <section class="mh-form-section mh-service-section" data-visit-types="dental">
            <div class="mh-section-header"><div><span class="mh-section-number">04</span><div><h5>Dental Record</h5><p>Document dental findings, treatment, and advice.</p></div></div></div>
            <div class="mh-form-grid mh-grid-2"><label class="mh-form-field"><span>Dental Findings</span><textarea name="details[dental_findings]" rows="3"></textarea></label><label class="mh-form-field"><span>Procedure / Treatment</span><textarea name="details[dental_treatment]" rows="3"></textarea></label><label class="mh-form-field"><span>Medication / Prescription</span><textarea name="medicines" rows="3"></textarea></label><label class="mh-form-field"><span>Advice</span><textarea name="details[dental_advice]" rows="3"></textarea></label><label class="mh-form-field"><span>Follow-up Date</span><input type="date" name="follow_up_date"></label><label class="mh-form-field"><span>Notes</span><textarea name="details[notes]" rows="3"></textarea></label></div>
        </section>

        <section class="mh-form-section mh-service-section" data-visit-types="laboratory">
            <div class="mh-section-header"><div><span class="mh-section-number">04</span><div><h5>Laboratory Record</h5><p>Record the selected laboratory service and result.</p></div></div></div>
            <div class="mh-form-grid mh-grid-2"><label class="mh-form-field"><span>Result</span><textarea name="details[laboratory_result]" rows="3"></textarea></label><label class="mh-form-field"><span>Interpretation</span><textarea name="details[laboratory_interpretation]" rows="3"></textarea></label><label class="mh-form-field"><span>Recommendation</span><textarea name="details[laboratory_recommendation]" rows="3"></textarea></label><label class="mh-form-field"><span>Notes</span><textarea name="details[notes]" rows="3"></textarea></label></div>
        </section>

        <section class="mh-form-section mh-service-section" data-visit-types="counseling_adolescent">
            <div class="mh-section-header"><div><span class="mh-section-number">04</span><div><h5>Adolescent Counseling</h5><p>Document the counseling encounter and health education.</p></div></div></div>
            <div class="mh-form-grid mh-grid-2"><label class="mh-form-field"><span>Reason for Counseling</span><textarea name="details[counseling_reason]" rows="2"></textarea></label><label class="mh-form-field"><span>Main Concern</span><textarea name="details[counseling_concern]" rows="2"></textarea></label><label class="mh-form-field"><span>Assessment</span><textarea name="details[counseling_assessment]" rows="3"></textarea></label><label class="mh-form-field"><span>Counseling / Intervention Provided</span><textarea name="details[counseling_intervention]" rows="3"></textarea></label><label class="mh-form-field"><span>Advice / Health Education</span><textarea name="details[counseling_advice]" rows="3"></textarea></label><label class="mh-form-field"><span>Follow-up Date</span><input type="date" name="follow_up_date"></label><label class="mh-form-field mh-field-full"><span>Notes</span><textarea name="details[notes]" rows="3"></textarea></label></div>
        </section>

        @if($assessment || $previousVisit)
            <div class="mh-reference-card" data-visit-types="consultation" data-reasons="NCD Follow-up,Follow-up / Re-check">
                <div class="mh-reference-main">
                    @if($previousVisit)
                        <div class="mh-reference-date">
                            {{ $previousVisit->consultation_date?->format('F d, Y') ?? 'Undated' }}
                        </div>
                        <h6>
                            {{ str_replace('_', ' ', ucwords($previousVisit->consultation_type, '_')) }}
                        </h6>
                        @if($previousVisit->assessment)
                            <p>
                                <strong>Previous Assessment:</strong>
                                {{ $previousVisit->assessment }}
                            </p>
                        @endif
                    @elseif($assessment)
                        <div class="mh-reference-date">
                            {{ $assessment->assessment_date ?: 'Undated' }}
                        </div>
                        <h6>Initial NCD Risk Assessment</h6>
                        <div class="mh-reference-stats">
                            <div>
                                <span>Blood Pressure</span>
                                <strong>{{ $assessment->bp_b ?: '---' }}</strong>
                            </div>
                            <div>
                                <span>FBS</span>
                                <strong>{{ $assessment->fbs ?: '---' }}</strong>
                            </div>
                            <div>
                                <span>BMI</span>
                                <strong>{{ $assessment->bmi ?: '---' }}</strong>
                            </div>
                        </div>
                    @endif
                </div>

                @if($assessment)
                    <div class="mh-reference-action">
                        <button
                            type="button"
                            class="btn-secondary btn-sm"
                            onclick="viewFullNcdAssessment({{ $assessment->id }})">
                            View Previous Assessment
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <section class="mh-form-section" data-visit-types="consultation">
            <div class="mh-section-header">
                <div>
                    <span class="mh-section-number">04</span>
                    <div>
                        <h5>Clinical Presentation &amp; Vitals</h5>
                        <p>Record symptoms, observations, and current vital signs.</p>
                    </div>
                </div>
            </div>

            <div class="mh-form-grid mh-grid-2">
                <label class="mh-form-field">
                    <span>Chief Complaint <em>*</em></span>
                    <textarea
                        id="chiefComplaint"
                        name="chief_complaint"
                        rows="3"
                        required
                        placeholder="Primary reason for seeking care..."></textarea>
                </label>

                <label class="mh-form-field" data-reasons="NCD Follow-up,Follow-up / Re-check">
                    <span>Changes Since Last Visit</span>
                    <textarea
                        id="changesSinceLastVisit"
                        name="details[changes_since_last_visit]"
                        rows="3"
                        placeholder="Symptom updates, medication tolerance..."></textarea>
                </label>
            </div>

            <div class="mh-subsection-title">
                <span>Vital Signs</span>
            </div>

            <div class="mh-vitals-grid">
                <label class="mh-form-field">
                    <span>Blood Pressure</span>
                    <div class="mh-input-unit">
                        <input type="text" id="bloodPressure" name="blood_pressure" placeholder="120/80">
                        <small>mmHg</small>
                    </div>
                </label>

                <label class="mh-form-field">
                    <span>Temperature</span>
                    <div class="mh-input-unit">
                        <input type="number" step="0.1" id="temperature" name="temperature" placeholder="36.5">
                        <small>°C</small>
                    </div>
                </label>

                <label class="mh-form-field">
                    <span>Pulse Rate</span>
                    <div class="mh-input-unit">
                        <input type="number" id="pulseRate" name="pulse_rate" placeholder="72">
                        <small>bpm</small>
                    </div>
                </label>

                <label class="mh-form-field">
                    <span>Respiratory Rate</span>
                    <div class="mh-input-unit">
                        <input type="number" id="respiratoryRate" name="respiratory_rate" placeholder="16">
                        <small>/min</small>
                    </div>
                </label>

                <label class="mh-form-field">
                    <span>SpO₂</span>
                    <div class="mh-input-unit">
                        <input type="number" step="0.1" id="oxygenSaturation" name="oxygen_saturation" placeholder="98">
                        <small>%</small>
                    </div>
                </label>

                <label class="mh-form-field">
                    <span>Weight</span>
                    <div class="mh-input-unit">
                        <input type="number" step="0.01" id="visitWeight" name="weight" placeholder="70.0">
                        <small>kg</small>
                    </div>
                </label>

                <label class="mh-form-field">
                    <span>Height</span>
                    <div class="mh-input-unit">
                        <input type="number" step="0.01" id="visitHeight" name="height" placeholder="170.0">
                        <small>cm</small>
                    </div>
                </label>

                <label class="mh-form-field">
                    <span>BMI</span>
                    <div class="mh-input-unit mh-input-readonly">
                        <input type="text" id="visitBmi" name="bmi" readonly placeholder="Auto">
                        <small>kg/m²</small>
                    </div>
                </label>
            </div>

            <label class="mh-form-field mh-field-full">
                <span>Physical Examination &amp; Findings</span>
                <textarea
                    id="currentFindings"
                    name="details[current_findings]"
                    rows="3"
                    placeholder="Objective physical examination observations..."></textarea>
            </label>
        </section>

        {{-- NCD Monitoring: only during Consultation + Reason = NCD Follow-up --}}
        <section class="mh-form-section" data-visit-types="consultation" data-reasons="NCD Follow-up">
            <div class="mh-section-header">
                <div>
                    <span class="mh-section-number">05</span>
                    <div>
                        <h5>NCD Monitoring &amp; Management</h5>
                        <p>Monitor specific chronic condition parameters and medication responses.</p>
                    </div>
                </div>
            </div>

            <div class="mh-monitoring-grid">
                @if($assessment && $assessment->is_hypertensive)
                    <div class="mh-monitoring-card">
                        <div class="mh-monitoring-card-head">
                            <div>
                                <span class="mh-monitoring-icon">BP</span>
                                <div>
                                    <strong>Hypertension</strong>
                                    <small>Blood pressure monitoring</small>
                                </div>
                            </div>
                        </div>

                        <label class="mh-form-field">
                            <span>Current Status</span>
                            <select id="hypertensionStatus" name="details[hypertension_status]">
                                <option value="">Select status</option>
                                <option value="Controlled">Controlled</option>
                                <option value="Improved">Improved</option>
                                <option value="Uncontrolled">Uncontrolled</option>
                            </select>
                        </label>
                    </div>
                @endif

                @if($assessment && $assessment->is_diabetic)
                    <div class="mh-monitoring-card">
                        <div class="mh-monitoring-card-head">
                            <div>
                                <span class="mh-monitoring-icon">BS</span>
                                <div>
                                    <strong>Diabetes</strong>
                                    <small>Blood sugar monitoring</small>
                                </div>
                            </div>
                        </div>

                        <label class="mh-form-field">
                            <span>Current Blood Sugar</span>
                            <div class="mh-input-unit">
                                <input type="number" step="0.01" id="currentBloodSugar" name="details[current_blood_sugar]" placeholder="Enter value">
                                <small>mg/dL</small>
                            </div>
                        </label>

                        <label class="mh-form-field">
                            <span>Current Status</span>
                            <select id="diabetesStatus" name="details[diabetes_status]">
                                <option value="">Select status</option>
                                <option value="Controlled">Controlled</option>
                                <option value="Improved">Improved</option>
                                <option value="Uncontrolled">Uncontrolled</option>
                            </select>
                        </label>
                    </div>
                @endif
            </div>

            <div class="mh-subsection-title">
                <span>Medication Monitoring</span>
            </div>

            <div class="mh-form-grid mh-grid-2">
                <label class="mh-form-field">
                    <span>Medication Compliance</span>
                    <select id="medicationCompliance" name="details[medication_compliance]">
                        <option value="">Select compliance</option>
                        <option value="Good">Good (Adherent)</option>
                        <option value="Fair">Fair (Partially Adherent)</option>
                        <option value="Poor">Poor (Non-adherent)</option>
                    </select>
                </label>

                <label class="mh-form-field">
                    <span>Medication Action</span>
                    <select id="medicationAction" name="details[medication_action]">
                        <option value="">Select action</option>
                        <option value="Continue">Continue Current Dose</option>
                        <option value="Change">Adjust Dosage / Change</option>
                        <option value="Stop">Discontinue</option>
                        <option value="Add Medication">Add New Medication</option>
                    </select>
                </label>
            </div>
        </section>

        <section class="mh-form-section" data-visit-types="consultation">
            <div class="mh-section-header">
                <div>
                    <span class="mh-section-number">06</span>
                    <div>
                        <h5>Assessment &amp; Plan</h5>
                        <p>Document overall clinical impression, prescriptions, and instructions.</p>
                    </div>
                </div>
            </div>

            <label class="mh-form-field mh-field-full">
                    <span>Primary Diagnosis / Clinical Impression</span>
                <textarea
                    id="assessment"
                    name="assessment"
                    rows="3"
                    placeholder="Enter primary diagnosis or clinical impression..."></textarea>
            </label>

            <div class="mh-form-grid mh-grid-2">
                <label class="mh-form-field" data-reasons="NCD Follow-up,Follow-up / Re-check">
                    <span>Condition Status Summary</span>
                    <textarea id="conditionStatus" name="details[condition_status]" rows="2"></textarea>
                </label>

                <label class="mh-form-field">
                    <span>Additional Clinical Notes</span>
                    <textarea id="clinicalFindings" name="details[clinical_findings]" rows="2"></textarea>
                </label>

                <label class="mh-form-field">
                    <span>Treatment &amp; Orders</span>
                    <textarea id="treatment" name="treatment" rows="2"></textarea>
                </label>

                <label class="mh-form-field">
                    <span>Health Advice &amp; Counseling</span>
                    <textarea id="healthAdvice" name="details[health_advice]" rows="2"></textarea>
                </label>

                <label class="mh-form-field">
                    <span>Lab / Diagnostic Orders</span>
                    <textarea id="laboratoryRequest" name="details[laboratory_request]" rows="2"></textarea>
                </label>

                <label class="mh-form-field">
                    <span>Referral / Specialist Details</span>
                    <textarea id="referral" name="details[referral]" rows="2"></textarea>
                </label>
            </div>

            <label class="mh-form-field mh-field-full">
                <span>Prescribed Medications</span>
                <textarea
                    id="medicines"
                    name="medicines"
                    rows="2"
                    placeholder="List medications, dosages, and instructions..."></textarea>
            </label>

            <div class="mh-form-grid mh-grid-2">
                {{-- Follow-up Date: base Consultation field, no longer reason-gated --}}
                <label class="mh-form-field">
                    <span>Next Follow-Up Date</span>
                    <input type="date" id="followUpDate" name="follow_up_date">
                </label>

                <label class="mh-form-field">
                    <span>Administrative / General Notes</span>
                    <textarea id="notes" name="details[notes]" rows="2"></textarea>
                </label>
            </div>
        </section>

        <div class="mh-form-actions">
            <button type="button" class="btn-secondary" id="btnBackFooter">
                Back / Cancel
            </button>
            <button type="button" class="btn-primary" id="btnReview" disabled>
                Review Session →
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    function initSucceedingVisitForm() {
        const visitTypeSelect = document.getElementById('visitType');
        const reasonSelect = document.getElementById('reasonForVisit');
        const form = document.getElementById('succeedingVisitForm');

        if (!visitTypeSelect || !form) return;

        if (form.dataset.svInitialized === 'true') return;
        form.dataset.svInitialized = 'true';

        const dynamicElements = form.querySelectorAll('[data-visit-types]');
        const reasonElements = form.querySelectorAll('[data-reasons]');
        const weightInput = document.getElementById('visitWeight');
        const heightInput = document.getElementById('visitHeight');
        const bmiInput = document.getElementById('visitBmi');
        const btnReview = document.getElementById('btnReview');

        const consultationReasons = [
            'NCD Follow-up',
            'General Consultation',
            'Follow-up / Re-check',
            'New Complaint',
            'Medication Refill',
            'Monitoring'
        ];

        // Rebuilds the Reason <select>'s option list. Only called on Visit
        // Type change — never on a Reason change — so picking a reason
        // never resets the value the user just chose.
        function updateReasonOptions(selectedType) {
            if (!reasonSelect) return;

            reasonSelect.innerHTML = '<option value="">Select reason...</option>';
            const options = selectedType === 'consultation' ? consultationReasons : [];

            options.forEach(reason => {
                const opt = document.createElement('option');
                opt.value = reason;
                opt.textContent = reason;
                reasonSelect.appendChild(opt);
            });
        }

        // Show/hide an element; disable/enable + require/un-require its
        // descendant inputs, so hidden fields are excluded from submission
        // and never enforce "required" validation.
        function setElementState(element, visible) {
            element.style.display = visible ? '' : 'none';
            element.querySelectorAll('input, select, textarea').forEach(input => {
                input.disabled = !visible;
                if (visible) {
                    if (input.dataset.wasRequired === 'true') input.required = true;
                } else {
                    if (input.required) input.dataset.wasRequired = 'true';
                    input.required = false;
                }
            });
        }

        // Elements can carry data-visit-types alone, data-reasons alone, or
        // both together — an element with both must satisfy both
        // conditions to be shown.
        function applyVisibility() {
            const selectedType = visitTypeSelect.value;
            const selectedReason = reasonSelect ? reasonSelect.value : '';

            dynamicElements.forEach(element => {
                const rawTypes = element.getAttribute('data-visit-types') || '';
                const allowedTypes = rawTypes.split(/\s+/).filter(Boolean);

                let isTypeMatch = selectedType !== '' && allowedTypes.includes(selectedType);
                let isReasonMatch = true;

                if (element.hasAttribute('data-reasons')) {
                    const rawReasons = element.getAttribute('data-reasons') || '';
                    const allowedReasons = rawReasons.split(',').map(r => r.trim());
                    isReasonMatch = allowedReasons.includes(selectedReason);
                }

                setElementState(element, isTypeMatch && isReasonMatch);
            });

            // Elements gated only by data-reasons (no data-visit-types)
            reasonElements.forEach(element => {
                if (element.hasAttribute('data-visit-types')) return;

                const rawReasons = element.getAttribute('data-reasons') || '';
                const allowedReasons = rawReasons.split(',').map(r => r.trim());
                const visible = selectedType === 'consultation' && allowedReasons.includes(selectedReason);
                setElementState(element, visible);
            });

            updateReviewButtonState();
        }

        // Runs only on a Visit Type change: rebuild Reason options (a prior
        // Reason is invalid under a different Visit Type), then re-apply
        // visibility for everything.
        function handleVisitTypeChange() {
            updateReasonOptions(visitTypeSelect.value);
            applyVisibility();
        }

        // Enable Review Session only once Visit Type is chosen and every
        // currently-visible required field is filled.
        function updateReviewButtonState() {
            if (!btnReview) return;

            const hasVisitType = !!visitTypeSelect.value;
            const visibleRequiredValid = Array.from(
                form.querySelectorAll('[required]:not(:disabled)')
            ).every(field => field.value.trim() !== '');

            btnReview.disabled = !(hasVisitType && visibleRequiredValid);
        }

        function calculateBMI() {
            const weight = parseFloat(weightInput?.value);
            const height = parseFloat(heightInput?.value);

            if (weight > 0 && height > 0) {
                const heightInMeters = height / 100;
                const bmi = weight / (heightInMeters ** 2);
                if (bmiInput) bmiInput.value = bmi.toFixed(1);
            } else if (bmiInput) {
                bmiInput.value = '';
            }
        }

        visitTypeSelect.addEventListener('change', handleVisitTypeChange);
        reasonSelect?.addEventListener('change', applyVisibility);
        weightInput?.addEventListener('input', calculateBMI);
        heightInput?.addEventListener('input', calculateBMI);

        form.addEventListener('input', updateReviewButtonState);
        form.addEventListener('change', updateReviewButtonState);

        document.getElementById('btnBackHeader')?.addEventListener('click', () => {
            if (typeof loadMedicalHistory === 'function') loadMedicalHistory();
        });

        document.getElementById('btnBackFooter')?.addEventListener('click', () => {
            if (typeof loadMedicalHistory === 'function') loadMedicalHistory();
        });

        btnReview?.addEventListener('click', () => {
            if (btnReview.disabled) return;
            if (typeof reviewSucceedingVisit === 'function') reviewSucceedingVisit();
        });

        // Run immediately on init: only Visit Information (+ Patient
        // Summary, Latest Recorded History, Back/Review) is visible.
        handleVisitTypeChange();
        calculateBMI();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSucceedingVisitForm);
    } else {
        // Fragment was injected after DOMContentLoaded already fired —
        // run immediately, since the markup is already in the DOM.
        initSucceedingVisitForm();
    }

    // Safety net: if this fragment was inserted via element.innerHTML,
    // <script> tags in it won't auto-execute. The caller can invoke
    // window.initSucceedingVisitForm() right after inserting the markup.
    window.initSucceedingVisitForm = initSucceedingVisitForm;
})();
</script>
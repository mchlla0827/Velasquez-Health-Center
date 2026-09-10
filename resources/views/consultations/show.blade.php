<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Details - {{ $consultation->patient->first_name ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #0f766e;
            --brand-primary-hover: #115e59;
            --brand-tint: #f0fdfa;
            --brand-accent: #0284c7;

            --bg-base: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            
            --text-title: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --text-subtle: #94a3b8;

            --badge-green-bg: #ecfdf5;
            --badge-green-text: #047857;
            --badge-green-border: #a7f3d0;

            --badge-amber-bg: #fffbeb;
            --badge-amber-text: #b45309;
            --badge-amber-border: #fde68a;

            --badge-rose-bg: #fff1f2;
            --badge-rose-text: #be123c;
            --badge-rose-border: #fecdd3;
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        * { box-sizing: border-box; }
        
        body { 
            margin: 0; 
            padding: 0; 
            background: var(--bg-base); 
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; 
            font-size: 13.5px; 
            color: var(--text-body); 
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Navigation Bar */
        .workspace-nav {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            position: sticky;
            top: 0;
            z-index: 30;
        }
        .nav-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .nav-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: var(--brand-tint);
            border: 1px solid #ccfbf1;
            color: var(--brand-primary);
            font-size: 12px;
            font-weight: 700;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--brand-primary);
        }
        .workspace-nav h1 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            color: var(--text-title);
            letter-spacing: -0.02em;
        }

        /* 2-Column Responsive Layout */
        .page-container {
            max-width: 1400px;
            margin: 24px auto 100px;
            padding: 0 28px;
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 24px;
            align-items: start;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Patient Identity Card */
        .patient-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            border-top: 4px solid var(--brand-primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .patient-avatar-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
        }
        .patient-avatar {
            width: 44px;
            height: 44px;
            background: #e0f2fe;
            color: var(--brand-accent);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
        }
        .patient-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-title);
            line-height: 1.2;
        }
        .patient-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 12px;
            background: #f8fafc;
            border-radius: var(--radius-sm);
        }
        .meta-item { display: flex; flex-direction: column; }
        .meta-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; color: var(--text-subtle); letter-spacing: 0.5px; }
        .meta-value { font-size: 12.5px; font-weight: 600; color: var(--text-title); margin-top: 2px; }

        /* Unified Card Panels */
        .section-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .panel-header {
            padding: 14px 20px;
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .panel-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-title);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .panel-body { padding: 20px; }

        /* Vitals Tile Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 14px;
        }
        .metric-tile {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
        }
        .metric-tile .label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.4px; }
        .metric-tile .val { font-size: 14px; font-weight: 700; color: var(--text-title); margin-top: 3px; }

        /* Detail List Rows */
        .record-rows { display: flex; flex-direction: column; }
        .record-row {
            display: flex;
            padding: 11px 0;
            border-bottom: 1px solid #f1f5f9;
            align-items: flex-start;
        }
        .record-row:first-child { padding-top: 0; }
        .record-row:last-child { border-bottom: none; padding-bottom: 0; }
        .record-key {
            flex: 0 0 175px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .record-val {
            flex: 1;
            font-size: 13.5px;
            color: var(--text-title);
        }
        .record-val.empty {
            color: var(--text-subtle);
            font-style: italic;
        }

        /* Diagnosis Highlight Box */
        .diagnosis-callout {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            margin-bottom: 18px;
        }
        .diagnosis-callout .label {
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #15803d;
            margin-bottom: 4px;
            display: block;
        }
        .diagnosis-callout .text {
            font-size: 15px;
            font-weight: 700;
            color: #14532d;
            margin: 0;
        }

        /* Content Bubble */
        .info-bubble {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: var(--radius-sm);
            padding: 12px;
            margin-top: 10px;
        }
        .info-bubble label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .info-bubble p {
            margin: 0;
            font-size: 13px;
            color: var(--text-title);
        }

        /* Main Workspace Container */
        .workspace {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Prescription Table */
        .table-responsive {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            overflow-x: auto;
            background: #ffffff;
        }
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .styled-table thead th {
            background: #f8fafc;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            white-space: nowrap;
        }
        .styled-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-body);
        }
        .styled-table tbody tr:last-child td { border-bottom: none; }
        .styled-table tbody tr:hover { background: #fafbfc; }

        /* Links & Interactive Elements */
        .badge-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--brand-accent);
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: color 0.15s ease;
        }
        .badge-link:hover { color: #0369a1; text-decoration: underline; }

        /* Floating Bottom Shelf */
        .bottom-shelf {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-top: 1px solid var(--border);
            padding: 12px 28px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            z-index: 40;
        }
        .btn {
            font-size: 13px;
            font-weight: 700;
            border-radius: var(--radius-sm);
            padding: 9px 20px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-ghost {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-body);
        }
        .btn-ghost:hover { background: #f1f5f9; }

        .empty-slate {
            padding: 16px;
            background: #f8fafc;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-size: 12.5px;
            text-align: center;
            border: 1px dashed var(--border);
        }

        @media (max-width: 1024px) {
            .page-container { grid-template-columns: 1fr; }
            .record-row { flex-direction: column; gap: 4px; }
            .record-key { flex: auto; }
        }
    </style>
</head>
<body>

@php
    $val = fn($v) => $v !== null && $v !== '' ? $v : null;
@endphp

<header class="workspace-nav">
    <div class="nav-inner">
        <div class="nav-title-wrap">
            <span class="status-pill"><span class="status-dot"></span> {{ $consultation->type_label }}</span>
            <h1>Consultation Details</h1>
        </div>
        <div style="font-size: 12.5px; color: var(--text-muted);">
            Attended by: <b style="color: var(--text-title);">{{ optional($consultation->attendedBy)->name ?? 'Unknown' }}</b>
        </div>
    </div>
</header>

<div class="page-container">

    <!-- ================= LEFT COLUMN: SUMMARY & TRIAGE DATA ================= -->
    <aside class="sidebar">

        <!-- Patient Profile Card -->
        <div class="patient-card">
            <div class="patient-avatar-wrap">
                <div class="patient-avatar">
                    {{ strtoupper(substr($consultation->patient->first_name ?? 'P', 0, 1)) }}{{ strtoupper(substr($consultation->patient->last_name ?? 'T', 0, 1)) }}
                </div>
                <div>
                    <div class="patient-name">{{ $consultation->patient->last_name ?? '' }}, {{ $consultation->patient->first_name ?? '' }}</div>
                    <span style="font-size:12px; color: var(--text-muted); font-family: monospace;">{{ $consultation->patient->patient_id ?? '-' }}</span>
                </div>
            </div>
            <div class="patient-meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Encounter Date</span>
                    <span class="meta-value">{{ optional($consultation->consultation_date)->format('M d, Y') ?? '-' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Patient ID</span>
                    <span class="meta-value">{{ $consultation->patient->patient_id ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Visit Info & Vitals -->
        <div class="section-panel">
            <div class="panel-header">
                <span class="panel-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Intake & Vitals
                </span>
            </div>
            <div class="panel-body">
                <div class="record-rows">
                    <div class="record-row">
                        <span class="record-key">Reason</span>
                        <span class="record-val {{ $val($consultation->reason_for_visit) ? '' : 'empty' }}">{{ $val($consultation->reason_for_visit) ?? 'Not recorded' }}</span>
                    </div>
                    <div class="record-row">
                        <span class="record-key">Complaint</span>
                        <span class="record-val {{ $val($consultation->chief_complaint) ? '' : 'empty' }}">{{ $val($consultation->chief_complaint) ?? 'Not recorded' }}</span>
                    </div>
                </div>

                @if ($consultation->vital_bp || $consultation->vital_temp || $consultation->vital_weight || $consultation->vital_height)
                    <div class="metrics-grid">
                        <div class="metric-tile">
                            <span class="label">Blood Pressure</span>
                            <span class="val">{{ $consultation->vital_bp ?? '-' }}</span>
                        </div>
                        <div class="metric-tile">
                            <span class="label">Temperature</span>
                            <span class="val">{{ $consultation->vital_temp ? $consultation->vital_temp . ' °C' : '-' }}</span>
                        </div>
                        <div class="metric-tile">
                            <span class="label">Weight / Height</span>
                            <span class="val">{{ $consultation->vital_weight ?? '-' }} kg / {{ $consultation->vital_height ?? '-' }} cm</span>
                        </div>
                        <div class="metric-tile">
                            <span class="label">Body Mass Index</span>
                            <span class="val">{{ $consultation->vital_bmi ?? '-' }}</span>
                        </div>
                    </div>
                @endif

                @if ($consultation->triageRecord)
                    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                        <span class="badge-link">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 10 4 15 9 20"/><path d="M20 4v7a4 4 0 0 1-4 4H4"/></svg>
                            Intake recorded {{ $consultation->triageRecord->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Risk Assessment Panel -->
        @if ($consultation->triageRecord && $consultation->triageRecord->risk_level)
            @php
                $lvl = strtolower($consultation->triageRecord->risk_level);
                $badgeStyle = $lvl === 'high' 
                    ? 'background: var(--badge-rose-bg); color: var(--badge-rose-text); border: 1px solid var(--badge-rose-border);' 
                    : ($lvl === 'medium' 
                        ? 'background: var(--badge-amber-bg); color: var(--badge-amber-text); border: 1px solid var(--badge-amber-border);' 
                        : 'background: var(--badge-green-bg); color: var(--badge-green-text); border: 1px solid var(--badge-green-border);');
            @endphp
            <div class="section-panel">
                <div class="panel-header">
                    <span class="panel-title">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Risk Assessment
                    </span>
                </div>
                <div class="panel-body">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <span style="font-weight: 700; font-size: 13px;">Risk Category:</span>
                        <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; padding: 4px 10px; border-radius: 6px; {{ $badgeStyle }}">
                            {{ $consultation->triageRecord->risk_level }}{{ $consultation->triageRecord->risk_score !== null ? ' ('.$consultation->triageRecord->risk_score.')' : '' }}
                        </span>
                    </div>
                    <div class="info-bubble">
                        <label>Identified Risk Factors</label>
                        <p>{{ is_array($consultation->triageRecord->risk_factors) ? implode(', ', $consultation->triageRecord->risk_factors) : '-' }}</p>
                    </div>
                </div>
            </div>
        @endif

    </aside>

    <!-- ================= RIGHT COLUMN: CLINICAL DETAILS ================= -->
    <main class="workspace">

        <!-- Assessment Notes -->
        <div class="section-panel">
            <div class="panel-header">
                <span class="panel-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Clinical Assessment & Advice
                </span>
            </div>
            <div class="panel-body">
                
                @if ($val($consultation->assessment_diagnosis))
                    <div class="diagnosis-callout">
                        <span class="label">Primary Diagnosis</span>
                        <p class="text">{{ $consultation->assessment_diagnosis }}</p>
                    </div>
                @endif

                <div class="record-rows">
                    @if (!$val($consultation->assessment_diagnosis))
                        <div class="record-row">
                            <span class="record-key">Diagnosis</span>
                            <span class="record-val empty">Not recorded</span>
                        </div>
                    @endif
                    <div class="record-row">
                        <span class="record-key">Physical Findings</span>
                        <span class="record-val {{ $val($consultation->details['additional_findings'] ?? null) ? '' : 'empty' }}">{{ $val($consultation->details['additional_findings'] ?? null) ?? 'Not recorded' }}</span>
                    </div>
                    <div class="record-row">
                        <span class="record-key">Treatment Plan</span>
                        <span class="record-val {{ $val($consultation->treatment) ? '' : 'empty' }}">{{ $val($consultation->treatment) ?? 'Not recorded' }}</span>
                    </div>
                    <div class="record-row">
                        <span class="record-key">Health Advice</span>
                        <span class="record-val {{ $val($consultation->health_advice) ? '' : 'empty' }}">{{ $val($consultation->health_advice) ?? 'Not recorded' }}</span>
                    </div>
                    <div class="record-row">
                        <span class="record-key">Referral Path</span>
                        <span class="record-val {{ $val($consultation->referral) ? '' : 'empty' }}">{{ $val($consultation->referral) ?? 'None' }}</span>
                    </div>
                    <div class="record-row">
                        <span class="record-key">Follow-up Date</span>
                        <span class="record-val {{ $consultation->follow_up_date ? '' : 'empty' }}">{{ $consultation->follow_up_date ? $consultation->follow_up_date->format('F d, Y') : 'Not scheduled' }}</span>
                    </div>
                    <div class="record-row">
                        <span class="record-key">Clinical Notes</span>
                        <span class="record-val {{ $val($consultation->notes) ? '' : 'empty' }}">{{ $val($consultation->notes) ?? 'Not recorded' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescriptions -->
        <div class="section-panel">
            <div class="panel-header">
                <span class="panel-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/></svg>
                    Prescription Orders
                </span>
            </div>
            <div class="panel-body">
                @if ($consultation->prescriptions->isNotEmpty())
                    <div class="table-responsive">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Duration</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($consultation->prescriptions as $rx)
                                <tr>
                                    <td><strong style="color: var(--text-title);">{{ $rx->medicine_name }}</strong></td>
                                    <td>{{ $rx->dosage ?? '-' }}</td>
                                    <td>{{ $rx->frequency ?? '-' }}</td>
                                    <td>{{ $rx->duration ?? '-' }}</td>
                                    <td>{{ $rx->quantity ?? '-' }}</td>
                                    <td>{{ $rx->unit ?? '-' }}</td>
                                    <td>{{ $rx->instructions ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-slate">No medicines were prescribed during this consultation.</div>
                @endif
            </div>
        </div>

        <!-- Related Records -->
        @if ($consultation->relatedNcdAssessment || $consultation->relatedConsultation)
            <div class="section-panel">
                <div class="panel-header">
                    <span class="panel-title">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        Related Medical Records
                    </span>
                </div>
                <div class="panel-body">
                    <div class="record-rows">
                        @if ($consultation->relatedNcdAssessment)
                            <div class="record-row">
                                <span class="record-key">NCD Assessment</span>
                                <div class="record-val" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                                    <span>Recorded on {{ optional($consultation->relatedNcdAssessment->assessment_date)->format('F d, Y') ?? $consultation->relatedNcdAssessment->assessment_date }}</span>
                                    <a href="{{ route('ncd.create', $consultation->patient_id) }}" class="badge-link">View Full Assessment &rarr;</a>
                                </div>
                            </div>
                        @endif
                        @if ($consultation->relatedConsultation)
                            <div class="record-row">
                                <span class="record-key">Prior Visit</span>
                                <div class="record-val" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                                    <span>
                                        {{ optional($consultation->relatedConsultation->consultation_date)->format('F d, Y') }} 
                                        &bull; 
                                        <b>{{ $consultation->relatedConsultation->assessment_diagnosis ?? 'No diagnosis recorded' }}</b>
                                    </span>
                                    <a href="{{ route('consultations.show', $consultation->relatedConsultation->id) }}" class="badge-link">Open Record &rarr;</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </main>
</div>

<!-- Floating Action Shelf -->
<div class="bottom-shelf">
    <a href="{{ route(strtolower(auth()->user()->role) . '.patient-records', ['view_patient' => $consultation->patient_id, 'tab' => 'medical-history']) }}" class="btn btn-ghost">
        &larr; Return to Patient History
    </a>
</div>

</body>
</html>
@php
    date_default_timezone_set('Asia/Manila');
    $role = strtolower($role ?? session('admin_role') ?? session('user_role') ?? 'bhw');
    $userName = session('admin_name') ?? session('user_name') ?? 'User';

    // ✅ GET CURRENT USER SAFELY
    $currentUser = auth()->user();
    $isDoctor = strtolower(optional($currentUser)->role ?? '') === 'doctor';
    $isPIC = in_array(optional($currentUser)->is_physician_in_charge, [1, "1", true], true);
    $canCallPatient = $isDoctor || $isPIC;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Triage - Velasquez Health Center</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            overflow-x: hidden;
        }

        .container { display: flex; }

        /* ================= SIDEBAR — FULLY RETAINED ================= */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: white;
            border-right: 1px solid #E5E7EB;
            padding: 24px;
            position: fixed;
            top: 0;
            left: 0;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #E5E7EB;
            margin-bottom: 10px;
        }

        .logo { width: 40px; height: 40px; border-radius: 50%; }

        .brand-wrapper {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand {
            font-weight: bold;
            color: #1E3A8A;
            font-size: 13.3px;
        }

        .sub { font-size: 11px; color: #6B7280; }

        .group {
            margin-top: 22px;
            font-size: 11px;
            font-weight: bold;
            color: #9CA3AF;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px;
            margin-top: 6px;
            text-decoration: none;
            color: #5f6570;
            border-radius: 6px;
            font-size: 14px;
        }

        .nav-icon {
            width: 22px;
            height: 22px;
            object-fit: contain;
        }

        .nav-item.active {
            background: #EFF6FF;
            color: #1A73E8;
            border-left: 4px solid #1A73E8;
            font-weight: bold;
        }

        .nav-item:hover { background: #F3F4F6; }

        /* ================= MAIN CONTENT WRAPPER ================= */
        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 24px;
            box-sizing: border-box;
        }

        /* ================= HEADER — FULLY RETAINED ================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .welcome-text {
            font-size: 14px;
            color: #374151;
            margin-bottom: 5px;
        }

        .welcome-name {
            font-weight: bold;
            color: #1F2937;
            font-size: 18px;
        }

        .role-badge {
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 8px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .bg-admin { background: #9333EA; }
        .bg-doctor { background: #3B82F6; }
        .bg-nurse { background: #10B981; }
        .bg-bhw { background: #6366F1; }

        .header-divider {
            width: 100%;
            height: 1px;
            background: #E5E7EB;
            margin: 16px 0 24px;
        }

        /* PAGE TOP */
        .page-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #1F2937;
        }

        .page-title p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #6B7280;
        }

        .btn-primary {
            background: #2563EB;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover { background: #1D4ED8; }

        /* EMERGENCY ALERT BANNER */
        .alert-banner {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-left: 4px solid #EF4444;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }

        .alert-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .alert-content {
            font-size: 13px;
            color: #991B1B;
            line-height: 1.4;
        }

        .alert-content b { color: #DC2626; }

        .queue-notice {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #DC2626;
            color: #FFFFFF;
            padding: 14px 24px;
            border-radius: 8px;
            z-index: 9999;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            box-shadow: 0 4px 12px rgba(127, 29, 29, .35);
            animation: queue-notice-flash .8s ease-in-out 3;
        }

        @keyframes queue-notice-flash {
            50% { opacity: .35; }
        }

        /* KPI CARDS */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .kpi-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 16px 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .kpi-card:hover { transform: translateY(-2px); }
        .kpi-card.active { outline: 2px solid #2563EB; outline-offset: 2px; }

        .kpi-card h4 {
            margin: 0 0 6px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .kpi-card .val {
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
        }

        .card-total { border-left-color: #9CA3AF; }
        .card-total h4 { color: #6B7280; }
        .card-total .val { color: #111827; }

        .card-high { border-left-color: #EF4444; }
        .card-high h4 { color: #DC2626; }
        .card-high .val { color: #991B1B; }

        .card-medium { border-left-color: #F59E0B; }
        .card-medium h4 { color: #D97706; }
        .card-medium .val { color: #92400E; }

        .card-low { border-left-color: #10B981; }
        .card-low h4 { color: #059669; }
        .card-low .val { color: #065F46; }

        /* QUEUE TABLE CARD */
        .table-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: #F9FAFB;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6B7280;
            border-bottom: 1px solid #E5E7EB;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #F3F4F6;
            vertical-align: middle;
            color: #374151;
        }

        tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #FAFAFA; }

        .font-bold { font-weight: 700; color: #111827; }
        .text-muted { color: #6B7280; font-size: 12px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* RISK PILLS */
        .pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .pill.high { background: #FEE2E2; color: #991B1B; }
        .pill.medium { background: #FEF3C7; color: #92400E; }
        .pill.low { background: #D1FAE5; color: #065F46; }

        /* ACTION BUTTONS */
        .btn-sm {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            margin-left: 6px;
        }

        .btn-view { background: #EFF6FF; color: #1D4ED8; }
        .btn-view:hover { background: #DBEAFE; }

        .btn-call { background: #10B981; color: white; }
        .btn-call:hover { background: #059669; }

        .btn-session { background: #F59E0B; color: white; }
        .btn-session:hover { background: #D97706; }

        .btn-done { background: #EF4444; color: white; }
        .btn-done:hover { background: #DC2626; }

        .empty-row { text-align: center; padding: 40px; color: #6B7280; }

        /* MODAL STYLES */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: flex-start;
            z-index: 1000;
            padding: 40px 0;
            overflow-y: auto;
        }

        .modal-content {
            background: white;
            width: 650px;
            max-width: 95%;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; }
        .close-btn { font-size: 22px; cursor: pointer; color: #6B7280; border: none; background: none; }

        .modal-body { padding: 20px; }
        .modal-footer {
            padding: 16px 20px;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .search-box { position: relative; margin-bottom: 16px; }
        .search-input {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1.5px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            outline: none;
        }
        .search-input:focus { border-color: #2563EB; }
        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        .patient-result {
            padding: 12px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .patient-result:hover { background: #F9FAFB; }
        .patient-name { font-weight: 600; color: #1F2937; margin-bottom: 4px; }
        .patient-meta { font-size: 11px; color: #6B7280; }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            font-size: 12px;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
        }
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #E5E7EB;
        }

        .reg-link {
            display: block;
            text-align: center;
            padding: 12px;
            border: 1px solid #2563EB;
            color: #2563EB;
            background: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }
        .reg-link:hover { background: #EFF6FF; }

        .selected-patient-box {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 8px;
            padding: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .selected-patient-box .name { font-weight: 600; color: #1E3A8A; }
        .selected-patient-box .meta { font-size: 12px; color: #64748B; margin-top: 2px; }
        .change-link { color: #2563EB; font-size: 13px; font-weight: 600; text-decoration: none; }

        .form-section-title {
            font-weight: 600;
            font-size: 15px;
            color: #1F2937;
            margin: 20px 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #E5E7EB;
        }

        .form-group { margin-bottom: 14px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 5px;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
        }
        .form-control:focus, .form-select:focus { border-color: #2563EB; }

        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .symptoms-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 8px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #374151;
            cursor: pointer;
        }

        .btn-submit {
            background: #D1D5DB;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: not-allowed;
            opacity: 0.5;
            transition: all 0.2s;
        }
        .btn-submit.active {
            background: #2563EB;
            cursor: pointer;
            opacity: 1;
        }
        .btn-cancel {
            background: white;
            border: 1px solid #D1D5DB;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            background: #F9FAFB;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            margin-bottom: 16px;
        }
        .detail-item .label {
            font-size: 11px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
        }
        .detail-item .value {
            font-size: 14px;
            font-weight: 500;
            color: #111827;
            margin-top: 2px;
        }
        .symptoms-box {
            background: white;
            border: 1px solid #D1D5DB;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            color: #4B5563;
            min-height: 40px;
        }

        @media (max-width: 992px) {
            .kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .vitals-grid { grid-template-columns: repeat(2, 1fr); }
            .detail-grid { grid-template-columns: repeat(2, 1fr); }
            .symptoms-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container">
    <x-sidebar />

    <div class="main">

        <!-- ✅ TOP HEADER -->
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ $userName }}
                    @php
                        $displayRole = ($isDoctor && $isPIC) ? 'PIC' : strtoupper($role);
                        $badgeClass = $role;
                    @endphp
                    <span class="role-badge bg-{{ $badgeClass }}">
                        {{ $displayRole }}
                    </span>
                </div>
            </div>
            <div style="text-align: right; font-size: 12px; color: #374151;">
                <b>Velasquez Health Center</b><br>
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="header-divider"></div>

        @if (session('success'))
            <div style="margin: 0 0 20px; padding: 14px 18px; background: #DCFCE7; border: 1px solid #86EFAC; color: #166534; border-radius: 6px; font-size: 14px; font-weight: bold;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="margin: 0 0 20px; padding: 14px 18px; background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; border-radius: 6px; font-size: 14px;">
                <b>Could not save. Please fix the following:</b>
                <ul style="margin: 8px 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ✅ PAGE TOP -->
        <div class="page-top">
            <div class="page-title">
                <h2>Patient Triage</h2>
                <p>Auto-sorted by risk level for priority care</p>
            </div>
            @if(in_array($role, ['bhw', 'nurse', 'admin']))
                <button class="btn-primary" onclick="toggleModal(true)">+ Add Patient to Queue</button>
            @endif
        </div>

        <!-- ✅ EMERGENCY ALERT BANNER -->
        @if(($highRiskCount ?? 0) > 0)
        <div class="alert-banner">
            <div class="alert-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div class="alert-content">
                <strong>EMERGENCY NOTICE:</strong> There are currently <b>{{ $highRiskCount }}</b> High-Risk case(s) waiting in the queue. Please attend to these immediately.
            </div>
        </div>
        @endif

        <!-- ✅ KPI CARDS -->
        <div class="kpi-grid">
            <div class="kpi-card card-total" onclick="filterTriage('all')" id="kpi-all">
                <h4>Total in Queue</h4>
                <div class="val">{{ $totalQueueCount ?? 0 }}</div>
            </div>
            <div class="kpi-card card-high" onclick="filterTriage('high')" id="kpi-high">
                <h4>High Risk</h4>
                <div class="val">{{ $highRiskCount ?? 0 }}</div>
            </div>
            <div class="kpi-card card-medium" onclick="filterTriage('medium')" id="kpi-medium">
                <h4>Medium Risk</h4>
                <div class="val">{{ $mediumRiskCount ?? 0 }}</div>
            </div>
            <div class="kpi-card card-low" onclick="filterTriage('low')" id="kpi-low">
                <h4>Low Risk</h4>
                <div class="val">{{ $lowRiskCount ?? 0 }}</div>
            </div>
        </div>

        <!-- ✅ QUEUE TABLE — FULLY FIXED -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 27%;">Patient Name</th>
                        <th style="width: 25%;">Reason / Service</th>
                        <th style="width: 15%; text-align: center;">Risk Level</th>
                        <th style="width: 25%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="queueTableBody">
                    @forelse($triageRecords ?? [] as $index => $record)
                    <tr data-risk="{{ strtolower($record->risk_level ?? 'low') }}" data-status="{{ strtolower($record->status ?? 'waiting') }}">
                        <td class="font-bold">#{{ $index + 1 }}</td>
                        <td>
                            {{ $record->patient ? ($record->patient->first_name . ' ' . $record->patient->last_name) : 'Unknown Patient' }}
                        </td>
                        <td class="text-muted">{{ $record->service_type ?? 'General Checkup' }}</td>
                        <td class="text-center">
                            @php $risk = strtolower($record->risk_level ?? 'low'); @endphp
                            <span class="pill {{ $risk }}">{{ ucfirst($risk) }} Risk</span>
                        </td>
                        <td class="text-right">
                            <button class="btn-sm btn-view"
                                    data-patient='@json($record->patient)'
                                    data-record='@json($record)'
                                    onclick="openTriageModal(this)">View</button>

                            {{-- ✅ FULLY FIXED: SHOW BUTTONS FOR DOCTOR AND PIC --}}
                            @if($canCallPatient)
                                @php $status = strtolower(trim($record->status ?? 'waiting')); @endphp
                                @if($status === 'waiting' || empty($status))
                                    <button class="btn-sm btn-call" onclick="updateQueueStatus({{ $record->id }}, 'Called')">Call Patient</button>
                                @elseif($status === 'called')
                                    <button class="btn-sm btn-session" onclick="updateQueueStatus({{ $record->id }}, 'In Session')">Start Session</button>
                                @elseif($status === 'in session')
                                    <button class="btn-sm btn-done" onclick="updateQueueStatus({{ $record->id }}, 'Done')">Done</button>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-row">Queue is currently empty.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- ✅ ADD TO QUEUE MODAL -->
<div class="modal-overlay" id="addQueueModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Patient to Queue</h3>
            <button class="close-btn" onclick="toggleModal(false)">&times;</button>
        </div>

        <div class="modal-body" id="searchStep">
            <div style="font-weight:600; font-size:14px; margin-bottom:12px;">Patient Selection</div>
            <div class="search-box">
                <img src="/icons/search.png" class="search-icon" alt="search">
                <input type="text" class="search-input" id="patientSearch" placeholder="Search by name and press Enter...">
            </div>
            <div id="searchResults"></div>
            <div class="divider">OR</div>
            <a href="/{{ $role }}/patient-registration" class="reg-link">Register New Patient</a>
        </div>

        <form id="assessmentForm" method="POST" action="{{ route('triage.storeQueue') }}">
            @csrf
            <input type="hidden" name="patient_id" id="selectedPatientId">

            <div class="modal-body" id="assessmentStep" style="display: none;">
                <div class="selected-patient-box">
                    <div>
                        <div class="name" id="dispName"></div>
                        <div class="meta" id="dispMeta"></div>
                    </div>
                    <a href="javascript:void(0)" onclick="goBackToSearch()" class="change-link">Change</a>
                </div>

                <div class="form-section-title">Initial Assessment</div>

                <div class="form-group">
                    <label class="form-label">Service Type</label>
                    <select class="form-select" name="service_type" id="serviceType" required>
                        <option value="">Select reason...</option>
                        <option value="Consultation">Consultation</option>
                        <option value="Immunization">Immunization</option>
                        <option value="Family Planning">Family Planning</option>
                        <option value="Prenatal">Pre-natal</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="ECG">ECG</option>
                        <option value="Ultrasound">Ultrasound</option>
                        <option value="Dental">Dental</option>
                        <option disabled>—— Laboratory ——</option>
                        <option value="Blood Extraction">— Blood Extraction</option>
                        <option value="Sputum Exam">— Sputum Exam</option>
                        <option value="Tuberculosis Program">— Tuberculosis Program</option>
                        <option value="Counseling Adolescent">Counseling Adolescent</option>
                    </select>
                </div>

                <div class="vitals-grid">
                    <div class="form-group">
                        <label class="form-label">Temperature (°C)</label>
                        <input type="text" class="form-control" name="temp" id="temp" placeholder="37.5">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Blood Pressure</label>
                        <input type="text" class="form-control" name="bp" id="bp" placeholder="120/80">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight (kg)</label>
                        <input type="text" class="form-control" name="weight" id="weight" placeholder="60">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Height (cm)</label>
                        <input type="text" class="form-control" name="height" id="height" placeholder="170">
                    </div>
                </div>

                <div class="form-label" style="margin-top:10px;">Symptoms</div>
                <div class="symptoms-grid">
                    <label class="checkbox-item"><input type="checkbox" name="symptoms[]" value="Fever"> Fever</label>
                    <label class="checkbox-item"><input type="checkbox" name="symptoms[]" value="Cough"> Cough</label>
                    <label class="checkbox-item"><input type="checkbox" name="symptoms[]" value="Difficulty breathing"> Difficulty breathing</label>
                    <label class="checkbox-item"><input type="checkbox" name="symptoms[]" value="Headache"> Headache</label>
                    <label class="checkbox-item"><input type="checkbox" name="symptoms[]" value="Body pain"> Body pain</label>
                    <label class="checkbox-item"><input type="checkbox" name="symptoms[]" value="Chest pain"> Chest pain</label>
                </div>
            </div>

            <div class="modal-footer" id="assessmentFooter" style="display: none;">
                <button type="button" class="btn-cancel" onclick="toggleModal(false)">Cancel</button>
                <button type="submit" class="btn-submit" id="submitBtn" disabled>Add to Queue</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ VIEW DETAILS MODAL -->
<div class="modal-overlay" id="viewTriageModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Triage Assessment Details</h3>
            <button class="close-btn" onclick="closeTriageModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="selected-patient-box" style="margin-bottom:20px;">
                <div>
                    <div class="name" id="vtName">—</div>
                    <div class="meta" id="vtMeta">—</div>
                </div>
                <span id="vtRiskBadge" class="pill low">Low Risk</span>
            </div>

            <div style="font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Vital Signs & Measurements</div>
            <div class="detail-grid">
                <div class="detail-item"><div class="label">Reason</div><div class="value" id="vtReason">—</div></div>
                <div class="detail-item"><div class="label">Temperature</div><div class="value" id="vtTemp">—</div></div>
                <div class="detail-item"><div class="label">BP</div><div class="value" id="vtBp">—</div></div>
                <div class="detail-item"><div class="label">Weight / Height</div><div class="value" id="vtWtHt">—</div></div>
            </div>

            <div style="font-size:13px; font-weight:600; color:#374151; margin:16px 0 8px;">Reported Symptoms</div>
            <div class="symptoms-box" id="vtSymptoms">No symptoms recorded.</div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeTriageModal()">Close</button>
        </div>
    </div>
</div>

<script>
    // ========== MODAL CONTROLS ==========
    function toggleModal(show) {
        document.getElementById('addQueueModal').style.display = show ? 'flex' : 'none';
        if (!show) resetForm();
    }

    function resetForm() {
        document.getElementById('searchStep').style.display = 'block';
        document.getElementById('assessmentStep').style.display = 'none';
        document.getElementById('assessmentFooter').style.display = 'none';
        document.getElementById('searchResults').innerHTML = '';
        document.getElementById('patientSearch').value = '';
        document.getElementById('serviceType').value = '';
        document.getElementById('temp').value = '';
        document.getElementById('bp').value = '';
        document.getElementById('weight').value = '';
        document.getElementById('height').value = '';
        document.querySelectorAll('input[name="symptoms[]"]').forEach(cb => cb.checked = false);
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').classList.remove('active');
    }

    function goBackToSearch() {
        resetForm();
    }

    // ========== VIEW DETAILS MODAL ==========
    function openTriageModal(btn) {
        const p = JSON.parse(btn.dataset.patient);
        const r = JSON.parse(btn.dataset.record);
        const risk = (r.risk_level ?? 'Low').toLowerCase();

        document.getElementById('vtName').innerText = `${p.first_name || ''} ${p.last_name || ''}`.trim() || 'Unknown Patient';
        document.getElementById('vtMeta').innerText = `ID: ${p.patient_id || '—'} | Age: ${p.age || '—'} | ${p.barangay || '—'}`;
        document.getElementById('vtRiskBadge').innerText = `${risk.charAt(0).toUpperCase() + risk.slice(1)} Risk`;
        document.getElementById('vtRiskBadge').className = `pill ${risk}`;
        document.getElementById('vtReason').innerText = r.service_type ?? 'Consultation';
        document.getElementById('vtTemp').innerText = r.temp ? `${r.temp} °C` : '—';
        document.getElementById('vtBp').innerText = r.bp ?? '—';
        document.getElementById('vtWtHt').innerText = `${r.weight ?? '—'} kg / ${r.height ?? '—'} cm`;
        document.getElementById('vtSymptoms').innerText = r.symptoms ? r.symptoms : 'No symptoms recorded.';

        document.getElementById('viewTriageModal').style.display = 'flex';
    }

    function closeTriageModal() {
        document.getElementById('viewTriageModal').style.display = 'none';
    }

    function showQueueNotice(message, isError = false) {
        const notice = Object.assign(document.createElement('div'), {
            textContent: message,
            role: 'status'
        });
        notice.className = 'queue-notice';
        if (isError) notice.style.background = '#B91C1C';
        document.body.appendChild(notice);
        setTimeout(() => notice.remove(), 5000);
    }

    // ========== UPDATE QUEUE STATUS ==========
    function updateQueueStatus(id, status) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        fetch(`/triage/update-status/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(d => {
            if (d.success) window.location.reload();
            else showQueueNotice('Error: ' + (d.message ?? 'Unknown error'), true);
        })
        .catch(() => showQueueNotice('Unable to update the patient status.', true));
    }

    // ========== PATIENT SEARCH ==========
    const searchInput = document.getElementById('patientSearch');
    if (searchInput) {
        searchInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const q = e.target.value.trim();
                if (q.length < 2) return;

                fetch(`/patients/search?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('searchResults');
                    container.innerHTML = '';

                    if (!data.length) {
                        container.innerHTML = '<div style="padding:16px; color:#6B7280; text-align:center;">No patients found</div>';
                        return;
                    }

                    data.forEach(patient => {
                        const card = document.createElement('div');
                        card.className = 'patient-result';
                        card.innerHTML = `
                            <div class="patient-name">${patient.first_name} ${patient.last_name}</div>
                            <div class="patient-meta">ID: ${patient.patient_id} | Age: ${patient.age} | ${patient.barangay || '—'}</div>
                        `;
                        card.onclick = () => selectPatient(patient);
                        container.appendChild(card);
                    });
                });
            }
        });
    }

    function selectPatient(p) {
        document.getElementById('searchStep').style.display = 'none';
        document.getElementById('assessmentStep').style.display = 'block';
        document.getElementById('assessmentFooter').style.display = 'flex';
        document.getElementById('selectedPatientId').value = p.id;
        document.getElementById('dispName').innerText = `${p.first_name} ${p.last_name}`;
        document.getElementById('dispMeta').innerText = `ID: ${p.patient_id} | Age: ${p.age} | ${p.barangay || '—'}`;
        checkFormCompletion();
    }

    // ========== FORM VALIDATION ==========
    function checkFormCompletion() {
        const hasPatient = document.getElementById('selectedPatientId').value.trim() !== '';
        const hasService = document.getElementById('serviceType').value.trim() !== '';
        const submitBtn = document.getElementById('submitBtn');

        if (hasPatient && hasService) {
            submitBtn.disabled = false;
            submitBtn.classList.add('active');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.remove('active');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('serviceType').addEventListener('change', checkFormCompletion);
        ['temp', 'bp', 'weight', 'height'].forEach(id => {
            document.getElementById(id).addEventListener('input', checkFormCompletion);
        });
        document.querySelectorAll('input[name="symptoms[]"]').forEach(cb => {
            cb.addEventListener('change', checkFormCompletion);
        });
    });

    // ========== FILTER TABLE BY RISK ==========
    function filterTriage(risk) {
        document.querySelectorAll('.kpi-card').forEach(c => c.classList.remove('active'));
        document.getElementById(`kpi-${risk}`)?.classList.add('active');

        document.querySelectorAll('#queueTableBody tr').forEach(row => {
            const rowRisk = row.dataset.risk;
            const rowStatus = row.dataset.status;
            if (rowStatus === 'done' || rowStatus === 'consulted') {
                row.style.display = 'none';
                return;
            }
            row.style.display = (risk === 'all' || rowRisk === risk) ? '' : 'none';
        });
    }

    // ========== LIVE QUEUE UPDATE — BHW RECEIVES DOCTOR CALLS ==========
    @if($role === 'bhw')
    setInterval(() => {
        fetch("{{ route('triage.liveData') }}")
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('queueTableBody');
            if (tbody && data.html) {
                tbody.innerHTML = data.html;
                tbody.querySelectorAll('.btn-call, .btn-session, .btn-done').forEach(btn => {
                    btn.style.display = 'none';
                });
            }
            if (data.active_call && localStorage.getItem('last_call') !== data.call_id) {
                localStorage.setItem('last_call', data.call_id);
                showQueueNotice('NOW CALLING: ' + data.patient_name + ' - Please proceed.');
            }
        });
    }, 3000);
    @endif

    // ESC KEY TO CLOSE
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            toggleModal(false);
            closeTriageModal();
        }
    });

</script>

</body>
</html>
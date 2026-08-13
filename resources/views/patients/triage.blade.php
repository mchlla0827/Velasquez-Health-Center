@php
    date_default_timezone_set('Asia/Manila');
    $role = strtolower($role ?? session('admin_role') ?? session('user_role') ?? 'bhw');
    $userName = session('admin_name') ?? session('user_name') ?? 'User';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Triage | Barangay Health System</title>
    <style>
        body { 
            margin: 0; 
            font-family: Arial, sans-serif; 
            background: #F9FAFB; 
            overflow-x: hidden; 
        }
        .container { 
            display: flex; 
        }

        .sidebar {
            width: 260px; height: 100vh; background: white; border-right: 1px solid #E5E7EB;
            padding: 24px; position: fixed; top: 0; left: 0; box-sizing: border-box; overflow-y: auto;
        }
        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #E5E7EB;
            margin-bottom: 10px;
        }

.logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.brand {
    font-weight: bold;
    color: #1E3A8A;
    font-size: 13.3px;
}

.sub {
    font-size: 11px;
    color: #6B7280;
}

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

.nav-item:hover {
    background: #F3F4F6;
}

        .main { 
            margin-left: 250px; 
            width: calc(100% - 260px);
            padding: 24px; 
            box-sizing: border-box; 
        }

        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
        }

        .welcome-name { 
            font-weight: bold; 
            color: #1F2937; 
            font-size: 18px; 
            margin-top: 4pt
        }
        
       .role-badge {
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 6px;
            text-transform: uppercase;
            font-weight: bold;
        }

.bg-admin {
    background: #9333EA;
}

.bg-doctor {
    background: #3B82F6;
}

.bg-nurse {
    background: #10B981;
}

.bg-bhw {
    background: #6366F1;
}

.header-divider {
    width: 100%;
    height: 1px;
    background: #E5E7EB;
    margin: 16px 0;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.page-title {
    font-size: 22px;
    font-weight: bold;
    color: #111827;
    margin-bottom: 4px;
}

.page-subtitle {
    font-size: 13px;
    color: #6B7280;
}

.add-btn {
    background: #2563EB;
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 13px;
}
/* KPI Grid Wrapper */
.kpi-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 20px;
    margin-bottom: 24px;
    width: 100%;
}

/* Base Card Styling — glass style */
.kpi {
    background: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 22px 24px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.6);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.kpi::before {
    display: none;
}

/* Typography Styling */
.kpi-title {
    margin: 0;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kpi-number {
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
    margin-top: 0;
}

/* Interactive Feedback */
.kpi:active {
    transform: translateY(-2px);
}


/* --- Color Theme Assignments (Soft Glass Tints) --- */

/* Total Queue Theme (Grey Tint) */
.kpi-grey { 
    background: rgba(243, 244, 246, 0.4); 
    border-color: rgba(209, 213, 219, 0.5);
}
.kpi-grey .kpi-title { color: #6B7280; }
.kpi-grey .kpi-number { color: #111827; }

/* High Risk Theme (Red Tint) */
.kpi-red { 
    background: rgba(239, 68, 68, 0.06); 
    border-color: rgba(239, 68, 68, 0.15);
}
.kpi-red .kpi-title { color: #DC2626; }
.kpi-red .kpi-number { color: #991B1B; }

/* Medium Risk Theme (Yellow/Amber Tint) */
.kpi-yellow { 
    background: rgba(245, 158, 11, 0.06); 
    border-color: rgba(245, 158, 11, 0.15);
}
.kpi-yellow .kpi-title { color: #D97706; }
.kpi-yellow .kpi-number { color: #92400E; }

/* Low Risk Theme (Green Tint) */
.kpi-green { 
    background: rgba(16, 185, 129, 0.06); 
    border-color: rgba(16, 185, 129, 0.15);
}
.kpi-green .kpi-title { color: #059669; }
.kpi-green .kpi-number { color: #065F46; }

/* Dynamic Emergency Alert Banner */
.triage-alert-banner.emergency {
    display: flex;
    align-items: center;
    gap: 14px;
    background: rgba(239, 68, 68, 0.08); /* Transparent deep red tint */
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(239, 68, 68, 0.25);
    border-left: 5px solid #DC2626; /* Crimson side-accent strip */
    border-radius: 16px;
    padding: 16px 20px;
    margin-top: 20px;
    margin-bottom: 24px;
    width: 100%;
    box-sizing: border-box;
    animation: pulse-subtle 2s infinite ease-in-out;
}

/* Icon Area Alignment */
.alert-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: rgba(239, 68, 68, 0.12);
    padding: 8px;
    border-radius: 50%;
}

/* Content Layout Grid */
.alert-content {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    line-height: 1.4;
    flex-wrap: wrap;
}

/* Text Element Design */
.alert-badge {
    color: #B91C1C;
    font-weight: 700;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

.alert-text {
    color: #7F1D1D;
    font-weight: 500;
}

.alert-text b {
    background: #DC2626;
    color: white;
    padding: 2px 7px;
    border-radius: 6px;
    font-size: 13px;
    margin: 0 2px;
}

/* Subtle pulse animation to request priority attention */
@keyframes pulse-subtle {
    0% { transform: scale(1); }
    50% { transform: scale(1.006); box-shadow: 0 10px 35px -8px rgba(239, 68, 68, 0.22); }
    100% { transform: scale(1); }
}

.queue-card {
    background: white;
    border-radius: 12px;
    margin-top: 20px;
    border: 1px solid #E5E7EB;
    overflow: hidden;
}

/* Container Frame */
.table-container {
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    margin-top: 16px;
    width: 100%; /* Keeps container tightly locked to layout card bounds */
}

/* Base structural table property assignments */
.triage-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

/* Explicit Alignment & Outer Edge Padding Adjustments */
.text-left   { text-align: left; }
.text-center { text-align: center; }
.text-right  { text-align: right; }

.pad-left    { padding-left: 16px !important; }
.pad-right   { padding-right: 16px !important; }

/* --- Table Headers Structure --- */
.triage-table thead th {
    background-color: #F8FAFC;
    color: #64748B;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding-top: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #E2E8F0;
}

/* --- Content Rows Spacings — PINAKA IMPORTANTE --- */
.triage-table tbody tr {
    border-bottom: 1px solid #F1F5F9;
    height: 42px !important; /* ✅ SAKTO LANG NA TAAS, HINDI MASYADONG HABA */
}

.triage-table tbody tr:last-child {
    border-bottom: none;
}

.triage-table tbody tr:hover {
    background-color: #F8FAFC;
}

.triage-table tbody td {
    padding-top: 4px !important;   /* ✅ BINAWASAN PADDING */
    padding-bottom: 4px !important;/* ✅ BINAWASAN PADDING */
    font-size: 13.5px;
    color: #334155;
    vertical-align: middle;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
}

/* Typography Rules */
.font-bold   { font-weight: 700; color: #1E293B; }
.font-medium { font-weight: 500; color: #1E293B; }
.text-muted  { color: #64748B; }

/* --- Uniform Badges (Pills) --- */
.pill {
    display: inline-block;
    padding: 3px 6px; /* ✅ Sakto lang na laki */
    border-radius: 9999px;
    font-size: 11.5px;
    font-weight: 600;
    text-align: center;
    width: 80px; /* ✅ Sakto lang lapad */
}
.pill.high   { background-color: #FEE2E2; color: #991B1B; }
.pill.medium { background-color: #FEF3C7; color: #92400E; }
.pill.low    { background-color: #D1FAE5; color: #065F46; }

/* --- Button Mechanics --- */
.btn-action {
    background-color: #2563EB;
    color: #ffffff;
    font-weight: 500;
    font-size: 12px;
    border: none;
    padding: 6px 12px; /* ✅ Sakto lang na laki */
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.15s ease;
}
.btn-action:hover {
    background-color: #1D4ED8;
}

/* Empty State Handling */
.empty-state {
    text-align: center;
    padding: 30px !important;
    color: #94A3B8;
    font-size: 13.5px;
}

.pill {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
}

.high {
    background: #FEE2E2;
    color: #B91C1C;
}

.medium {
    background: #FEF3C7;
    color: #92400E;
}

.low {
    background: #D1FAE5;
    color: #065F46;
}
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); display: none; justify-content: center;
            align-items: center; z-index: 1000;
        }
        .modal-content {
            background: white; width: 650px; border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden;
        }
        .modal-header {
            padding: 16px 20px; border-bottom: 1px solid #E5E7EB;
            display: flex; justify-content: space-between; align-items: center;
        }
       .modal-body {
    padding: 20px;
}

.search-container {
    position: relative;
    margin-bottom: 15px;
}

.search-input {
    width: 100%;
    padding: 12px 12px 12px 42px;
    border: 1.5px solid #000;
    border-radius: 10px;
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    object-fit: contain;
    pointer-events: none;
}

.patient-result {
    padding: 12px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: background 0.2s;
}

.patient-result:hover {
    background: #F9FAFB;
}

.patient-name-bold {
    font-weight: bold;
    color: #1F2937;
    margin-bottom: 4px;
}

.patient-meta-sm {
    font-size: 11px;
    color: #6B7280;
}

.divider-text {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 20px 0;
    font-size: 12px;
    color: #9CA3AF;
    font-weight: bold;
    text-transform: uppercase;
}

.divider-text::before,
.divider-text::after {
    content: "";
    flex: 1;
    border-bottom: 1px solid #E5E7EB;
}

.reg-btn-container {
    display: flex;
    justify-content: center;
    width: 100%;
}

.reg-btn {
    display: inline-block;
    width: 100%;
    padding: 12px;
    border: 1px solid #2563EB;
    color: #2563EB;
    background: white;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
}

.reg-btn:hover {
    background-color: #f0f7ff;
}


.selected-patient-box {
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.assessment-section-title {
    font-weight: bold;
    font-size: 16px;
    color: #1F2937;
    margin: 20px 0 10px 0;
    border-bottom: 1px solid #E5E7EB;
    padding-bottom: 8px;
}

.form-group {
    margin-bottom: 15px;
}

.form-label {
    display: block;
    font-size: 13px;
    color: #374151;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-select,
.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}

.vitals-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.symptoms-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 10px;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #374151;
    cursor: pointer;
}

.checkbox-item input {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #E5E7EB;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    background: #fff;
}

.btn-submit {
    background: #D1D5DB;
    color: white;
    padding: 10px 30px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: not-allowed;
    flex-grow: 1;
    transition: background 0.3s;
}

.btn-submit.active {
    background: #2563EB;
    cursor: pointer;
}

.btn-cancel {
    background: white;
    border: 1px solid #D1D5DB;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
}

option.group-header {
    font-weight: bold;
    color: #9CA3AF;
    background-color: #F3F4F6;
}
    </style>
</head>
<body>

<div class="container">
    <x-sidebar />

    <div class="main">
        <div class="header">
    <div>
        <div style="font-size:14px; color:#374151;">Welcome back,</div>

        @php
            $user = Auth::user();

            $displayRole = (
                strtolower($user->role) === 'doctor' &&
                $user->is_physician_in_charge == 1
            ) ? 'PIC' : strtoupper($role);

            $badgeClass = strtolower($user->role);
        @endphp

        <div class="welcome-name">
            {{ $userName }}

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

        <div class="top-bar">
            <div>
                <div class="page-title">Patient Triage</div>
                <div class="page-subtitle">Auto-sorted by risk level for priority care</div>
            </div>
            @if(in_array(strtolower($role), ['bhw', 'nurse', 'admin']))
                <button class="add-btn" onclick="toggleModal(true)">Add Patient to Queue</button>
            @endif
        </div>

        @if(($highRiskCount ?? 0) > 0)
            <div class="triage-alert-banner emergency">
                <div class="alert-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div class="alert-content">
                    <span class="alert-badge">EMERGENCY NOTICE:</span>
                    <span class="alert-text">
                        There are currently <b>{{ $highRiskCount }}</b> High-Risk case(s) waiting in the queue. Please pause routine check-ins if necessary to attend to these critical records immediately.
                    </span>
                </div>
            </div>
        @endif


        <div class="kpi-row">
            
            <div class="kpi kpi-grey" onclick="filterTriage('all')">
                <div class="kpi-title">Total in Queue</div>
                <div class="kpi-number">{{$totalQueueCount}}</div>
            </div>
            <div class="kpi kpi-red" onclick="filterTriage('high')">
                <div class="kpi-title">High Risk</div>
                <div class="kpi-number">{{$highRiskCount}}</div>
            </div>

            <div class="kpi kpi-yellow" onclick="filterTriage('medium')">
                <div class="kpi-title">Medium Risk</div>
                <div class="kpi-number">{{$mediumRiskCount}}</div>
            </div>
            <div class="kpi kpi-green" onclick="filterTriage('low')">
                <div class="kpi-title">Low Risk</div>
                <div class="kpi-number">{{$lowRiskCount}}</div>
            </div>
        </div>

        <div class="queue-card">
<table class="triage-table">
    <colgroup>
        <col style="width: 6%;">   <col style="width: 26%;">  <col style="width: 25%;">  <col style="width: 15%;">  <col style="width: 28%;">  </colgroup>
    
    <thead>
        <tr>
            <th class="text-left pad-left">Queue</th>
            <th class="text-left">Patient Name</th>
            <th class="text-left">Reason / Service</th>
            <th class="text-center">Risk Level</th>
            <th class="text-right pad-right">Action</th>
        </tr>
    </thead>
    
<tbody>
    @forelse($triageRecords as $index => $record)
        <tr data-risk="{{ strtolower($record->risk_level) }}">
            <td class="font-bold pad-left">#{{ $index + 1 }}</td>
            <td class="font-medium">
                {{ $record->patient ? ($record->patient->first_name . ' ' . $record->patient->last_name) : 'Unknown Patient' }}
            </td>
            <td class="text-muted">{{ $record->service_type ?? 'General Checkup' }}</td>
            <td class="text-center">
                @if(strtolower($record->risk_level) == 'high')
                    <span class="pill high">High Risk</span>
                @elseif(strtolower($record->risk_level) == 'medium')
                    <span class="pill medium">Medium Risk</span>
                @else
                    <span class="pill low">Low Risk</span>
                @endif
            </td>
<td class="text-right pad-right" style="white-space: nowrap; vertical-align: middle;">
            <div style="display: inline-flex; gap: 8px; justify-content: flex-end; align-items: center; width: 100%;">
                
                {{-- ✅ VIEW BUTTON: SIGURADONG TAMA ANG DATA --}}
                <button type="button" class="btn-action" 
                        onclick="openTriageModal(this)"
                        data-patient='@json($record->patient)' 
                        data-record='@json($record)'>
                    View
                </button>

                {{-- ✅ DOCTOR ONLY: TAMA NA DALOY NG STATUS --}}
                @if(strtolower(auth()->user()->role) === 'doctor')
                    @php 
                        $status = strtolower(trim($record->status ?? 'waiting')); 
                    @endphp
                    
                    {{-- ✅ STEP 1: KUNG WAITING O WALANG LAMAN --}}
                    @if($status === 'waiting' || empty($status))
                        <button type="button" class="btn-action" style="background-color: #10B981; color: white; border: none; padding: 6px 12px; border-radius: 4px;" 
                                onclick="updateQueueStatus({{ $record->id }}, 'Called')">
                            Call Patient
                        </button>
                    {{-- ✅ STEP 2: KUNG TINAWAG NA --}}
                    @elseif($status === 'called')
                        <button type="button" class="btn-action" style="background-color: #F59E0B; color: white; border: none; padding: 6px 12px; border-radius: 4px;" 
                                onclick="updateQueueStatus({{ $record->id }}, 'In Session')">
                            Start Session
                        </button>
                    {{-- ✅ STEP 3: KUNG NAGKONSULTA NA --}}
                    @elseif($status === 'in session')
                        <button type="button" class="btn-action" style="background-color: #EF4444; color: white; border: none; padding: 6px 12px; border-radius: 4px;" 
                                onclick="updateQueueStatus({{ $record->id }}, 'Done')">
                            Done
                        </button>
                    @endif
                @endif

            </div>
        </td>        
    </tr>
    @empty
        <tr>
            <td colspan="5" class="empty-state">Queue is currently empty.</td>
        </tr>
    @endforelse
</tbody>
</table>

            </div>
        </div>
    </div>

<div class="modal-overlay" id="addQueueModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0; font-size: 16px;">Add Patient to Queue</h3>
            <span style="cursor:pointer; font-size: 20px; color: #9CA3AF;" onclick="toggleModal(false)">&times;</span>
        </div>

        <div class="modal-body" id="searchStep">
            <div style="font-weight:600; font-size: 14px; margin-bottom: 12px; color: #374151;">Patient Selection</div>
            <div class="search-container">
                <img src="/icons/search.png" class="search-icon" alt="search">
                <input type="text" class="search-input" id="patientSearch" placeholder="Search by name and press Enter...">
            </div>
            <div id="searchResults"></div>
            <div class="divider-text">OR</div>
            <div class="reg-btn-container">
                <a href="/{{ $role }}/patient-registration" class="reg-btn">Register New Patient</a>
            </div>
        </div>

        <form id="assessmentForm" method="POST" action="{{ route('triage.storeQueue') }}">            
            @csrf
            <input type="hidden" name="patient_id" id="selectedPatientId">
            <div class="modal-body" id="assessmentStep" style="display: none;">
                <div style="font-weight:600; font-size: 14px; margin-bottom: 12px; color: #374151;">Patient Selection</div>
                <div class="selected-patient-box">
                    <div>
                        <div id="dispName" style="font-weight: bold; color: #1E3A8A;"></div>
                        <div id="dispMeta" style="font-size: 12px; color: #64748B;"></div>
                    </div>
                    <a href="javascript:void(0)" onclick="goBackToSearch()" style="color: #2563EB; font-size: 13px; font-weight: bold; text-decoration: none;">Change</a>
                </div>

                <div class="assessment-section-title">Initial Assessment</div>
                
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
                        <option class="group-header" disabled>Laboratory</option>
                        <option value="Blood Extraction">&nbsp;&nbsp;&nbsp;— Blood extraction</option>
                        <option value="Sputum Exam">&nbsp;&nbsp;&nbsp;— Sputum Exam</option>
                        <option value="Tuberculosis Program">&nbsp;&nbsp;&nbsp;— Tuberculosis program</option>
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

                <div class="form-label" style="margin-top: 10px;">Symptoms</div>
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
            <button type="submit" class="btn-submit" id="submitBtn" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" disabled>Add to Queue</button>                <button type="button" class="btn-cancel" onclick="toggleModal(false)">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="viewTriageModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0; font-size: 16px;">Triage Assessment Details</h3>
            <span style="cursor:pointer; font-size: 20px; color: #9CA3AF;" onclick="closeTriageModal()">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="selected-patient-box" style="margin-bottom: 20px;">
                <div>
                    <div id="vtName" style="font-weight: bold; color: #1E3A8A; font-size: 16px;">---</div>
                    <div id="vtMeta" style="font-size: 12px; color: #64748B; margin-top: 4px;">---</div>
                </div>
                <span id="vtRisk" class="pill low">Low Risk</span>
            </div>

            <div style="font-size: 13px; font-weight: bold; color: #374151; margin-bottom: 8px;">Vital Signs & Measurements</div>
            <div class="vitals-grid" style="margin-bottom: 20px; background: #F9FAFB; padding: 15px; border-radius: 8px; border: 1px solid #E5E7EB;">
                <div><span style="font-size: 11px; color: #6B7280; font-weight: bold;">REASON</span><div id="vtReason" style="font-size: 14px; font-weight: 500; color: #111827;">---</div></div>
                <div><span style="font-size: 11px; color: #6B7280; font-weight: bold;">TEMP</span><div id="vtTemp" style="font-size: 14px; font-weight: 500; color: #111827;">---</div></div>
                <div><span style="font-size: 11px; color: #6B7280; font-weight: bold;">BP</span><div id="vtBp" style="font-size: 14px; font-weight: 500; color: #111827;">---</div></div>
                <div><span style="font-size: 11px; color: #6B7280; font-weight: bold;">WEIGHT/HEIGHT</span><div id="vtWtHt" style="font-size: 14px; font-weight: 500; color: #111827;">---</div></div>
            </div>

            <div style="font-size: 13px; font-weight: bold; color: #374151; margin-bottom: 8px;">Reported Symptoms</div>
            <div id="vtSymptoms" style="background: white; border: 1px solid #D1D5DB; padding: 12px; border-radius: 8px; font-size: 13px; color: #4B5563; min-height: 40px; margin-bottom: 10px;">
                ---
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeTriageModal()">Close</button>
        </div>
    </div>
</div>

<script>
    // --- MODAL FUNCTIONS ---
    function toggleModal(show) {
        const m = document.getElementById('addQueueModal');
        if(m) m.style.display = show ? 'flex' : 'none';
    }

    function goBackToSearch() {
        document.getElementById('searchStep').style.display = 'block';
        document.getElementById('assessmentStep').style.display = 'none';
        document.getElementById('assessmentFooter').style.display = 'none';
        document.getElementById('searchResults').innerHTML = '';
        
        // Reset form
        document.getElementById('patientSearch').value = '';
        document.getElementById('serviceType').value = '';
        document.getElementById('temp').value = '';
        document.getElementById('bp').value = '';
        document.getElementById('weight').value = '';
        document.getElementById('height').value = '';
        document.querySelectorAll('input[name="symptoms[]"]').forEach(cb => cb.checked = false);
        
        // Reset button
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.style.opacity = "0.5";
        btn.style.cursor = "not-allowed";
    }

    // View Modal
    function openTriageModal(btn) {
        const p = JSON.parse(btn.dataset.patient);
        const r = JSON.parse(btn.dataset.record);

        document.getElementById('vtName').innerText = `${p.first_name||''} ${p.last_name||''}`.trim() || 'Unknown';
        document.getElementById('vtMeta').innerText = `ID: ${p.patient_id||'---'} | Age: ${p.age||'---'} | ${p.barangay||'---'}`;
        document.getElementById('vtRisk').innerText = `${r.risk_level||'Low'} Risk`;
        document.getElementById('vtRisk').className = 'pill ' + (r.risk_level||'low').toLowerCase();
        document.getElementById('vtReason').innerText = r.service_type||'Consultation';
        
        document.getElementById('vtTemp').innerText = r.temp ? r.temp + ' °C' : '---';
        document.getElementById('vtBp').innerText = r.bp || '---';
        document.getElementById('vtWtHt').innerText = `${r.weight||'---'} kg / ${r.height||'---'} cm`;
        document.getElementById('vtSymptoms').innerText = r.symptoms||'No symptoms recorded.';

        document.getElementById('viewTriageModal').style.display = 'flex';
    }
    function closeTriageModal() {
        document.getElementById('viewTriageModal').style.display = 'none';
    }

    // Update Status
    function updateQueueStatus(id, status) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        fetch(`/triage/update-status/${id}`, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':token},
            body: JSON.stringify({status:status})
        })
        .then(res => res.json())
        .then(d => {
            if(d.success) window.location.reload();
            else alert('Error: '+d.message);
        });
    }

    // Search Function
    const searchInput = document.getElementById('patientSearch');
    if(searchInput){
        searchInput.addEventListener('keypress', e=>{
            if(e.key==='Enter'){
                e.preventDefault();
                const q = e.target.value.trim();
                if(q.length<2)return;
                fetch(`/patients/search?q=${encodeURIComponent(q)}`)
                .then(res=>res.json())
                .then(data=>{
                    const c = document.getElementById('searchResults');
                    c.innerHTML='';
                    if(!data.length){c.innerHTML='<div style="padding:1rem; color:#666;">No results found</div>';return;}
                    data.forEach(p=>{
                        const d=document.createElement('div');
                        d.style.padding='.75rem';
                        d.style.borderBottom='1px solid #eee';
                        d.style.cursor='pointer';
                        d.innerHTML=`<strong>${p.first_name} ${p.last_name}</strong><br>ID: ${p.patient_id} | Age: ${p.age} | ${p.barangay}`;
                        d.onclick=()=>{
                            document.getElementById('searchStep').style.display='none';
                            document.getElementById('assessmentStep').style.display='block';
                            document.getElementById('assessmentFooter').style.display='flex';
                            document.getElementById('selectedPatientId').value=p.id;
                            document.getElementById('dispName').innerText=`${p.first_name} ${p.last_name}`;
                            document.getElementById('dispMeta').innerText=`ID: ${p.patient_id} | Age: ${p.age} | ${p.barangay}`;
                            
                            // ✅ CHECK IMMEDIATELY AFTER SELECT
                            checkFormCompletion();
                        };
                        c.appendChild(d);
                    });
                });
            }
        });
    }

    // ✅ FORM CHECKER - THE MOST IMPORTANT PART
    function checkFormCompletion() {
    const patientId = document.getElementById('selectedPatientId').value.trim();
    const service = document.getElementById('serviceType').value.trim();

    const submitBtn = document.getElementById('submitBtn');

    // Patient and service type are required to add to queue
    if (patientId && service) {

        submitBtn.disabled = false;
        submitBtn.style.opacity = "1";
        submitBtn.style.cursor = "pointer";
        submitBtn.style.pointerEvents = "auto";
        submitBtn.classList.add('active');

    } else {

        submitBtn.disabled = true;
        submitBtn.style.opacity = "0.5";
        submitBtn.style.cursor = "not-allowed";
        submitBtn.style.pointerEvents = "none";
        submitBtn.classList.remove('active');

    }
}

    // ✅ ATTACH CHECKER TO ALL INPUTS
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('serviceType').addEventListener('change', checkFormCompletion);
        document.getElementById('temp').addEventListener('input', checkFormCompletion);
        document.getElementById('bp').addEventListener('input', checkFormCompletion);
        document.getElementById('weight').addEventListener('input', checkFormCompletion);
        document.getElementById('height').addEventListener('input', checkFormCompletion);
        
        document.querySelectorAll('input[name="symptoms[]"]').forEach(cb => {
            cb.addEventListener('change', checkFormCompletion);
        });
    });

    // Filter
    function filterTriage(risk){
        document.querySelectorAll('tbody tr').forEach(row=>{
            const r = row.dataset.risk;
            const s = row.dataset.status;
            if(s==='done'||s==='consulted'){row.style.display='none';return;}
            row.style.display = (risk==='all'||r===risk) ? '' : 'none';
        });
    }

    // Live Update (BHW/NURSE)
    @if(strtolower(auth()->user()->role) !== 'doctor')
    setInterval(()=>{
        fetch('/triage/live-data')
        .then(res=>res.json())
        .then(d=>{
            const tb = document.querySelector('.triage-table tbody');
            if(tb && d.html){
                tb.innerHTML = d.html;
                tb.querySelectorAll('.btn-action').forEach(btn=>{
                    if(btn.innerText.includes('Call')||btn.innerText.includes('Start')||btn.innerText.includes('Done')) btn.style.display='none';
                });
            }
            if(d.active_call && localStorage.getItem('last_call') != d.call_id){
                localStorage.setItem('last_call', d.call_id);
                const n = document.createElement('div');
                n.style.cssText='position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#222;color:#fff;padding:1rem 1.5rem;border-radius:6px;z-index:9999;';
                n.innerHTML=`<strong>🔊 NOW CALLING</strong><br>${d.patient_name}`;
                document.body.appendChild(n);
                setTimeout(()=>n.remove(),5000);
                if('speechSynthesis' in window) speechSynthesis.speak(new SpeechSynthesisUtterance(`Patient ${d.patient_name} is now being called`));
            }
        });
    }, 3000);
    @endif

    // Close on Escape
    document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeTriageModal();toggleModal(false);}});
</script>
</body>
</html>
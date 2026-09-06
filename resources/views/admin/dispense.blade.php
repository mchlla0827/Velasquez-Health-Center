<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dispense Medicine | {{ ucfirst($role) }}</title>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #F9FAFB;
        overflow-x: hidden;
    }

    .container { display: flex; }

    /* ================= SIDEBAR ================= */
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

    .brand-wrapper { display: flex; flex-direction: column; line-height: 1.2; }

    .brand { font-weight: bold; color: #1E3A8A; font-size: 13.3px; }

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

    .nav-icon { width: 22px; height: 22px; object-fit: contain; }

    .nav-item.active {
        background: #EFF6FF;
        color: #1A73E8;
        border-left: 4px solid #1A73E8;
        font-weight: bold;
    }

    .nav-item:hover { background: #F3F4F6; }

    .nav-btn {
        width: 100%;
        text-align: left;
        border: none;
        background: transparent;
        cursor: pointer;
        font-family: inherit;
    }

    /* ================= MAIN CONTENT ================= */
    .main {
        margin-left: 260px;
        width: calc(100% - 260px);
        padding: 24px;
        box-sizing: border-box;
    }

    /* HEADER */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .welcome-text { font-size: 14px; color: #374151; margin-bottom: 5px; }

    .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }

    .role {
        background: #10B981;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
        text-transform: uppercase;
    }
    .role-admin { background: #9333EA; }
    .role-nurse { background: #10B981; }

    .right { text-align: right; font-size: 12px; color: #374151; }

    .header-divider {
        width: 100%;
        height: 1px;
        background: #E5E7EB;
        margin: 16px 0;
    }

    /* PAGE TITLE ROW */
    .page-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: -0.02em;
        margin: 0;
    }

    /* ================= FILTERS CARD ================= */
    .filter-card {
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 16px;
    }
    .filter-actions {
        display: flex;
        align-items: flex-end;
    }
    .clear-filters-btn {
        width: 100%;
        min-height: 42px;
        padding: 10px 14px;
        border: 1px solid #93C5FD;
        border-radius: 8px;
        background: #EFF6FF;
        color: #1D4ED8;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
    }
    .clear-filters-btn:hover {
        background: #DBEAFE;
        border-color: #60A5FA;
        color: #1E40AF;
    }
    .clear-filters-btn:focus-visible {
        outline: none;
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
    }
    .clear-filters-btn:active { background: #BFDBFE; }

    /* ================= TABLE CARD ================= */
    .table-card {
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .table-header-title {
        padding: 16px 20px;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        border-bottom: 1px solid #E5E7EB;
        background: #FAFAFA;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 14px;
    }

    th, td {
        padding: 14px 20px;
        border-bottom: 1px solid #E5E7EB;
        color: #374151;
        vertical-align: middle;
    }

    th {
        background: #F9FAFB;
        font-weight: 600;
        color: #4B5563;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    tbody tr:hover { background-color: #F9FAFB; }

    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .status-badge.active { background: #DCFCE7; color: #15803D; }
    .status-badge.voided { background: #FEE2E2; color: #B91C1C; }

    /* ================= FORM ELEMENTS ================= */
    label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: 6px;
    }

    input, select, textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 13px;
        background-color: #ffffff;
        color: #0F172A;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        font-family: inherit;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #1A73E8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.15);
    }

    input[readonly] {
        background-color: #F3F4F6;
        color: #475569;
        border-color: #E2E8F0;
        cursor: not-allowed;
    }

    /* ================= BUTTONS ================= */
    .btn-primary {
        background-color: #1A73E8;
        color: white;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.15s ease;
    }
    .btn-primary:hover { background-color: #1557B0; }

    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn {
        background: transparent;
        border: none;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .view-btn { color: #2563EB; }
    .view-btn:hover { background: #EFF6FF; }
    .edit-btn { color: #059669; }
    .edit-btn:hover { background: #ECFDF5; }
    .void-btn { color: #EF4444; }
    .void-btn:hover { background: #FEF2F2; }

    .record-btn {
        background-color: #10B981;
        color: #FFFFFF;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .record-btn:hover:not(:disabled) { background-color: #059669; }
    .record-btn:disabled { background-color: #CBD5E1; color: #94A3B8; cursor: not-allowed; }

    .clear-btn {
        background-color: #F1F5F9;
        color: #475569;
        font-weight: 500;
        font-size: 14px;
        padding: 10px 18px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        cursor: pointer;
    }
    .clear-btn:hover { background-color: #E2E8F0; }

    /* ================= MODAL SYSTEM ================= */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .modal-overlay.show { display: flex; opacity: 1; }

    .modal-box {
        background: #ffffff;
        border-radius: 12px;
        width: 90%;
        max-width: 900px;
        max-height: 90vh;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title { font-size: 18px; font-weight: bold; color: #0F172A; margin: 0; }
    .close-btn { font-size: 22px; color: #94A3B8; cursor: pointer; background: none; border: none; }
    .close-btn:hover { color: #0F172A; }
    .modal-body { padding: 24px; }

    /* GRID UTILITIES */
    .form-section-block { margin-bottom: 24px; }
    .section-title { font-weight: 700; color: #1E293B; margin-bottom: 12px; font-size: 15px; }
    .divider { height: 1px; background: #E5E7EB; margin-bottom: 16px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .field.full { grid-column: span 2; }
    .buttons { padding-top: 16px; border-top: 1px solid #E2E8F0; display: flex; justify-content: flex-end; gap: 12px; }

    /* RESPONSIVE MEDIA QUERIES */
    @media (max-width: 1100px) {
        .filter-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .filter-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .filter-grid, .grid-2 { grid-template-columns: 1fr; }
        .field.full { grid-column: span 1; }
    }
</style>
</head>

<body>

<div class="container">

<!-- SIDEBAR -->
<x-sidebar />

<!-- MAIN CONTENT -->
<div class="main">

    <!-- HEADER -->
    <div class="header">
        <div>
            <div class="welcome-text">Welcome back,</div>
            <div class="welcome-name">
                {{ $userName }}
                <span class="role role-{{ strtolower($role) }}">{{ strtoupper($role) }}</span>
            </div>
        </div>

        <div class="right">
            <b>Velasquez Health Center</b><br>
            @php date_default_timezone_set('Asia/Manila'); @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>

    <div class="header-divider"></div>

    <!-- PAGE TITLE & TOP ACTION BUTTON -->
    <div class="page-title-row">
        <h2 class="page-title">Dispense Medicine</h2>
        <button type="button" class="btn-primary" id="openDispenseModal">
            + New Dispense
        </button>
    </div>

    <!-- FILTERS CARD -->
<div class="filter-card">
    <div class="filter-grid">

        <div>
            <label>Search</label>
            <input type="text" id="searchInput" placeholder="Search record...">
        </div>

        <div>
            <label>Date Filter</label>
            <input type="date" id="dateFilter">
        </div>

        <div>
            <label>Patient Filter</label>
            <select id="patientFilter">
                <option value="">All Patients</option>

                @foreach($patients as $p)
                    <option value="{{ $p->patient_id ?? $p->ptn ?? '' }}">
                        {{ $p->first_name }} {{ $p->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Medicine Filter</label>
            <select id="medicineFilter">
                <option value="">All Medicines</option>

                @foreach($medicines as $med)
                    <option value="{{ $med->id }}">
                        {{ $med->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Staff Filter</label>
            <select id="staffFilter">
                <option value="">All Staff</option>

                @foreach($staffMembers ?? [] as $staff)
                    <option value="{{ $staff->name }}">
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-actions">
            <button type="button" class="clear-filters-btn" id="clearFiltersBtn">Clear Filters</button>
        </div>

    </div>
</div>

    <!-- DISPENSING HISTORY TABLE -->
    <div class="table-card">
        <div class="table-header-title">Dispensing History</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient</th>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Dispensed By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dispensingTableBody">

    @foreach($dispensingHistory as $record)

        <tr class="dispensing-row"
    data-id="{{ $record->id }}"
    data-status="{{ $record->status }}"
    data-date="{{ $record->dispense_date }}"
    data-patient-ptn="{{ $record->patient_ptn }}"
    data-patient-name="{{ $record->patient_name }}"
    data-medicine-id="{{ $record->medicine_id }}"
    data-medicine-name="{{ $record->medicine->name ?? '' }}"
    data-quantity="{{ $record->quantity_dispensed }}"
    data-unit="{{ $record->unit }}"
    data-diagnosis="{{ $record->diagnosis }}"
    data-staff="{{ $record->dispensed_by }}">

            <td>
                {{ $record->dispense_date }}
            </td>

            <td>
                {{ $record->patient_name }}
            </td>

            <td>
                {{ $record->medicine->name ?? '' }}
            </td>

            <td>
                {{ $record->quantity_dispensed }}
            </td>

            <td>
                {{ $record->dispensed_by }}
            </td>

            <td>
    @if($record->status === 'VOIDED')
        <span class="status-badge voided">VOIDED</span>
    @else
        <span class="status-badge active">ACTIVE</span>
    @endif
</td>

            <td>
                <div class="action-group">

                    <button type="button"
                            class="action-btn view-btn view-record-btn"
                            data-date="{{ $record->dispense_date }}"
                            data-patient="{{ $record->patient_name }}"
                            data-medicine="{{ $record->medicine->name ?? '' }}"
                            data-quantity="{{ $record->quantity_dispensed }}"
                            data-dispensedby="{{ $record->dispensed_by }}"
                            data-diagnosis="{{ $record->diagnosis ?? 'N/A' }}">
                        View
                    </button>

                    <button type="button"
                            class="action-btn edit-btn"
                            onclick="editRecord({{ $record->id }})">
                        Edit
                    </button>

                    <button type="button"
                            class="action-btn void-btn"
                            onclick="confirmVoid({{ $record->id }})">
                        Void
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>
        </table>
    </div>

</div>
</div>

<!-- ================= NEW DISPENSE MODAL ================= -->
<div id="dispenseModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="modal-title">New Dispense Medicine</h3>
            <button class="close-btn" id="closeDispenseModal">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route($role . '.dispense.save') }}" id="dispenseForm">
                @csrf

                <!-- SELECT PATIENT SECTION -->
                <div class="form-section-block">
                    <div class="section-title">Select Patient</div>
                    <div class="divider"></div>
                    <div class="field full">
                        <select id="patient_select" name="patient_id" required>
                            <option value="">-- Select Patient --</option>
                            @foreach($patients as $p)
                                <option
                                    value="{{ $p->id }}"
                                    data-ptn="{{ $p->patient_id ?? $p->ptn ?? '' }}"
                                    data-family="{{ $p->family_number ?? $p->family_no ?? '' }}"
                                    data-brgy="{{ $p->barangay ?? '' }}"
                                    data-name="{{ trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')) }}"
                                    data-age="{{ $p->age ?? '' }}"
                                    data-sex="{{ $p->sex ?? $p->gender ?? '' }}"
                                    data-address="{{ $p->address ?? '' }}"
                                    data-philhealth="{{ $p->philhealth_no ?? $p->philhealth ?? '' }}"
                                >
                                    {{ $p->patient_id ?? 'NO ID' }} - {{ $p->first_name ?? '' }} {{ $p->last_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- PATIENT INFORMATION SECTION -->
                <div class="form-section-block">
                    <div class="section-title">Patient Information</div>
                    <div class="divider"></div>
                    <div class="grid-2">
                        <div class="field">
                            <label>Patient PTN / ID</label>
                            <input type="text" name="patient_ptn" id="patient_ptn" readonly required>
                        </div>
                        <div class="field">
                            <label>Family No.</label>
                            <input type="text" name="family_no" id="family_no" readonly required>
                        </div>
                        <div class="field">
                            <label>Barangay</label>
                            <input type="text" name="barangay" id="barangay" readonly required>
                        </div>
                        <div class="field">
                            <label>Date</label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" readonly required>
                        </div>
                        <div class="field full">
                            <label>Patient Name</label>
                            <input type="text" name="patient_name" id="patient_name" readonly required>
                        </div>
                        <div class="field">
                            <label>Age</label>
                            <input type="number" name="age" id="age" readonly required>
                        </div>
                        <div class="field">
                            <label>Sex</label>
                            <input type="text" name="sex" id="sex" readonly required>
                        </div>
                        <div class="field full">
                            <label>Address</label>
                            <input type="text" name="address" id="address" readonly required>
                        </div>
                        <div class="field">
                            <label>PhilHealth No.</label>
                            <input type="text" name="philhealth_no" id="philhealth_no" readonly>
                        </div>
                    </div>
                </div>

                <!-- MEDICAL INFORMATION SECTION -->
                <div class="form-section-block">
                    <div class="section-title">Medical Information</div>
                    <div class="divider"></div>
                    <div class="field full">
                        <label>Diagnosis / Purpose</label>
                        <input type="text" name="diagnosis" id="diagnosis" placeholder="Enter diagnosis..." required>
                    </div>
                </div>

                <!-- MEDICINE INFORMATION SECTION -->
                <div class="form-section-block">
                    <div class="section-title">Medicine Information</div>
                    <div class="divider"></div>
                    <div class="field full">
                        <label>Select Medicine</label>
                        <select name="medicine_id" id="medicine_id" required>
                            <option value="">-- Select Medicine --</option>
                            @foreach($medicines as $med)
                                <option
                                    value="{{ $med->id }}"
                                    data-unit="{{ $med->unit ?? 'TABLET' }}"
                                    data-stock="{{ $med->stock ?? 0 }}"
                                >
                                    {{ $med->name }} — Stock: {{ $med->stock ?? 0 }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid-2" style="margin-top: 16px;">
                        <div class="field">
                            <label>Quantity to Dispense</label>
                            <input type="number" name="quantity" id="quantity" min="1" placeholder="e.g. 10" required>
                        </div>
                        <div class="field">
                            <label>Unit</label>
                            <input type="text" name="unit" id="unit" readonly required>
                        </div>
                    </div>
                </div>

                <!-- CONFIRMATION SECTION -->
                <div class="form-section-block">
                    <div class="section-title">Confirmation</div>
                    <div class="divider"></div>
                    <div class="field" style="max-width: 50%;">
                        <label>Dispensed by (Staff Initials / Name)</label>
                        <input type="text" name="dispensed_by" id="dispensed_by" value="{{ auth()->user()->name }}" readonly required>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="buttons">
                    <button type="submit" class="record-btn" id="recordBtn" disabled>Record Dispensing</button>
                    <button type="button" class="clear-btn" id="clearBtn">Clear Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= VIEW RECORD MODAL ================= -->
<div id="viewModal" class="modal-overlay">
    <div class="modal-box" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title">Dispensing Record Details</h3>
            <button class="close-btn" id="closeViewModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="grid-2">
                <div class="field">
                    <label>Date</label>
                    <input type="text" id="viewDate" readonly>
                </div>
                <div class="field">
                    <label>Dispensed By</label>
                    <input type="text" id="viewDispensedBy" readonly>
                </div>
                <div class="field full">
                    <label>Patient Name</label>
                    <input type="text" id="viewPatient" readonly>
                </div>
                <div class="field">
                    <label>Medicine</label>
                    <input type="text" id="viewMedicine" readonly>
                </div>
                <div class="field">
                    <label>Quantity</label>
                    <input type="text" id="viewQuantity" readonly>
                </div>
                <div class="field full">
                    <label>Diagnosis / Purpose</label>
                    <input type="text" id="viewDiagnosis" readonly>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= EDIT DISPENSING MODAL ================= -->
<!-- FIX: classes now match the modal-overlay / modal-box system used everywhere else -->
<div id="editDispenseModal" class="modal-overlay">
    <div class="modal-box" style="max-width: 700px;">

        <div class="modal-header">
            <h3 class="modal-title">Edit Dispensing Record</h3>
            <button type="button" id="closeEditDispenseModal" class="close-btn">&times;</button>
        </div>

        <div class="modal-body">
            <form id="editDispenseForm" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section-block">
                    <div class="grid-2">

                        <div class="field">
                            <label>Patient</label>
                            <input type="text" id="edit_patient_name" readonly>
                        </div>

                        <div class="field">
                            <label>Patient PTN</label>
                            <input type="text" id="edit_patient_ptn" readonly>
                        </div>

                        <div class="field">
                            <label>Date</label>
                            <input type="date" name="date" id="edit_date" required>
                        </div>

                        <div class="field">
                            <label>Medicine</label>
                            <select name="medicine_id" id="edit_medicine_id" required>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" data-unit="{{ $medicine->unit }}">
                                        {{ $medicine->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Quantity</label>
                            <input type="number" name="quantity" id="edit_quantity" min="1" required>
                        </div>

                        <div class="field">
                            <label>Unit</label>
                            <input type="text" name="unit" id="edit_unit" readonly required>
                        </div>

                        <div class="field full">
                            <label>Diagnosis</label>
                            <textarea name="diagnosis" id="edit_diagnosis" required></textarea>
                        </div>

                    </div>
                </div>

                <div class="buttons">
                    <button type="button" id="cancelEditDispense" class="clear-btn">Cancel</button>
                    <button type="submit" class="record-btn">Save Changes</button>
                </div>

            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Role-aware route templates — mirrors how the save form uses route($role.'.dispense.save').
    // We pass BOTH 'role' and 'dispense' params so this works whether your route URI is
    // "/{role}/dispense/{dispense}" (role consumed as a path segment) or a literal
    // prefixed URI like "/nurse/dispense/{dispense}" (extra 'role' param is ignored).
    const dispenseRoutes = {
        update: "{{ route($role . '.dispense.update', ['role' => $role, 'dispense' => 'ID_PLACEHOLDER']) }}",
        void:   "{{ route($role . '.dispense.void',   ['role' => $role, 'dispense' => 'ID_PLACEHOLDER']) }}",
    };

    function buildRoute(name, id) {
        return dispenseRoutes[name].replace('ID_PLACEHOLDER', id);
    }

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       MODALS
    ========================================================= */

    const dispenseModal = document.getElementById('dispenseModal');
    const openDispenseModal = document.getElementById('openDispenseModal');
    const closeDispenseModal = document.getElementById('closeDispenseModal');

    const viewModal = document.getElementById('viewModal');
    const closeViewModal = document.getElementById('closeViewModal');

    const editDispenseModal = document.getElementById('editDispenseModal');
    const closeEditDispenseModal = document.getElementById('closeEditDispenseModal');
    const cancelEditDispense = document.getElementById('cancelEditDispense');


    /* =========================================================
       DISPENSE MODAL TOGGLE
    ========================================================= */

    if (openDispenseModal && dispenseModal) {
        openDispenseModal.addEventListener('click', function () {
            dispenseModal.classList.add('show');
        });
    }

    if (closeDispenseModal && dispenseModal) {
        closeDispenseModal.addEventListener('click', function () {
            dispenseModal.classList.remove('show');
        });
    }


    /* =========================================================
       VIEW MODAL TOGGLE
    ========================================================= */

    if (closeViewModal && viewModal) {
        closeViewModal.addEventListener('click', function () {
            viewModal.classList.remove('show');
        });
    }


    /* =========================================================
       EDIT MODAL TOGGLE
    ========================================================= */

    if (closeEditDispenseModal && editDispenseModal) {
        closeEditDispenseModal.addEventListener('click', function () {
            editDispenseModal.classList.remove('show');
        });
    }

    if (cancelEditDispense && editDispenseModal) {
        cancelEditDispense.addEventListener('click', function () {
            editDispenseModal.classList.remove('show');
        });
    }


    /* =========================================================
       CLOSE MODALS WHEN CLICKING OUTSIDE
    ========================================================= */

    window.addEventListener('click', function (event) {

        if (dispenseModal && event.target === dispenseModal) {
            dispenseModal.classList.remove('show');
        }

        if (viewModal && event.target === viewModal) {
            viewModal.classList.remove('show');
        }

        if (editDispenseModal && event.target === editDispenseModal) {
            editDispenseModal.classList.remove('show');
        }

    });


    /* =========================================================
       DISPENSE FORM
    ========================================================= */

    const form = document.getElementById('dispenseForm');
    const patientSelect = document.getElementById('patient_select');
    const medicineSelect = document.getElementById('medicine_id');
    const qtyInput = document.getElementById('quantity');
    const recordBtn = document.getElementById('recordBtn');
    const clearBtn = document.getElementById('clearBtn');

    const requiredIds = [
        'patient_ptn',
        'family_no',
        'barangay',
        'date',
        'patient_name',
        'age',
        'sex',
        'address',
        'diagnosis',
        'medicine_id',
        'quantity',
        'unit',
        'dispensed_by'
    ];


    /* =========================================================
       CLEAR PATIENT FIELDS
    ========================================================= */

    function clearPatientFields() {

        [
            'patient_ptn',
            'family_no',
            'barangay',
            'patient_name',
            'age',
            'sex',
            'address',
            'philhealth_no'
        ].forEach(function (id) {

            const el = document.getElementById(id);

            if (el) {
                el.value = '';
            }

        });

    }


    /* =========================================================
       CLEAR MEDICINE FIELDS
    ========================================================= */

    function clearMedicineFields() {

        const unit = document.getElementById('unit');

        if (unit) {
            unit.value = '';
        }

        if (qtyInput) {
            qtyInput.value = '';
            qtyInput.removeAttribute('max');
            qtyInput.placeholder = 'e.g. 10';
        }

    }


    /* =========================================================
       PATIENT SELECTION
    ========================================================= */

    if (patientSelect) {

        patientSelect.addEventListener('change', function () {

            const opt = this.selectedOptions[0];

            if (!opt || !opt.value) {

                clearPatientFields();
                checkReady();

                return;
            }

            const patientPtn = document.getElementById('patient_ptn');
            const familyNo = document.getElementById('family_no');
            const barangay = document.getElementById('barangay');
            const patientName = document.getElementById('patient_name');
            const age = document.getElementById('age');
            const sex = document.getElementById('sex');
            const address = document.getElementById('address');
            const philhealth = document.getElementById('philhealth_no');

            if (patientPtn) {
                patientPtn.value = opt.dataset.ptn || '';
            }

            if (familyNo) {
                familyNo.value = opt.dataset.family || '';
            }

            if (barangay) {
                barangay.value = opt.dataset.brgy || '';
            }

            if (patientName) {
                patientName.value = opt.dataset.name || '';
            }

            if (age) {
                age.value = opt.dataset.age || '';
            }

            if (sex) {
                sex.value = opt.dataset.sex || '';
            }

            if (address) {
                address.value = opt.dataset.address || '';
            }

            if (philhealth) {
                philhealth.value = opt.dataset.philhealth || '';
            }

            checkReady();

        });

    }


    /* =========================================================
       MEDICINE SELECTION
    ========================================================= */

    if (medicineSelect) {

        medicineSelect.addEventListener('change', function () {

            const opt = this.selectedOptions[0];

            if (!opt || !opt.value) {

                clearMedicineFields();
                checkReady();

                return;
            }

            const unit = document.getElementById('unit');

            if (unit) {
                unit.value = opt.dataset.unit || 'TABLET';
            }

            const stock = parseInt(opt.dataset.stock) || 0;

            if (qtyInput) {

                qtyInput.max = stock;
                qtyInput.placeholder = `Max: ${stock}`;

            }

            checkReady();

        });

    }


    /* =========================================================
       CHECK DISPENSE FORM
    ========================================================= */

    function checkReady() {

        if (!form || !recordBtn) {
            return;
        }

        let isReady = true;

        requiredIds.forEach(function (id) {

            const el = document.getElementById(id);

            if (
                !el ||
                el.value === null ||
                el.value.toString().trim() === ''
            ) {
                isReady = false;
            }

        });

        if (qtyInput) {

            const val = parseInt(qtyInput.value);
            const max = parseInt(qtyInput.max);

            if (
                isNaN(val) ||
                val <= 0 ||
                (!isNaN(max) && max > 0 && val > max)
            ) {
                isReady = false;
            }

        }

        recordBtn.disabled = !isReady;

    }


    /* =========================================================
       DISPENSE FORM EVENTS
    ========================================================= */

    if (form) {

        form.addEventListener('input', checkReady);
        form.addEventListener('change', checkReady);
        form.addEventListener('keyup', checkReady);

    }


    /* =========================================================
       CLEAR DISPENSE FORM
    ========================================================= */

    if (clearBtn) {

        clearBtn.addEventListener('click', function (e) {

            e.preventDefault();

            if (patientSelect) {
                patientSelect.value = '';
            }

            if (medicineSelect) {
                medicineSelect.value = '';
            }

            clearPatientFields();
            clearMedicineFields();

            const diagnosis = document.getElementById('diagnosis');

            if (diagnosis) {
                diagnosis.value = '';
            }

            checkReady();

        });

    }


    /* =========================================================
       VIEW DISPENSING RECORD
    ========================================================= */

    document.querySelectorAll('.view-record-btn').forEach(function (button) {

        button.addEventListener('click', function () {

            const viewDate = document.getElementById('viewDate');
            const viewPatient = document.getElementById('viewPatient');
            const viewMedicine = document.getElementById('viewMedicine');
            const viewQuantity = document.getElementById('viewQuantity');
            const viewDispensedBy = document.getElementById('viewDispensedBy');
            const viewDiagnosis = document.getElementById('viewDiagnosis');

            if (viewDate) {
                viewDate.value = this.dataset.date || '';
            }

            if (viewPatient) {
                viewPatient.value = this.dataset.patient || '';
            }

            if (viewMedicine) {
                viewMedicine.value = this.dataset.medicine || '';
            }

            if (viewQuantity) {
                viewQuantity.value = this.dataset.quantity || '';
            }

            if (viewDispensedBy) {
                viewDispensedBy.value = this.dataset.dispensedby || '';
            }

            if (viewDiagnosis) {
                viewDiagnosis.value = this.dataset.diagnosis || 'N/A';
            }

            if (viewModal) {
                viewModal.classList.add('show');
            }

        });

    });


    /* =========================================================
       EDIT MEDICINE UNIT
    ========================================================= */

    const editMedicineSelect =
        document.getElementById('edit_medicine_id');

    const editUnitInput =
        document.getElementById('edit_unit');

    if (editMedicineSelect) {

        editMedicineSelect.addEventListener('change', function () {

            const selectedOption =
                this.options[this.selectedIndex];

            if (editUnitInput) {

                editUnitInput.value =
                    selectedOption.dataset.unit || '';

            }

        });

    }


    /* =========================================================
       FILTER LOGIC
    ========================================================= */

    const searchInput = document.getElementById('searchInput');
    const dateFilter = document.getElementById('dateFilter');
    const patientFilter = document.getElementById('patientFilter');
    const medicineFilter = document.getElementById('medicineFilter');
    const staffFilter = document.getElementById('staffFilter');
    const clearFiltersBtn =
        document.getElementById('clearFiltersBtn');

    const dispensingRows =
        document.querySelectorAll('.dispensing-row');


    function filterRecords() {

        const searchValue =
            searchInput
                ? searchInput.value.toLowerCase().trim()
                : '';

        const dateValue =
            dateFilter
                ? dateFilter.value
                : '';

        const patientValue =
            patientFilter
                ? patientFilter.value.trim()
                : '';

        const medicineValue =
            medicineFilter
                ? medicineFilter.value.trim()
                : '';

        const staffValue =
            staffFilter
                ? staffFilter.value.toLowerCase().trim()
                : '';


        dispensingRows.forEach(function (row) {

            const rowDate =
                (row.dataset.date || '').trim();

            const patientPtn =
                (row.dataset.patientPtn || '')
                    .trim()
                    .toLowerCase();

            const patientName =
                (row.dataset.patientName || '')
                    .trim()
                    .toLowerCase();

            const medicineId =
                (row.dataset.medicineId || '').trim();

            const medicineName =
                (row.dataset.medicineName || '')
                    .trim()
                    .toLowerCase();

            const staffName =
                (row.dataset.staff || '')
                    .trim()
                    .toLowerCase();


            /* SEARCH */

            const matchesSearch =
                !searchValue ||
                patientName.includes(searchValue) ||
                patientPtn.includes(searchValue) ||
                medicineName.includes(searchValue) ||
                staffName.includes(searchValue);


            /* DATE */

            const matchesDate =
                !dateValue ||
                rowDate === dateValue;


            /* PATIENT */

            const matchesPatient =
                !patientValue ||
                patientPtn === patientValue.toLowerCase();


            /* MEDICINE */

            const matchesMedicine =
                !medicineValue ||
                medicineId === medicineValue;


            /* STAFF */

            const matchesStaff =
                !staffValue ||
                staffName.includes(staffValue);


            /* FINAL RESULT */

            const isVisible =
                matchesSearch &&
                matchesDate &&
                matchesPatient &&
                matchesMedicine &&
                matchesStaff;


            row.style.display =
                isVisible ? '' : 'none';

        });

    }


    /* =========================================================
       FILTER EVENTS
    ========================================================= */

    if (searchInput) {
        searchInput.addEventListener(
            'input',
            filterRecords
        );
    }

    if (dateFilter) {
        dateFilter.addEventListener(
            'change',
            filterRecords
        );
    }

    if (patientFilter) {
        patientFilter.addEventListener(
            'change',
            filterRecords
        );
    }

    if (medicineFilter) {
        medicineFilter.addEventListener(
            'change',
            filterRecords
        );
    }

    if (staffFilter) {
        staffFilter.addEventListener(
            'change',
            filterRecords
        );
    }


    /* =========================================================
       CLEAR FILTERS
    ========================================================= */

    if (clearFiltersBtn) {

        clearFiltersBtn.addEventListener('click', function () {

            if (searchInput) {
                searchInput.value = '';
            }

            if (dateFilter) {
                dateFilter.value = '';
            }

            if (patientFilter) {
                patientFilter.value = '';
            }

            if (medicineFilter) {
                medicineFilter.value = '';
            }

            if (staffFilter) {
                staffFilter.value = '';
            }

            filterRecords();

        });

    }


    /* =========================================================
       INITIAL FORM CHECK
    ========================================================= */

    checkReady();


    /* =========================================================
       SWEETALERT SUCCESS
    ========================================================= */

    @if(session('success'))

        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: @json(session('success'))
        });

    @endif


    /* =========================================================
       SWEETALERT ERROR
    ========================================================= */

    @if(session('error'))

        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: @json(session('error'))
        });

    @endif

});


/* =============================================================
   EDIT DISPENSING RECORD
============================================================= */

function editRecord(recordId) {
    const row = document.querySelector(`.dispensing-row[data-id="${recordId}"]`);

    if (!row) {
        console.error('Dispensing record not found:', recordId);
        return;
    }

    if (row.dataset.status === 'VOIDED') {
        Swal.fire({
            icon: 'warning',
            title: 'Record is voided',
            text: 'A voided dispensing record cannot be edited.'
        });
        return;
    }

    const modal = document.getElementById('editDispenseModal');
    const form = document.getElementById('editDispenseForm');

    if (!modal || !form) {
        console.error('Edit modal or form not found.');
        return;
    }

    document.getElementById('edit_patient_name').value = row.dataset.patientName || '';
    document.getElementById('edit_patient_ptn').value = row.dataset.patientPtn || '';
    document.getElementById('edit_date').value = row.dataset.date || '';
    document.getElementById('edit_medicine_id').value = row.dataset.medicineId || '';
    document.getElementById('edit_quantity').value = row.dataset.quantity || '';
    document.getElementById('edit_unit').value = row.dataset.unit || '';
    document.getElementById('edit_diagnosis').value = row.dataset.diagnosis || '';

    // Role-aware route instead of a hardcoded URL
    form.action = buildRoute('update', recordId);

    modal.classList.add('show');
}

/* =============================================================
   VOID DISPENSING RECORD
============================================================= */

function confirmVoid(recordId) {
    const row = document.querySelector(`.dispensing-row[data-id="${recordId}"]`);

    if (!row) {
        Swal.fire({ icon: 'error', title: 'Record Not Found', text: 'The dispensing record could not be found.' });
        return;
    }

    if (row.dataset.status === 'VOIDED') {
        Swal.fire({ icon: 'info', title: 'Already Voided', text: 'This dispensing record has already been voided.' });
        return;
    }

    Swal.fire({
        title: 'Void Dispensing Record?',
        text: 'This will return the dispensed medicine to inventory. The record will remain in the history as VOIDED.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, void record',
        cancelButtonText: 'Cancel'
    }).then(function (result) {
        if (!result.isConfirmed) return;

        const form = document.createElement('form');
        form.method = 'POST';

        // Role-aware route instead of a hardcoded URL
        form.action = buildRoute('void', recordId);

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        csrf.value = csrfMeta
            ? csrfMeta.getAttribute('content')
            : (document.querySelector('input[name="_token"]')?.value || '');

        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    });
}
</script>

</body>
</html>
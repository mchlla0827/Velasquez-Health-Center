<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    /* ================= FORM ELEMENTS ================= */
    label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: 6px;
    }

    input, select {
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
    }

    input:focus, select:focus {
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

    .btn-secondary {
        background-color: #fff;
        color: #374151;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 18px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.15s ease;
    }
    .btn-secondary:hover { background-color: #F3F4F6; }

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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dispensingTableBody">

    @foreach($dispensingHistory as $record)

        <tr class="dispensing-row"
            data-date="{{ $record->dispense_date }}"
            data-patient-ptn="{{ $record->patient_ptn }}"
            data-patient-name="{{ $record->patient_name }}"
            data-medicine-id="{{ $record->medicine_id }}"
            data-medicine-name="{{ $record->medicine->name ?? '' }}"
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
                <div class="action-group">

                    <button type="button"
                            class="action-btn view-btn view-record-btn"
                            data-id="{{ $record->id }}"
                            data-date="{{ $record->dispense_date }}"
                            data-patient="{{ $record->patient_name }}"
                            data-medicine="{{ $record->medicine->name ?? '' }}"
                            data-quantity="{{ $record->quantity_dispensed }}"
                            data-dispensedby="{{ $record->dispensed_by }}"
                            data-diagnosis="{{ $record->diagnosis ?? 'N/A' }}"
                            data-status="{{ $record->status ?? 'fully_dispensed' }}"
                            data-unfulfilled="{{ $record->unfulfilled_quantity ?? 0 }}"
                            data-prescription-id="{{ $record->prescription_id ?? '' }}"
                            data-outcome="{{ $record->prescription->fulfillment_outcome ?? '' }}">
                        View
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

                <!-- PRESCRIPTIONS SECTION (replaces manual medicine/diagnosis entry) -->
                <div class="form-section-block">
                    <div class="section-title">Active Prescriptions</div>
                    <div class="divider"></div>
                    <div id="rxNoPatient" style="padding: 14px; color: #9CA3AF; font-size: 13px; text-align: center;">
                        Select a patient above to see their active prescriptions.
                    </div>
                    <div id="rxNoneFound" style="display:none; padding: 14px; background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; border-radius: 6px; font-size: 13px;">
                        This patient has no active (unfulfilled) prescriptions.
                    </div>
                    <div id="rxList" style="display:none; display: flex; flex-direction: column; gap: 8px;"></div>
                </div>

                <!-- SELECTED PRESCRIPTION + DISPENSE QUANTITY (hidden until a prescription is chosen) -->
                <div class="form-section-block" id="rxSelectedBlock" style="display:none;">
                    <div class="section-title">Dispense This Medicine</div>
                    <div class="divider"></div>

                    <input type="hidden" name="prescription_id" id="prescription_id" required>
                    <input type="hidden" name="medicine_id" id="medicine_id" required>
                    <input type="hidden" name="diagnosis" id="diagnosis" required>

                    <div class="grid-2">
                        <div class="field">
                            <label>Medicine</label>
                            <input type="text" id="rxMedicineDisplay" readonly>
                        </div>
                        <div class="field">
                            <label>Dosage / Frequency / Duration</label>
                            <input type="text" id="rxDetailsDisplay" readonly>
                        </div>
                        <div class="field full">
                            <label>Instructions</label>
                            <input type="text" id="rxInstructionsDisplay" readonly>
                        </div>
                        <div class="field">
                            <label>Quantity to Dispense</label>
                            <input type="number" name="quantity" id="quantity" min="1" required>
                        </div>
                        <div class="field">
                            <label>Unit</label>
                            <input type="text" name="unit" id="unit" readonly required>
                        </div>
                    </div>
                    <div id="rxStockWarning" style="display:none; margin-top: 10px; padding: 10px 14px; background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; border-radius: 6px; font-size: 12.5px;"></div>
                </div>

                <!-- CONFIRMATION SECTION -->
                <div class="form-section-block">
                    <div class="section-title">Confirmation</div>
                    <div class="divider"></div>
                    <div class="field" style="max-width: 50%;">
                        <label>Dispensed by (Staff Initials / Name)</label>
                        <input type="text" name="dispensed_by" id="dispensed_by" value="{{ Auth::user()->name ?? session('admin_name') ?? session('user_name') ?? ucfirst($role) }}" readonly required>
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
            <input type="hidden" id="viewRecordId">
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
                    <input type="number" min="1" id="viewQuantity" readonly>
                </div>
                <div class="field full">
                    <label>Diagnosis / Purpose</label>
                    <input type="text" id="viewDiagnosis" readonly>
                </div>
            </div>

            <div id="viewOutcomeSection" style="display:none; margin-top: 16px; padding: 14px; background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 8px;">
                <div style="font-size: 12.5px; font-weight: 600; color: #92400E; margin-bottom: 4px;">
                    <span id="viewUnfulfilledText"></span>
                </div>
                <label style="font-size: 11.5px; font-weight: 700; color: #6B7280; text-transform: uppercase;">What happens to the remaining quantity?</label>
                <select id="viewOutcomeSelect" style="width:100%; margin-top: 6px; padding: 8px 10px; border: 1px solid #D1D5DB; border-radius: 6px;">
                    <option value="awaiting_stock">Awaiting Stock (keep active)</option>
                    <option value="referred_other_pharmacy">Referred to Other Pharmacy</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button type="button" class="btn-primary" style="margin-top: 10px; width: 100%;" onclick="saveOutcome()">Save Outcome</button>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
                <button type="button" class="btn-secondary" id="viewEditBtn" onclick="enableRecordEdit()">Edit</button>
                <button type="button" class="btn-primary" id="viewSaveBtn" style="display:none;" onclick="saveRecordEdit()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<style>.swal2-container { z-index: 100000 !important; }</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // MODAL TOGGLES
    const dispenseModal = document.getElementById('dispenseModal');
    const openDispenseModal = document.getElementById('openDispenseModal');
    const closeDispenseModal = document.getElementById('closeDispenseModal');

    const viewModal = document.getElementById('viewModal');
    const closeViewModal = document.getElementById('closeViewModal');

    openDispenseModal.addEventListener('click', () => dispenseModal.classList.add('show'));
    closeDispenseModal.addEventListener('click', () => dispenseModal.classList.remove('show'));
    closeViewModal.addEventListener('click', () => viewModal.classList.remove('show'));

    // FORM LOGIC
    const form = document.getElementById('dispenseForm');
    const patientSelect = document.getElementById('patient_select');
    const qtyInput = document.getElementById('quantity');
    const recordBtn = document.getElementById('recordBtn');
    const clearBtn = document.getElementById('clearBtn');

    const requiredIds = [
        'patient_ptn', 'family_no', 'barangay', 'date',
        'patient_name', 'age', 'sex', 'address',
        'diagnosis', 'medicine_id', 'prescription_id', 'quantity', 'unit', 'dispensed_by'
    ];

    function clearPatientFields() {
        ['patient_ptn', 'family_no', 'barangay', 'patient_name', 'age', 'sex', 'address', 'philhealth_no'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
    }

    function clearPrescriptionSelection() {
        ['prescription_id', 'medicine_id', 'diagnosis', 'unit', 'quantity'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        document.getElementById('rxSelectedBlock').style.display = 'none';
        document.getElementById('rxStockWarning').style.display = 'none';
    }

    function selectPrescription(rx) {
        document.getElementById('prescription_id').value = rx.id;
        document.getElementById('medicine_id').value = rx.medicine_id;
        document.getElementById('diagnosis').value = rx.diagnosis || 'Not recorded';
        document.getElementById('unit').value = rx.unit || '';

        document.getElementById('rxMedicineDisplay').value = rx.medicine_name;
        document.getElementById('rxDetailsDisplay').value = [rx.dosage, rx.frequency, rx.duration].filter(Boolean).join(' / ') || '-';
        document.getElementById('rxInstructionsDisplay').value = rx.instructions || 'None';

        const suggestedQty = rx.remaining_needed;
        qtyInput.value = suggestedQty > 0 ? suggestedQty : '';
        qtyInput.max = rx.remaining_needed;

        const warning = document.getElementById('rxStockWarning');
        if (rx.usable_stock < rx.remaining_needed) {
            warning.style.display = 'block';
            warning.textContent = rx.usable_stock <= 0
                ? `No usable stock available for ${rx.medicine_name}. This will be recorded as a stock-out.`
                : `Only ${rx.usable_stock} unit(s) available - prescribed amount is ${rx.remaining_needed}. This will be recorded as a partial dispense.`;
        } else {
            warning.style.display = 'none';
        }

        document.getElementById('rxSelectedBlock').style.display = 'block';
        checkReady();
    }

    function renderPrescriptionList(prescriptions) {
        const listEl = document.getElementById('rxList');
        const noneEl = document.getElementById('rxNoneFound');
        const noPatientEl = document.getElementById('rxNoPatient');

        noPatientEl.style.display = 'none';
        clearPrescriptionSelection();

        if (!prescriptions.length) {
            listEl.style.display = 'none';
            noneEl.style.display = 'block';
            return;
        }

        noneEl.style.display = 'none';
        listEl.style.display = 'flex';
        listEl.innerHTML = prescriptions.map((rx, i) => `
            <div class="rx-option" data-index="${i}" style="border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px 14px; cursor: pointer;">
                <div style="font-weight: 600; font-size: 13.5px; color: #111827;">${rx.medicine_name}</div>
                <div style="font-size: 12px; color: #6B7280; margin-top: 3px;">
                    ${[rx.dosage, rx.frequency, rx.duration].filter(Boolean).join(' / ') || '-'}
                    &middot; Needed: ${rx.remaining_needed} ${rx.unit || ''}
                    &middot; Usable stock: ${rx.usable_stock}
                </div>
            </div>
        `).join('');

        listEl.querySelectorAll('.rx-option').forEach((el, i) => {
            el.addEventListener('click', () => {
                listEl.querySelectorAll('.rx-option').forEach(o => o.style.cssText = 'border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px 14px; cursor: pointer;');
                el.style.cssText = 'border: 2px solid #2563EB; background: #EFF6FF; border-radius: 8px; padding: 12px 14px; cursor: pointer;';
                selectPrescription(prescriptions[i]);
            });
        });
    }

    function clearMedicineFields() {
        document.getElementById('rxNoPatient').style.display = 'block';
        document.getElementById('rxNoneFound').style.display = 'none';
        document.getElementById('rxList').style.display = 'none';
        document.getElementById('rxList').innerHTML = '';
        clearPrescriptionSelection();
    }

    patientSelect.addEventListener('change', function() {
        const opt = this.selectedOptions[0];
        if (!opt || !opt.value) {
            clearPatientFields();
            clearMedicineFields();
            checkReady();
            return;
        }

        document.getElementById('patient_ptn').value  = opt.dataset.ptn || '';
        document.getElementById('family_no').value    = opt.dataset.family || '';
        document.getElementById('barangay').value     = opt.dataset.brgy || '';
        document.getElementById('patient_name').value = opt.dataset.name || '';
        document.getElementById('age').value          = opt.dataset.age || '';
        document.getElementById('sex').value          = opt.dataset.sex || '';
        document.getElementById('address').value      = opt.dataset.address || '';
        document.getElementById('philhealth_no').value = opt.dataset.philhealth || '';

        fetch(`/patients/${opt.value}/prescriptions`)
            .then(res => res.json())
            .then(data => renderPrescriptionList(data.prescriptions || []))
            .catch(() => renderPrescriptionList([]));

        checkReady();
    });

    function checkReady() {
        let isReady = true;

        requiredIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el || el.value === null || el.value.toString().trim() === '') {
                isReady = false;
            }
        });

        const val = parseInt(qtyInput.value);
        const max = parseInt(qtyInput.max);
        if (isNaN(val) || val <= 0 || (max && val > max)) {
            isReady = false;
        }

        recordBtn.disabled = !isReady;
    }

    form.addEventListener('input', checkReady);
    form.addEventListener('change', checkReady);
    form.addEventListener('keyup', checkReady);

    clearBtn.addEventListener('click', function(e) {
        e.preventDefault();
        patientSelect.value = '';
        medicineSelect.value = '';
        clearPatientFields();
        clearMedicineFields();
        document.getElementById('diagnosis').value = '';
        checkReady();
    });

    // POPULATE VIEW MODAL
    document.querySelectorAll('.view-record-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('viewRecordId').value = this.dataset.id;
            document.getElementById('viewDate').value = this.dataset.date;
            document.getElementById('viewPatient').value = this.dataset.patient;
            document.getElementById('viewMedicine').value = this.dataset.medicine;
            document.getElementById('viewQuantity').value = this.dataset.quantity;
            document.getElementById('viewQuantity').dataset.original = this.dataset.quantity;
            document.getElementById('viewDispensedBy').value = this.dataset.dispensedby;
            document.getElementById('viewDiagnosis').value = this.dataset.diagnosis;

            document.getElementById('viewQuantity').readOnly = true;
            document.getElementById('viewEditBtn').style.display = 'inline-block';
            document.getElementById('viewSaveBtn').style.display = 'none';

            const outcomeSection = document.getElementById('viewOutcomeSection');
            if (this.dataset.status === 'partially_dispensed' && this.dataset.prescriptionId) {
                outcomeSection.style.display = 'block';
                outcomeSection.dataset.prescriptionId = this.dataset.prescriptionId;
                document.getElementById('viewUnfulfilledText').textContent =
                    `${this.dataset.unfulfilled} unit(s) could not be dispensed due to insufficient stock.`;
                document.getElementById('viewOutcomeSelect').value = this.dataset.outcome || 'awaiting_stock';
            } else {
                outcomeSection.style.display = 'none';
            }

            viewModal.classList.add('show');
        });
    });

// ================= FILTER LOGIC =================

const searchInput = document.getElementById('searchInput');
const dateFilter = document.getElementById('dateFilter');
const patientFilter = document.getElementById('patientFilter');
const medicineFilter = document.getElementById('medicineFilter');
const staffFilter = document.getElementById('staffFilter');
const clearFiltersBtn = document.getElementById('clearFiltersBtn');

const dispensingRows = document.querySelectorAll('.dispensing-row');

function filterRecords() {
    const searchValue = searchInput.value.toLowerCase().trim();
    const dateValue = dateFilter.value;
    const patientValue = patientFilter.value.trim();
    const medicineValue = medicineFilter.value.trim();
    const staffValue = staffFilter.value.toLowerCase().trim();

    dispensingRows.forEach(row => {
        // Get row values safely
        const rowDate = (row.dataset.date || '').trim();
        const patientPtn = (row.dataset.patientPtn || '').trim().toLowerCase();
        const patientName = (row.dataset.patientName || '').trim().toLowerCase();
        const medicineId = (row.dataset.medicineId || '').trim();
        const medicineName = (row.dataset.medicineName || '').trim().toLowerCase();
        const staffName = (row.dataset.staff || '').trim().toLowerCase();

        // SEARCH — matches patient name, PTN, medicine name, or staff
        const matchesSearch = !searchValue ||
            patientName.includes(searchValue) ||
            patientPtn.includes(searchValue) ||
            medicineName.includes(searchValue) ||
            staffName.includes(searchValue);

        // DATE — exact match
        const matchesDate = !dateValue || rowDate === dateValue;

        // PATIENT — match by PTN/ID exactly
        const matchesPatient = !patientValue || patientPtn === patientValue.toLowerCase();

        // MEDICINE — match by ID exactly
        const matchesMedicine = !medicineValue || medicineId === medicineValue;

        // STAFF — match by name (contains)
        const matchesStaff = !staffValue || staffName.includes(staffValue);

        // SHOW row only if ALL conditions match
        const isVisible = matchesSearch && matchesDate && matchesPatient && matchesMedicine && matchesStaff;
        row.style.display = isVisible ? '' : 'none';
    });
}

// Attach events
searchInput.addEventListener('input', filterRecords);
dateFilter.addEventListener('change', filterRecords);
patientFilter.addEventListener('change', filterRecords);
medicineFilter.addEventListener('change', filterRecords);
staffFilter.addEventListener('change', filterRecords);
clearFiltersBtn.addEventListener('click', function() {
    searchInput.value = '';
    dateFilter.value = '';
    patientFilter.value = '';
    medicineFilter.value = '';
    staffFilter.value = '';
    filterRecords();
});

    checkReady();

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Saved!', text: "{{ session('success') }}" });
    @endif

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error!', text: "{{ session('error') }}" });
    @endif

});

// ADMIN ACTION HANDLERS
function enableRecordEdit() {
    document.getElementById('viewQuantity').readOnly = false;
    document.getElementById('viewQuantity').focus();
    document.getElementById('viewEditBtn').style.display = 'none';
    document.getElementById('viewSaveBtn').style.display = 'inline-block';
}

function saveOutcome() {
    const section = document.getElementById('viewOutcomeSection');
    const prescriptionId = section.dataset.prescriptionId;
    const outcome = document.getElementById('viewOutcomeSelect').value;

    const token = document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('input[name="_token"]')?.value;

    fetch(`/prescriptions/${prescriptionId}/outcome`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ outcome: outcome })
    })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
        if (ok && data.success) {
            Swal.fire('Saved', 'Outcome updated successfully.', 'success')
                .then(() => window.location.reload());
        } else {
            Swal.fire('Error', data.message || 'Unable to save outcome.', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Unable to reach the server.', 'error'));
}

function saveRecordEdit() {
    const recordId = document.getElementById('viewRecordId').value;
    const newQty = parseInt(document.getElementById('viewQuantity').value, 10);
    const originalQty = parseInt(document.getElementById('viewQuantity').dataset.original, 10);

    if (!newQty || newQty < 1) {
        Swal.fire('Invalid Quantity', 'Quantity must be at least 1.', 'warning');
        return;
    }
    if (newQty === originalQty) {
        document.getElementById('viewQuantity').readOnly = true;
        document.getElementById('viewEditBtn').style.display = 'inline-block';
        document.getElementById('viewSaveBtn').style.display = 'none';
        return;
    }

    const direction = newQty < originalQty ? 'returned to inventory' : 'deducted from inventory';
    const diff = Math.abs(newQty - originalQty);

    Swal.fire({
        title: 'Confirm Correction?',
        html: `Quantity will change from <b>${originalQty}</b> to <b>${newQty}</b>.<br>${diff} unit(s) will be ${direction}.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, save correction',
        confirmButtonColor: '#1A73E8'
    }).then((result) => {
        if (!result.isConfirmed) return;

        const token = document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_token"]')?.value;

        fetch(`/dispense/${recordId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: newQty })
        })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (ok && data.success) {
                Swal.fire('Updated!', data.message || 'Record updated successfully.', 'success')
                    .then(() => window.location.reload());
            } else {
                Swal.fire('Error', data.message || 'Unable to update record.', 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Unable to reach the server.', 'error'));
    });
}
</script>

</body>
</html>
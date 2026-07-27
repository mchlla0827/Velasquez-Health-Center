<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dispense Medicine | Nurse</title>

<style>
    body { margin: 0; font-family: Arial, sans-serif; background: #F9FAFB; overflow-x: hidden; }
    .container { display: flex; }
    .sidebar { width: 260px; height: 100vh; background: white; border-right: 1px solid #E5E7EB; padding: 24px; position: fixed; top: 0; left: 0; box-sizing: border-box; overflow-y: auto; }
    .sidebar-header { display: flex; align-items: center; gap: 14px; padding-bottom: 14px; border-bottom: 1px solid #E5E7EB; margin-bottom: 10px; }
    .logo { width: 40px; height: 40px; border-radius: 50%; }
    .brand-wrapper { display: flex; flex-direction: column; line-height: 1.2; }
    .brand { font-weight: bold; color: #1E3A8A; font-size: 13.3px; }
    .sub { font-size: 11px; color: #6B7280; }
    .group { margin-top: 22px; font-size: 11px; font-weight: bold; color: #9CA3AF; text-transform: uppercase; }
    .nav-item { display: flex; align-items: center; gap: 14px; padding: 10px; margin-top: 6px; text-decoration: none; color: #5f6570; border-radius: 6px; font-size: 14px; }
    .nav-icon { width: 22px; height: 22px; object-fit: contain; }
    .nav-item.active { background: #EFF6FF; color: #1A73E8; border-left: 4px solid #1A73E8; font-weight: bold; }
    .nav-item:hover { background: #F3F4F6; }
    
    /* MAIN LAYOUT */
    .main { margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom:16px; }
    .welcome-text{ font-size: 14px; color: #374151; margin-bottom: 5px; }
    .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }
    .role { background: #9333EA; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-left: 6px; }
    .right { text-align: right; font-size: 12px; color: #374151; }
    .divider { width: 100%; height: 1px; background: #E5E7EB; margin: 10px 0 20px 0; }
    
    /* PAGE TITLE HEADER & TOP BUTTON */
    .page-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .page-title { font-size: 22px; font-weight: bold; color: #111827; margin: 0; }

    /* FILTERS SECTION */
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
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    /* TABLE SECTION */
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
    }
    th {
        background: #F9FAFB;
        font-weight: 600;
        color: #4B5563;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    tbody tr:hover {
        background-color: #F9FAFB;
    }

    /* FORM COMPONENTS */
    label { font-size: 13px; font-weight: 600; color: #475569; display: block; margin-bottom: 6px; }
    input, select { 
        width: 100%; 
        padding: 10px 14px; 
        border: 1px solid #CBD5E1; 
        border-radius: 8px; 
        font-size: 14px; 
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
        background-color: #F1F5F9; 
        color: #475569; 
        border-color: #E2E8F0;
        cursor: not-allowed; 
    }

    /* BUTTONS */
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
    
    .action-btn {
        background: transparent;
        border: none;
        color: #2563EB;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 6px;
        transition: background 0.15s ease;
    }
    .action-btn:hover { background: #EFF6FF; }

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

    /* MODAL OVERLAY & POPUP */
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
    .section-title { font-weight: 700; color: #1E293B; margin-bottom: 16px; font-size: 15px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .field.full { grid-column: span 2; }
    .buttons { padding-top: 16px; border-top: 1px solid #E2E8F0; display: flex; justify-content: flex-end; gap: 12px; }

    /* MEDIA QUERIES */
    @media (max-width: 992px) {
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

<x-sidebar />

<!-- MAIN CONTENT -->
<div class="main">

    <!-- TOP HEADER -->
    <div class="header">
        <div>
            <div class="welcome-text">Welcome back,</div>
            <div class="welcome-name">
                {{ $userName }}
                @php
                    $roleColor = match($role) {
                        'admin' => '#9333EA',
                        'nurse' => '#10B981',
                        'bhw'   => '#6366F1',
                        'doctor'=> '#EF4444',
                        default => '#6B7280'
                    };
                @endphp
                <span class="role" style="background-color: {{ $roleColor }};">
                    {{ strtoupper($role) }}
                </span>
            </div>
        </div>

        <div class="right">
            <b>Velasquez Health Center</b><br>
            @php date_default_timezone_set('Asia/Manila'); @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>

    <div class="divider"></div>

    <!-- PAGE TITLE & TOP NEW DISPENSE BUTTON -->
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
                        <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Medicine Filter</label>
                <select id="medicineFilter">
                    <option value="">All Medicines</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                    @endforeach
                </select>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="dispensingTableBody">
                @foreach($dispensingHistory as $record)
                <tr>
                    <td>{{ $record->dispense_date }}</td>
                    <td>{{ $record->patient->first_name ?? '' }} {{ $record->patient->last_name ?? '' }}</td>
                    <td>{{ $record->medicine->name ?? '' }}</td>
                    <td>{{ $record->quantity_dispensed }}</td>
                    <td>{{ $record->dispensed_by }}</td>
                    <td>
                        <button type="button" class="action-btn view-record-btn" 
                            data-date="{{ $record->dispense_date }}"
                            data-patient="{{ $record->patient->first_name ?? '' }} {{ $record->patient->last_name ?? '' }}"
                            data-medicine="{{ $record->medicine->name ?? '' }}"
                            data-quantity="{{ $record->quantity_dispensed }}"
                            data-dispensedby="{{ $record->dispensed_by }}"
                            data-diagnosis="{{ $record->diagnosis ?? 'N/A' }}">
                            View
                        </button>
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
            <form method="POST" action="/nurse/dispense/save" id="dispenseForm">
                @csrf
                <!-- SELECT PATIENT -->
                <div class="form-section-block">
                    <div class="section-title">Select Patient</div>
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

                <!-- PATIENT INFO -->
                <div class="form-section-block">
                    <div class="section-title">Patient Information</div>
                    <div class="grid-2">
                        <div>
                            <label>Patient PTN / ID</label>
                            <input type="text" name="patient_ptn" id="patient_ptn" readonly required>
                        </div>
                        <div>
                            <label>Family No.</label>
                            <input type="text" name="family_no" id="family_no" readonly required>
                        </div>
                        <div>
                            <label>Barangay</label>
                            <input type="text" name="barangay" id="barangay" readonly required>
                        </div>
                        <div>
                            <label>Date</label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" readonly required>
                        </div>
                        <div class="field full">
                            <label>Patient Name</label>
                            <input type="text" name="patient_name" id="patient_name" readonly required>
                        </div>
                        <div>
                            <label>Age</label>
                            <input type="number" name="age" id="age" readonly required>
                        </div>
                        <div>
                            <label>Sex</label>
                            <input type="text" name="sex" id="sex" readonly required>
                        </div>
                        <div class="field full">
                            <label>Address</label>
                            <input type="text" name="address" id="address" readonly required>
                        </div>
                        <div>
                            <label>PhilHealth No.</label>
                            <input type="text" name="philhealth_no" id="philhealth_no" readonly>
                        </div>
                    </div>
                </div>

                <!-- MEDICAL INFO -->
                <div class="form-section-block">
                    <div class="section-title">Medical Information</div>
                    <div class="field full">
                        <label>Diagnosis / Purpose</label>
                        <input type="text" name="diagnosis" id="diagnosis" placeholder="Enter diagnosis..." required>
                    </div>
                </div>

                <!-- MEDICINE INFO -->
                <div class="form-section-block">
                    <div class="section-title">Medicine Information</div>
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
                        <div>
                            <label>Quantity to Dispense</label>
                            <input type="number" name="quantity" id="quantity" min="1" placeholder="e.g. 10" required>
                        </div>
                        <div>
                            <label>Unit</label>
                            <input type="text" name="unit" id="unit" readonly required>
                        </div>
                    </div>
                </div>

                <!-- CONFIRMATION -->
                <div class="form-section-block">
                    <div class="section-title">Confirmation</div>
                    <div style="max-width: 50%;">
                        <label>Dispensed by</label>
                        <input type="text" name="dispensed_by" id="dispensed_by" value="{{ Auth::user()->name ?? 'Staff' }}" readonly required>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="buttons">
                    <button type="submit" class="record-btn" id="recordBtn" disabled>Record Dispensing</button>
                    <button type="button" class="clear-btn" id="clearBtn">Clear</button>
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
                <div>
                    <label>Date</label>
                    <input type="text" id="viewDate" readonly>
                </div>
                <div>
                    <label>Dispensed By</label>
                    <input type="text" id="viewDispensedBy" readonly>
                </div>
                <div class="field full">
                    <label>Patient Name</label>
                    <input type="text" id="viewPatient" readonly>
                </div>
                <div>
                    <label>Medicine</label>
                    <input type="text" id="viewMedicine" readonly>
                </div>
                <div>
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

    // FORM ELEMENTS
    const form = document.getElementById('dispenseForm');
    const patientSelect = document.getElementById('patient_select');
    const medicineSelect = document.getElementById('medicine_id');
    const qtyInput = document.getElementById('quantity');
    const recordBtn = document.getElementById('recordBtn');
    const clearBtn = document.getElementById('clearBtn');

    const requiredIds = [
        'patient_ptn', 'family_no', 'barangay', 'date', 
        'patient_name', 'age', 'sex', 'address', 
        'diagnosis', 'medicine_id', 'quantity', 'unit', 'dispensed_by'
    ];

    function clearPatientFields() {
        ['patient_ptn', 'family_no', 'barangay', 'patient_name', 'age', 'sex', 'address', 'philhealth_no'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
    }

    function clearMedicineFields() {
        document.getElementById('unit').value = '';
        qtyInput.value = '';
        qtyInput.removeAttribute('max');
        qtyInput.placeholder = 'e.g. 10';
    }

    patientSelect.addEventListener('change', function() {
        const opt = this.selectedOptions[0];
        if (!opt || !opt.value) {
            clearPatientFields();
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

        checkReady();
    });

    medicineSelect.addEventListener('change', function() {
        const opt = this.selectedOptions[0];
        if (!opt || !opt.value) {
            clearMedicineFields();
            checkReady();
            return;
        }

        document.getElementById('unit').value = opt.dataset.unit || 'TABLET';
        const stock = parseInt(opt.dataset.stock) || 0;
        qtyInput.max = stock;
        qtyInput.placeholder = `Max: ${stock}`;

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

    // VIEW ACTION MODAL POPULATION
    document.querySelectorAll('.view-record-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('viewDate').value = this.dataset.date;
            document.getElementById('viewPatient').value = this.dataset.patient;
            document.getElementById('viewMedicine').value = this.dataset.medicine;
            document.getElementById('viewQuantity').value = this.dataset.quantity;
            document.getElementById('viewDispensedBy').value = this.dataset.dispensedby;
            document.getElementById('viewDiagnosis').value = this.dataset.diagnosis;
            viewModal.classList.add('show');
        });
    });

    checkReady();

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Saved!', text: "{{ session('success') }}" });
    @endif

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error!', text: "{{ session('error') }}" });
    @endif

});
</script>

</body>
</html>
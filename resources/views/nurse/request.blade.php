<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <title>Request Form | Nurse</title>

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
        .main { margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom:16px; }
        .welcome-text{ font-size: 14px; color: #374151; margin-bottom: 5px; }
        .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }
        .role { background: #10B981; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-left: 6px; }
        .right { text-align: right; font-size: 12px; color: #374151; }
        .divider { width: 100%; height: 1px; background: #E5E7EB; margin: 10px 0 20px 0; }
        .page-title { font-size: 22px; font-weight: bold; color: #111827; margin-bottom: 4px; }

        /* ================= PAGE HEADER / CREATE BUTTON ================= */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; width: 100%; }
        .create-btn { background: #2563EB; color: white; padding: 10px 14px; border-radius: 8px; font-size: 13px; border: none; cursor: pointer; }

        /* ================= TABLE CARD ================= */
        .table-card { background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.05em; padding: 14px 24px; border-bottom: 1px solid #E5E7EB; }
        td { padding: 16px 24px; font-size: 13px; color: #111827; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
        tbody tr:hover { background: #F9FAFB; }
        .medicine-name { font-weight: 600; }

        /* status pills */
        .pill { padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .pill-pending { background: #FEF9C3; color: #A16207; }
        .pill-approved { background: #DCFCE7; color: #166534; }
        .pill-rejected { background: #FEE2E2; color: #991B1B; }
        .pill-completed { background: #DBEAFE; color: #1E40AF; }

        .view-btn { background:#F3E8FF; color:#6D28D9; border:none; padding:6px 12px; border-radius:6px; font-size:12px; cursor:pointer; }
        .view-btn:hover { background:#E9D5FF; }

        /* ================= MODAL ================= */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); overflow: auto; }
        .modal-content { background: white; width: 900px; margin: 40px auto; border-radius: 12px; padding: 24px; max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: start; }
        .modal-title { font-size: 20px; font-weight: bold; }
        .modal-sub { font-size: 12px; color: #6B7280; }
        .close-btn { font-size: 18px; cursor: pointer; color: #6B7280; }
        .section { margin-top: 20px; }
        .info-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
        .info-box { border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 13px; }
        .req-table th { background: #F3F4F6; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
        .btn-close { background: white; border: 1px solid #E5E7EB; padding: 10px 16px; border-radius: 8px; cursor: pointer; }

        /* --- GOVERNMENT STANDARD RIS SHEET CONTAINER (inside New Request modal) --- */
        .ris-form { background: #FFFFFF; width: 100%; margin: 0 auto; }
        .annex { text-align: right; font-weight: 600; font-size: 12px; color: #374151; margin: -5px 0 12px; }
        .form-title { text-align: center; margin-bottom: 20px; }
        .form-title h2 { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #111827; margin:0; }
        .form-title p { font-size: 12px; color: #4B5563; margin-top: 3px; }

        .ris-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 15px; margin-bottom: 15px; }
        .info-row-full { grid-column: 1 / -1; display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px; }
        .info-col { display: flex; flex-direction: column; gap: 4px; }
        .info-label { font-size: 11px; font-weight: 600; color: #4B5563; text-transform: uppercase; }
        .info-value-box { background: #F3F4F6; border: 1px solid #D1D5DB; border-radius: 4px; padding: 6px 10px; font-size: 12px; color: #374151; }

        input[type="text"], input[type="number"], input[type="date"], select {
            width: 100%; border: 1px solid #D1D5DB; border-radius: 4px; padding: 6px 10px;
            font-size: 12px; color: #1F2937; background: #FFF; outline: none; box-sizing: border-box;
        }
        input:disabled, select:disabled { background: #F3F4F6; color: #6B7280; cursor: not-allowed; }

        .table-wrap { margin: 12px 0; border: 1px solid #D1D5DB; border-radius: 4px; overflow: hidden; }
        .ris-form table th, .ris-form table td { border: 1px solid #D1D5DB; padding: 6px 4px; text-align: center; vertical-align: middle; font-size: 11px; }
        .ris-form table th { background: #F9FAFB; font-weight: 600; text-transform: uppercase; }
        .ris-form table td input, .ris-form table td select { padding: 4px 6px; font-size: 11px; border-radius: 3px; }
        .text-left { text-align: left !important; }
        .radio-cell { display: flex; justify-content: center; align-items: center; }
        .radio-cell input { width: 14px; height: 14px; }

        .purpose-container { margin: 12px 0 18px; }
        .purpose-container input { margin-top: 4px; }

        .sign-section { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; text-align: center; margin-top: 20px; padding-top: 15px; }
        .sign-col { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 4px; padding: 10px; min-height: 120px; }
        .sign-header { font-size: 10px; font-weight: bold; color: #6B7280; text-transform: uppercase; }
        .sign-name { font-weight: bold; font-size: 12px; color: #111827; border-bottom: 1px solid #9CA3AF; padding-bottom: 3px; margin: 8px 0 3px; min-height: 20px; }
        .sign-title { font-size: 10px; color: #4B5563; text-transform: uppercase; }
        .sign-date { font-size: 10px; color: #6B7280; margin-top: 3px; }
        .text-muted { color: #9CA3AF !important; }

        .btn-group { margin-top: 15px; display: flex; justify-content: flex-end; gap: 8px; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .btn-sm { padding: 4px 8px; font-size: 11px; }
        .btn-primary { background: #1A73E8; color: #FFF; }
        .btn-success { background: #10B981; color: #FFF; }
        .btn-secondary { background: #6B7280; color: #FFF; }
        .btn-danger { background: #EF4444; color: #FFF; }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<div class="container">

    <x-sidebar />

    <div class="main">
        @php
            $user = Auth::user();
            $userName = $user->name ?? 'User';
            $role = strtolower($user->role ?? 'bhw');
            $roleColor = match ($role) {
                'admin'  => '#9333EA',
                'nurse'  => '#10B981',
                'bhw'    => '#6366F1',
                'doctor' => '#EF4444',
                default  => '#6B7280',
            };
        @endphp

        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ $userName }}
                    <span class="role" style="background-color: {{ $roleColor }};">{{ strtoupper($role) }}</span>
                </div>
            </div>
            <div class="right">
                <b>Velasquez Health Center</b><br>
                @php date_default_timezone_set('Asia/Manila'); @endphp
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="divider"></div>

        <div class="page-header">
            <div>
                <div class="page-title">Medicine Requests</div>
            </div>
            <button type="button" class="create-btn" onclick="openNewRequestModal()">
                <i class="bi bi-plus-circle"></i>   &nbsp;New Request
            </button>
        </div>

        {{-- ✅ MY REQUESTS TABLE --}}
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Medicine</th>
                        <th>Qty</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myRequests as $mr)
                    <tr>
                        <td>{{ $mr->created_at->format('M d, Y') }}</td>
                        <td class="medicine-name">{{ $mr->medicine->name ?? 'Deleted Item' }}</td>
                        <td>{{ number_format($mr->quantity_requested) }}</td>
                        <td>{{ $mr->reason ?? '—' }}</td>
                        <td>
                            @php
                                $pillClass = match($mr->status) {
                                    'Approved' => 'pill-approved',
                                    'Rejected' => 'pill-rejected',
                                    'Completed' => 'pill-completed',
                                    default => 'pill-pending',
                                };
                            @endphp
                            <span class="pill {{ $pillClass }}">{{ strtoupper($mr->status ?? 'PENDING PHYSICIAN') }}</span>
                        </td>
                        <td>
                            <button class="view-btn"
                                data-id="{{ $mr->id }}"
                                data-date="{{ $mr->created_at->format('M d, Y') }}"
                                data-ris="{{ $mr->ris_number ?: '—' }}"
                                data-center="{{ $mr->responsibility_center_code ?: '—' }}"
                                data-prepared="{{ $mr->date_prepared ? \Carbon\Carbon::parse($mr->date_prepared)->format('M d, Y') : '—' }}"
                                data-requester="{{ $mr->requester->name ?? $userName }}"
                                data-medicine="{{ $mr->medicine->name ?? 'Deleted Item' }}"
                                data-unit="{{ $mr->unit ?: '—' }}"
                                data-batch="{{ $mr->batch ?: '—' }}"
                                data-expiry="{{ $mr->expiry ? \Carbon\Carbon::parse($mr->expiry)->format('M d, Y') : '—' }}"
                                data-qty="{{ number_format($mr->quantity_requested) }}"
                                data-purpose="{{ $mr->reason ?: '—' }}"
                                data-status="{{ $mr->status ?? 'Pending Physician' }}"
                                data-notes="{{ $mr->physician_notes ?: '—' }}"
                                onclick="viewReq(this)">View</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#6B7280; padding:24px;">No requests submitted yet. Click "+ New Request" to create one.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ✅ NEW REQUEST MODAL — contains the full RIS slip form --}}
<div class="modal" id="newRequestModal">
    <div class="modal-content" style="width: 1050px;">
        <div class="modal-header">
            <div>
                <div class="modal-title">New Medicine Request</div>
                <div class="modal-sub">Fill out the Requisition and Issue Slip below.</div>
            </div>
            <span class="close-btn" onclick="closeNewRequestModal()">&times;</span>
        </div>

        <form id="risMainForm" action="#" method="POST">
        @csrf

        <div class="ris-form">
            <div class="annex">ANNEX 23</div>

            <div class="form-title">
                <h2>Requisition and Issue Slip</h2>
                <p>DEPARTMENT OF HEALTH</p>
            </div>

            <div class="ris-info-grid">
                <div class="info-col">
                    <label class="info-label">Entity Name</label>
                    <div class="info-value-box">MANILA HEALTH DEPARTMENT</div>
                </div>
                <div class="info-col">
                    <label class="info-label">Fund Cluster</label>
                    <div class="info-value-box">DOH</div>
                </div>

                <div class="info-col">
                    <label class="info-label">Office</label>
                    <div class="info-value-box">DISTRICT I PHARMACY STORAGE</div>
                </div>
                <div class="info-col">
                    <label class="info-label">Responsibility Center Code</label>
                    <input type="text" name="responsibility_center_code" placeholder="Enter center code">
                </div>

                <div class="info-row-full">
                    <div class="info-col">
                        <label class="info-label">Division</label>
                        <div class="info-value-box">VELASQUEZ HEALTH CENTER</div>
                    </div>
                    <div class="info-col">
                        <label class="info-label">RIS Control No.</label>
                        <input type="text" name="ris_number" placeholder="Auto Generate">
                    </div>
                    <div class="info-col">
                        <label class="info-label">Date Prepared</label>
                        <input type="date" name="date_prepared" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 70px;">SKU / Code</th>
                            <th rowspan="2" style="width: 160px;">Item Description</th>
                            <th rowspan="2" style="width: 50px;">Unit</th>
                            <th rowspan="2" style="width: 70px;">Batch / Lot No.</th>
                            <th rowspan="2" style="width: 90px;">Expiry Date</th>
                            <th rowspan="2" style="width: 50px;">Qty Req</th>
                            <th colspan="2" style="width: 60px;">Stocks Avail?</th>
                            <th colspan="4">Issuance</th>
                            <th rowspan="2" style="width: 80px;">Remarks</th>
                            <th rowspan="2" style="width: 35px;"><i class="bi bi-trash"></i></th>
                        </tr>
                        <tr>
                            <th>Yes</th>
                            <th>No</th>
                            <th style="width: 55px;">Qty Issued</th>
                            <th style="width: 65px;">Unit Cost</th>
                            <th style="width: 70px;">Total</th>
                            <th style="width: 80px;">Date Issued</th>
                        </tr>
                    </thead>
                    <tbody id="itemBody">
                        <tr class="item-row">
                            <td><input type="text" name="sku[]" placeholder="Code"></td>
                            <td class="text-left">
                                <select name="medicine_id[]" required>
                                    <option value="">-- Select --</option>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="unit[]" placeholder="box"></td>
                            <td><input type="text" name="batch[]"></td>
                            <td><input type="date" name="expiry[]"></td>
                            <td><input type="number" name="qty_requested[]" min="1" required placeholder="0"></td>
                            <td><div class="radio-cell"><input type="radio" name="stock[0]" value="Yes"></div></td>
                            <td><div class="radio-cell"><input type="radio" name="stock[0]" value="No" checked></div></td>
                            <td><input type="number" name="qty_issued[]" placeholder="0" disabled></td>
                            <td><input type="number" name="cost[]" step="0.01" placeholder="0.00" disabled></td>
                            <td><input type="number" name="total[]" step="0.01" placeholder="0.00" disabled></td>
                            <td><input type="date" name="date_issued[]" disabled></td>
                            <td><input type="text" name="remarks[]"></td>
                            <td><button type="button" class="btn btn-danger btn-sm delete-row-btn" disabled><i class="bi bi-x-circle"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-secondary btn-sm" id="addRow">
                <i class="bi bi-plus-circle"></i> Add Item
            </button>

            <div class="purpose-container">
                <label class="info-label">Purpose</label>
                <input type="text" name="purpose" value="FOR HEALTH CENTER USE" required>
            </div>

            <div class="sign-section">
                <div class="sign-col">
                    <div class="sign-header">Requested By</div>
                    <div class="sign-name">{{ $userName }}</div>
                    <div class="sign-title">NURSE</div>
                    <div class="sign-date">Date: {{ date('m/d/Y') }}</div>
                </div>
                <div class="sign-col">
                    <div class="sign-header">Approved By</div>
                    <div class="sign-name text-muted">—</div>
                    <div class="sign-title">PHYSICIAN</div>
                    <div class="sign-date">Pending review</div>
                </div>
                <div class="sign-col">
                    <div class="sign-header">Issued By</div>
                    <div class="sign-name text-muted">—</div>
                    <div class="sign-title">PHARMACIST</div>
                    <div class="sign-date">—</div>
                </div>
                <div class="sign-col">
                    <div class="sign-header">Received By</div>
                    <div class="sign-name text-muted">—</div>
                    <div class="sign-title">RECIPIENT</div>
                    <div class="sign-date">—</div>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-close" onclick="closeNewRequestModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveBtn"><i class="bi bi-file-earmark-check"></i> Save Record</button>
            </div>
        </div>
        </form>
    </div>
</div>

{{-- ✅ VIEW SLIP MODAL — read-only, for checking a past request --}}
<div class="modal" id="viewRequestModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <div class="modal-title">Requisition and Issue Slip</div>
                <div class="modal-sub" id="vModalRequestNo">Request #—</div>
            </div>
            <span class="close-btn" onclick="closeViewModal()">&times;</span>
        </div>

        <div class="section info-grid">
            <div class="info-box"><b>Date Submitted:</b> <span id="vModalDate">—</span></div>
            <div class="info-box"><b>RIS Control No.:</b> <span id="vModalRis">—</span></div>
            <div class="info-box"><b>Responsibility Center Code:</b> <span id="vModalCenter">—</span></div>
            <div class="info-box"><b>Date Prepared:</b> <span id="vModalPrepared">—</span></div>
            <div class="info-box"><b>Requested By:</b> <span id="vModalRequester">—</span></div>
            <div class="info-box"><b>Status:</b> <span id="vModalStatus">—</span></div>
        </div>

        <div class="section">
            <table class="req-table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="padding:8px; text-align:left; border:1px solid #E5E7EB;">Medicine</th>
                        <th style="padding:8px; text-align:left; border:1px solid #E5E7EB;">Unit</th>
                        <th style="padding:8px; text-align:left; border:1px solid #E5E7EB;">Batch / Lot No.</th>
                        <th style="padding:8px; text-align:left; border:1px solid #E5E7EB;">Expiry Date</th>
                        <th style="padding:8px; text-align:left; border:1px solid #E5E7EB;">Qty Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="vModalMedicine">—</td>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="vModalUnit">—</td>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="vModalBatch">—</td>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="vModalExpiry">—</td>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="vModalQty">—</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="info-box"><b>Purpose:</b> <span id="vModalPurpose">—</span></div>
        </div>

        <div class="section">
            <div class="info-box"><b>Physician Notes:</b> <span id="vModalNotes">—</span></div>
        </div>

        <div class="modal-actions">
            <button class="btn-close" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<script>
function openNewRequestModal(){
    document.getElementById('newRequestModal').style.display = 'block';
}
function closeNewRequestModal(){
    document.getElementById('newRequestModal').style.display = 'none';
}

function viewReq(btn){
    const d = btn.dataset;
    document.getElementById('vModalRequestNo').innerText = 'Request #' + d.id;
    document.getElementById('vModalDate').innerText = d.date;
    document.getElementById('vModalRis').innerText = d.ris;
    document.getElementById('vModalCenter').innerText = d.center;
    document.getElementById('vModalPrepared').innerText = d.prepared;
    document.getElementById('vModalRequester').innerText = d.requester;
    document.getElementById('vModalStatus').innerText = (d.status || '').toUpperCase();
    document.getElementById('vModalMedicine').innerText = d.medicine;
    document.getElementById('vModalUnit').innerText = d.unit;
    document.getElementById('vModalBatch').innerText = d.batch;
    document.getElementById('vModalExpiry').innerText = d.expiry;
    document.getElementById('vModalQty').innerText = d.qty;
    document.getElementById('vModalPurpose').innerText = d.purpose;
    document.getElementById('vModalNotes').innerText = d.notes;
    document.getElementById('viewRequestModal').style.display = 'block';
}
function closeViewModal(){
    document.getElementById('viewRequestModal').style.display = 'none';
}

$(function(){
    let idx = 1;

    $('#addRow').click(function(){
        let row = `
        <tr class="item-row">
            <td><input type="text" name="sku[]" placeholder="Code"></td>
            <td class="text-left">
                <select name="medicine_id[]" required>
                    <option value="">-- Select --</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="unit[]" placeholder="box"></td>
            <td><input type="text" name="batch[]"></td>
            <td><input type="date" name="expiry[]"></td>
            <td><input type="number" name="qty_requested[]" min="1" required placeholder="0"></td>
            <td><div class="radio-cell"><input type="radio" name="stock[${idx}]" value="Yes"></div></td>
            <td><div class="radio-cell"><input type="radio" name="stock[${idx}]" value="No" checked></div></td>
            <td><input type="number" name="qty_issued[]" placeholder="0" disabled></td>
            <td><input type="number" name="cost[]" step="0.01" placeholder="0.00" disabled></td>
            <td><input type="number" name="total[]" step="0.01" placeholder="0.00" disabled></td>
            <td><input type="date" name="date_issued[]" disabled></td>
            <td><input type="text" name="remarks[]"></td>
            <td><button type="button" class="btn btn-danger btn-sm delete-row-btn"><i class="bi bi-x-circle"></i></button></td>
        </tr>`;
        $('#itemBody').append(row);
        idx++;
        evalDeleteButtons();
    });

    $('#itemBody').on('click', '.delete-row-btn', function(){
        $(this).closest('tr').remove();
        evalDeleteButtons();
    });

    function evalDeleteButtons() {
        const rows = $('.item-row').length;
        $('.delete-row-btn').prop('disabled', rows <= 1);
    }

    $('#risMainForm').submit(function(e){
        e.preventDefault();
        let data = $(this).serialize();
        let $btn = $('#saveBtn');

        $btn.prop('disabled', true).text('Saving...');

        $.post("{{ route('nurse.request.store') }}", data)
        .done(function(res){
            Swal.fire({
                icon: 'success',
                title: 'Request Submitted',
                text: 'Your request was saved and sent to the physician for approval.',
                confirmButtonColor: '#1A73E8'
            }).then(function(){
                window.location.reload();
            });
        })
        .fail(function(xhr){
            $btn.prop('disabled', false).text('Save Record');
            const msg = xhr.responseJSON?.message
                || (xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors).flat().join('\n') : null)
                || 'Something went wrong while saving your request.';
            Swal.fire({
                icon: 'error',
                title: 'Failed to Submit',
                text: msg,
                confirmButtonColor: '#EF4444'
            });
        });
    });
});
</script>

</body>
</html>
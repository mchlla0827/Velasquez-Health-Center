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
        .page-title { font-size: 22px; font-weight: bold; color: #111827; margin-bottom: 16px; }

/* --- GOVERNMENT STANDARD RIS SHEET CONTAINER --- */
.ris-form {
    background: #FFFFFF;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    width: 1000px; 
    margin: 0 auto;
    padding: 25px; 
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

/* ✅ Status Box — EKSAKTO SA DESIGN */
.status-box {
    background: #FEF3C7;
    color: #92400E;
    border:1px solid #F59E0B;
    padding: 6px 14px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 12px;
    margin-bottom: 15px;
    display: inline-block;
}
.status-box.approved { background: #D1FAE5; color: #065F46; border:1px solid #10B981; }

.annex {
    text-align: right;
    font-weight: 600;
    font-size: 12px;
    color: #374151;
    margin: -5px 0 12px;
}
.form-title {
    text-align: center;
    margin-bottom: 20px;
}
.form-title h2 {
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    color: #111827;
    margin:0;
}
.form-title p {
    font-size: 12px;
    color: #4B5563;
    margin-top: 3px;
}

/* ✅ INFO GRID — SAKTO LANG, WALANG SOBRANG SPACE */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 15px;
    margin-bottom: 15px;
}
.info-row-full {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 15px;
}
.info-col {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.info-label {
    font-size: 11px;
    font-weight: 600;
    color: #4B5563;
    text-transform: uppercase;
}
.info-value-box {
    background: #F3F4F6;
    border: 1px solid #D1D5DB;
    border-radius: 4px;
    padding: 6px 10px;
    font-size: 12px;
    color: #374151;
}

/* --- FORM FIELDS & CONTROLS --- */
input[type="text"], input[type="number"], input[type="date"], select {
    width: 100%;
    border: 1px solid #D1D5DB;
    border-radius: 4px;
    padding: 6px 10px;
    font-size: 12px;
    color: #1F2937;
    background: #FFF;
    outline: none;
    box-sizing: border-box;
}
input:disabled, select:disabled {
    background: #F3F4F6;
    color: #6B7280;
    cursor: not-allowed;
}

/* --- INVENTORY SHEET INTERIOR TABLE --- */
.table-wrap {
    margin: 12px 0;
    border: 1px solid #D1D5DB;
    border-radius: 4px;
    overflow: hidden;
}
table {
    width: 100%;
    border-collapse: collapse;
}
table th, table td {
    border: 1px solid #D1D5DB;
    padding: 6px 4px;
    text-align: center;
    vertical-align: middle;
    font-size: 11px;
}
table th {
    background: #F9FAFB;
    font-weight: 600;
    text-transform: uppercase;
}
table td input, table td select {
    padding: 4px 6px;
    font-size: 11px;
    border-radius: 3px;
}
.text-left {
    text-align: left !important;
}
.radio-cell {
    display: flex;
    justify-content: center;
    align-items: center;
}
.radio-cell input {
    width: 14px;
    height: 14px;
}

/* --- PURPOSE SECTION --- */
.purpose-container {
    margin: 12px 0 18px;
}
.purpose-container input {
    margin-top: 4px;
}

/* --- MULTI-SIGNATORY SECTION --- */
.sign-section {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    text-align: center;
    margin-top: 20px;
    padding-top: 15px;
}
.sign-col {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 4px;
    padding: 10px;
    min-height: 120px;
}
.sign-header {
    font-size: 10px;
    font-weight: bold;
    color: #6B7280;
    text-transform: uppercase;
}
.sign-name {
    font-weight: bold;
    font-size: 12px;
    color: #111827;
    border-bottom: 1px solid #9CA3AF;
    padding-bottom: 3px;
    margin: 8px 0 3px;
    min-height: 20px;
}
.sign-title {
    font-size: 10px;
    color: #4B5563;
    text-transform: uppercase;
}
.sign-date {
    font-size: 10px;
    color: #6B7280;
    margin-top: 3px;
}
.text-muted { color: #9CA3AF !important; }

/* --- ACTIONS & UTILITY CONTROLS --- */
.btn-group {
    margin-top: 15px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}
.btn {
    padding: 7px 14px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}
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
        {{-- ✅ HEADER — NOT TOUCHED --}}
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
        <div class="page-title">Requisition and Issue Slip</div>

        <!-- ✅ STATUS — EXACT SAME AS SCREENSHOT -->
        <div class="status-box pending" id="formStatus">
            <i class="bi bi-hourglass-split"></i> STATUS: PENDING APPROVAL
        </div>

        <form id="risMainForm" action="#" method="POST">
        @csrf
        
        <div class="ris-form">
            <div class="annex">ANNEX 23</div>

            <div class="form-title">
                <h2>Requisition and Issue Slip</h2>
                <p>DEPARTMENT OF HEALTH</p>
            </div>

            <!-- ✅ METADATA — EXACT 3-ROW LAYOUT, WALANG SOBRANG SPACE -->
            <div class="info-grid">
                <!-- ROW 1 -->
                <div class="info-col">
                    <label class="info-label">Entity Name</label>
                    <div class="info-value-box">MANILA HEALTH DEPARTMENT</div>
                </div>
                <div class="info-col">
                    <label class="info-label">Fund Cluster</label>
                    <div class="info-value-box">DOH</div>
                </div>

                <!-- ROW 2 -->
                <div class="info-col">
                    <label class="info-label">Office</label>
                    <div class="info-value-box">DISTRICT I PHARMACY STORAGE</div>
                </div>
                <div class="info-col">
                    <label class="info-label">Responsibility Center Code</label>
                    <input type="text" name="responsibility_center_code" placeholder="Enter center code">
                </div>

                <!-- ROW 3 — FULL WIDTH 3 COLUMNS -->
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

            <!-- ✅ MAIN TABLE — KOMPAKTO, PORTARIAT STYLE -->
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

            <!-- ✅ PURPOSE -->
            <div class="purpose-container">
                <label class="info-label">Purpose</label>
                <input type="text" name="purpose" value="FOR HEALTH CENTER USE" required>
            </div>

            <!-- ✅ SIGNATURES — KOMPAKTO -->
            <div class="sign-section">
                <div class="sign-col">
                    <div class="sign-header">Requested By</div>
                    <div class="sign-name">{{ $userName }}</div>
                    <div class="sign-title">NURSE</div>
                    <div class="sign-date">Date: {{ date('m/d/Y') }}</div>
                    <input type="hidden" name="requested_by" value="{{ session('admin_id') ?? Auth::id() }}">
                </div>

                <div class="sign-col">
                    <div class="sign-header">Approved By</div>
                    <div class="sign-name text-muted" id="approvedNameTarget">—</div>
                    <div class="sign-title">PHYSICIAN</div>
                    <div class="sign-date" id="approvedDateTarget">Date: —</div>
                    <input type="hidden" name="approved_name" id="approvedNameInput">
                    <input type="hidden" name="approved_date" id="approvedDateInput">
                </div>

                <div class="sign-col">
                    <div class="sign-header">Issued By</div>
                    <div class="sign-name text-muted" id="issuedNameTarget">—</div>
                    <div class="sign-title">PHARMACIST</div>
                    <div class="sign-date" id="issuedDateTarget">Date: —</div>
                    <input type="hidden" name="issued_name" id="issuedNameInput">
                    <input type="hidden" name="issued_date" id="issuedDateInput">
                </div>

                <div class="sign-col">
                    <div class="sign-header">Received By</div>
                    <div class="sign-name text-muted" id="receivedNameTarget">—</div>
                    <div class="sign-title">RECIPIENT</div>
                    <div class="sign-date" id="receivedDateTarget">Date: —</div>
                    <input type="hidden" name="received_name" id="receivedNameInput">
                    <input type="hidden" name="received_date" id="receivedDateInput">
                </div>
            </div>

            <!-- ✅ BUTTONS -->
            <div class="btn-group">
                <input type="hidden" name="status" id="formStatusInput" value="Pending">
                <button type="submit" class="btn btn-primary" id="saveBtn"><i class="bi bi-file-earmark-check"></i> Save Record</button>
                
                @if(in_array($role, ['admin','doctor']))
                <button type="button" class="btn btn-success" id="approveBtn" style="display:none;"><i class="bi bi-patch-check"></i> Mark Approved</button>
                @endif
            </div>
        </div>
    </form>

    {{-- ✅ MY RECENT REQUESTS — so the nurse can see approval status without asking the doctor --}}
    <div class="ris-form" style="margin-top: 24px;">
        <div class="form-title" style="text-align:left; margin-bottom:14px;">
            <h2 style="font-size:15px; text-transform:none;">My Recent Requests</h2>
        </div>
        <div class="table-wrap">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="padding:8px; text-align:left; border-bottom:1px solid #E5E7EB;">Date</th>
                        <th style="padding:8px; text-align:left; border-bottom:1px solid #E5E7EB;">Medicine</th>
                        <th style="padding:8px; text-align:left; border-bottom:1px solid #E5E7EB;">Qty</th>
                        <th style="padding:8px; text-align:left; border-bottom:1px solid #E5E7EB;">Status</th>
                        <th style="padding:8px; text-align:left; border-bottom:1px solid #E5E7EB;">Physician Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myRequests as $mr)
                    <tr>
                        <td style="padding:8px; border-bottom:1px solid #F1F5F9;">{{ $mr->created_at->format('M d, Y') }}</td>
                        <td style="padding:8px; border-bottom:1px solid #F1F5F9;">{{ $mr->medicine->name ?? 'Deleted Item' }}</td>
                        <td style="padding:8px; border-bottom:1px solid #F1F5F9;">{{ number_format($mr->quantity_requested) }}</td>
                        <td style="padding:8px; border-bottom:1px solid #F1F5F9;">
                            @php
                                $badgeColor = match($mr->status) {
                                    'Approved' => 'background:#DCFCE7; color:#166534;',
                                    'Rejected' => 'background:#FEE2E2; color:#991B1B;',
                                    'Completed' => 'background:#DBEAFE; color:#1E40AF;',
                                    default => 'background:#FEF9C3; color:#A16207;',
                                };
                            @endphp
                            <span style="{{ $badgeColor }} padding:3px 10px; border-radius:20px; font-size:11px; font-weight:bold;">
                                {{ strtoupper($mr->status ?? 'PENDING PHYSICIAN') }}
                            </span>
                        </td>
                        <td style="padding:8px; border-bottom:1px solid #F1F5F9; color:#6B7280;">{{ $mr->physician_notes ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:16px; text-align:center; color:#6B7280;">No requests submitted yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    </div>
</div>
<script>
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
                // Reload so the form resets and "My Recent Requests" shows the new entry
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

    $('#approveBtn').click(function(){
        let currentName = "{{ $userName }}";
        let currentDate = "{{ date('Y-m-d') }}";
        let formattedDate = "{{ date('m/d/Y') }}";

        $('#approvedNameTarget').text(currentName).removeClass('text-muted');
        $('#approvedDateTarget').text('Date: ' + formattedDate);
        $('#approvedNameInput').val(currentName);
        $('#approvedDateInput').val(currentDate);
        $('#formStatusInput').val('Approved');
        
        $('#formStatus').removeClass('pending').addClass('approved').html('<i class="bi bi-patch-check-fill"></i> STATUS: APPROVED - FOR LHD');
        alert('✅ Approved! Issuance fields unlocked.');
        
        $('input[name^="qty_issued"], input[name^="date_issued"], input[name^="cost"], input[name^="total"]').prop('disabled', false);
    });
});
</script>

</body>
</html>
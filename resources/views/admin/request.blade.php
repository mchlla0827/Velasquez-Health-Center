<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Request Form - Velasquez Health Center</title>

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

    .nav-btn {
        width: 100%;
        text-align: left;
        border: none;
        background: transparent;
        cursor: pointer;
        font-family: inherit;
    }

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

    .role {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        color: white;
        margin-left: 8px;
    }

    .right {
        text-align: right;
        font-size: 12px;
        color: #374151;
    }

    .header-divider {
        width: 100%;
        height: 1px;
        background: #E5E7EB;
        margin: 16px 0 24px;
    }

    /* ==================================================
       ✅ REFINED MAIN CONTENT — CLEAN & MINIMAL
       ================================================== */

    .module-container { display: flex; flex-direction: column; gap: 20px; }

    /* PAGE HEADER */
    .page-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    /* KPI STATUS CARDS */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .kpi-card {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        padding: 16px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .kpi-card:hover { transform: translateY(-2px); }
    .kpi-card.active { outline: 2px solid #2563EB; outline-offset: 2px; }

    .kpi-card h4 {
        margin: 0 0 6px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .kpi-card .val { font-size: 26px; font-weight: 700; line-height: 1; }

    .card-pending { border-left: 3px solid #9CA3AF; }
    .card-pending h4 { color: #6B7280; }
    .card-pending .val { color: #374151; }

    .card-approved { border-left: 3px solid #10B981; }
    .card-approved h4 { color: #059669; }
    .card-approved .val { color: #065F46; }

    .card-rejected { border-left: 3px solid #EF4444; }
    .card-rejected h4 { color: #DC2626; }
    .card-rejected .val { color: #991B1B; }

    .card-completed { border-left: 3px solid #2563EB; }
    .card-completed h4 { color: #2563EB; }
    .card-completed .val { color: #1E40AF; }

    /* TABLE CARD */
    .table-card {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        overflow: hidden;
    }

    .table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px;
        background: #F9FAFB;
        border-bottom: 1px solid #E5E7EB;
    }

    .table-toolbar .left { display: flex; align-items: center; gap: 10px; }
    .table-title { font-size: 14px; font-weight: 600; color: #1F2937; }

    .btn-filter-all {
        background: #EFF6FF;
        color: #2563EB;
        border: none;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        cursor: pointer;
    }

    .search-input {
        width: 280px;
        padding: 8px 12px;
        font-size: 13px;
        border: 1px solid #D1D5DB;
        border-radius: 6px;
        outline: none;
    }

    .search-input:focus { border-color: #2563EB; }

    table { width: 100%; border-collapse: collapse; font-size: 13px; }

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

    td { padding: 14px 16px; border-bottom: 1px solid #F3F4F6; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: #FAFAFA; }

    .pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .pill-pending { background: #FEF9C3; color: #A16207; }
    .pill-approved { background: #DCFCE7; color: #166534; }
    .pill-rejected { background: #FEE2E2; color: #991B1B; }
    .pill-completed { background: #DBEAFE; color: #1E40AF; }

    .btn-sm {
        padding: 5px 10px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
    }

    .btn-view { background: #F3E8FF; color: #6D28D9; margin-right: 4px; }
    .btn-view:hover { background: #E9D5FF; }

    .btn-delete { background: #F3F4F6; color: #374151; }
    .btn-delete:hover { background: #E5E7EB; }

    .text-muted { font-size: 12px; color: #6B7280; }
    .text-center { text-align: center; padding: 30px; color: #6B7280; }

    /* MODAL */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
    }

    .modal-content {
        background: white;
        width: 900px;
        max-width: 95%;
        margin: 40px auto;
        border-radius: 10px;
        padding: 24px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .modal-header h3 { margin: 0; font-size: 17px; font-weight: 700; }
    .modal-sub { font-size: 12px; color: #6B7280; margin-top: 4px; }
    .close-btn { font-size: 20px; cursor: pointer; color: #6B7280; border: none; background: none; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .info-box {
        padding: 10px 12px;
        background: #F9FAFB;
        border-radius: 6px;
        font-size: 13px;
    }

    .info-box b { color: #374151; }

    .modal-table {
        width: 100%;
        border-collapse: collapse;
        margin: 16px 0;
    }

    .modal-table th, .modal-table td {
        border: 1px solid #E5E7EB;
        padding: 10px;
        font-size: 13px;
    }

    .modal-table th { background: #F9FAFB; font-weight: 600; }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-approve {
        background: #10B981;
        color: white;
        border: none;
        padding: 9px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-reject {
        background: #EF4444;
        color: white;
        border: none;
        padding: 9px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-close {
        background: white;
        color: #374151;
        border: 1px solid #D1D5DB;
        padding: 9px 16px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
    }

    @media (max-width: 992px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .info-grid { grid-template-columns: repeat(2, 1fr); }
        .table-toolbar { flex-direction: column; gap: 10px; align-items: flex-start; }
        .search-input { width: 100%; }
    }
</style>
</head>
<body>

<div class="container">

    <x-sidebar />

    <div class="main">

        <!-- ✅ TOP HEADER — FULLY RETAINED, NO CHANGES -->
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ $userName ?? Auth::user()->name ?? 'Administrator' }}
                    @php
                        $user = Auth::user();
                        $role = strtolower($user->role);
                        $displayRole = ($role === 'doctor' && $user->is_physician_in_charge == 1)
                            ? 'PIC'
                            : strtoupper($role);
                        $roleColor = match($role) {
                            'admin'  => '#9333EA',
                            'nurse'  => '#10B981',
                            'doctor' => '#3B82F6',
                            'bhw'    => '#6366F1',
                            default  => '#6B7280'
                        };
                    @endphp
                    <span class="role" style="background-color: {{ $roleColor }};">
                        {{ $displayRole }}
                    </span>
                </div>
            </div>
            <div class="right">
                <b>Velasquez Health Center</b><br>
                @php date_default_timezone_set('Asia/Manila'); @endphp
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="header-divider"></div>

        <!-- ✅ REFINED MAIN CONTENT STARTS HERE -->
        <div class="module-container">

            <!-- PAGE TOP -->
            <div class="page-top">
                <div class="page-title">
                    <h2>Medicine Request Management</h2>
                    <p>View, create, and approve all medicine supply requests from staff</p>
                </div>
                <button class="btn-primary" onclick="openRequestModal()">+ New Request</button>
            </div>

            <!-- KPI STATUS CARDS -->
            <div class="kpi-grid">
                <div class="kpi-card card-pending" id="kpi-Pending Physician"
                     onclick="filterRequests('Pending Physician')">
                    <h4>Pending</h4>
                    <div class="val">{{ $requests->where('status','Pending Physician')->count() }}</div>
                </div>
                <div class="kpi-card card-approved" id="kpi-Approved"
                     onclick="filterRequests('Approved')">
                    <h4>Approved</h4>
                    <div class="val">{{ $requests->where('status','Approved')->count() }}</div>
                </div>
                <div class="kpi-card card-rejected" id="kpi-Rejected"
                     onclick="filterRequests('Rejected')">
                    <h4>Rejected</h4>
                    <div class="val">{{ $requests->where('status','Rejected')->count() }}</div>
                </div>
                <div class="kpi-card card-completed" id="kpi-Completed"
                     onclick="filterRequests('Completed')">
                    <h4>Completed</h4>
                    <div class="val">{{ $requests->where('status','Completed')->count() }}</div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-card">
                <div class="table-toolbar">
                    <div class="left">
                        <span class="table-title" id="tableTitle">All Request Records</span>
                        <button class="btn-filter-all" onclick="filterRequests('all')">Show All</button>
                    </div>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search requests...">
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Requested By</th>
                            <th>Medicine</th>
                            <th>Quantity</th>
                            <th>Purpose / Notes</th>
                            <th>Status</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requestTableBody">
                        @forelse($requests as $req)
                        <tr class="request-row"
                            data-row-status="{{ $req->status ?? 'Pending Physician' }}"
                            data-search="{{ strtolower(($req->requester->name ?? 'unknown').' '.($req->medicine->name ?? 'deleted item').' '.($req->reason ?? '').' '.($req->ris_number ?? '')) }}">
                            <td>{{ $req->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="font-weight:500;">{{ $req->requester->name ?? 'Unknown' }}</div>
                                <span class="text-muted">({{ strtoupper($req->requester->role ?? 'N/A') }})</span>
                            </td>
                            <td>{{ $req->medicine->name ?? 'Deleted Item' }}</td>
                            <td>{{ number_format($req->quantity_requested) }} units</td>
                            <td>{{ $req->reason ?? '—' }}</td>
                            <td>
                                @php
                                    $pillClass = match($req->status ?? 'Pending Physician') {
                                        'Approved' => 'pill-approved',
                                        'Rejected' => 'pill-rejected',
                                        'Completed' => 'pill-completed',
                                        default => 'pill-pending'
                                    };
                                @endphp
                                <span class="pill {{ $pillClass }}">
                                    {{ strtoupper($req->status ?? 'PENDING PHYSICIAN') }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <button class="btn-sm btn-view"
                                    data-id="{{ $req->id }}"
                                    data-date="{{ $req->created_at->format('M d, Y') }}"
                                    data-ris="{{ $req->ris_number ?: '—' }}"
                                    data-center="{{ $req->responsibility_center_code ?: '—' }}"
                                    data-prepared="{{ $req->date_prepared ? \Carbon\Carbon::parse($req->date_prepared)->format('M d, Y') : '—' }}"
                                    data-requester="{{ $req->requester->name ?? 'Unknown' }}"
                                    data-role="{{ strtoupper($req->requester->role ?? 'N/A') }}"
                                    data-medicine="{{ $req->medicine->name ?? 'Deleted Item' }}"
                                    data-unit="{{ $req->unit ?: '—' }}"
                                    data-batch="{{ $req->batch ?: '—' }}"
                                    data-expiry="{{ $req->expiry ? \Carbon\Carbon::parse($req->expiry)->format('M d, Y') : '—' }}"
                                    data-qty="{{ number_format($req->quantity_requested) }}"
                                    data-purpose="{{ $req->reason ?: '—' }}"
                                    data-status="{{ $req->status ?? 'Pending Physician' }}"
                                    data-notes="{{ $req->physician_notes ?: '—' }}"
                                    onclick="viewReq(this)">View</button>
                                <button class="btn-sm btn-delete" onclick="deleteReq({{ $req->id }})">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No request records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- RIS VIEW MODAL -->
<div class="modal" id="requestModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h3>Requisition and Issue Slip</h3>
                <div class="modal-sub" id="modalRequestNo">Request #—</div>
            </div>
            <button class="close-btn" onclick="closeRequestModal()">&times;</button>
        </div>

        <div class="info-grid">
            <div class="info-box"><b>Date Submitted:</b> <span id="modalDate">—</span></div>
            <div class="info-box"><b>RIS Control No.:</b> <span id="modalRis">—</span></div>
            <div class="info-box"><b>Responsibility Center:</b> <span id="modalCenter">—</span></div>
            <div class="info-box"><b>Date Prepared:</b> <span id="modalPrepared">—</span></div>
            <div class="info-box"><b>Requested By:</b> <span id="modalRequester">—</span></div>
            <div class="info-box"><b>Status:</b> <span id="modalStatus">—</span></div>
        </div>

        <table class="modal-table">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Unit</th>
                    <th>Batch / Lot No.</th>
                    <th>Expiry Date</th>
                    <th>Qty Requested</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="modalMedicine">—</td>
                    <td id="modalUnit">—</td>
                    <td id="modalBatch">—</td>
                    <td id="modalExpiry">—</td>
                    <td id="modalQty">—</td>
                </tr>
            </tbody>
        </table>

        <div class="info-box"><b>Purpose:</b> <span id="modalPurpose">—</span></div>
        <div class="info-box" style="margin-top:12px;"><b>Physician Notes:</b> <span id="modalNotes">—</span></div>

        <div class="modal-actions" id="modalActions">
            <button class="btn-close" onclick="closeRequestModal()">Close</button>
            <button class="btn-reject" id="modalRejectBtn" style="display:none;">Reject</button>
            <button class="btn-approve" id="modalApproveBtn" style="display:none;">Approve</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Success / Error Messages
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Success!', text: "{{ session('success') }}", confirmButtonColor: '#2563EB' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error!', text: "{{ session('error') }}", confirmButtonColor: '#EF4444' });
    @endif

    // Open New Request Modal
    function openRequestModal() {
        Swal.fire({ title: 'New Medicine Request', html: '<p>Form will load here...</p>', icon: 'info' });
    }

    // View RIS Slip
    function viewReq(btn) {
        const d = btn.dataset;
        document.getElementById("modalRequestNo").innerText = "Request #" + d.id;
        document.getElementById("modalDate").innerText = d.date;
        document.getElementById("modalRis").innerText = d.ris;
        document.getElementById("modalCenter").innerText = d.center;
        document.getElementById("modalPrepared").innerText = d.prepared;
        document.getElementById("modalRequester").innerText = d.requester + " (" + d.role + ")";
        document.getElementById("modalStatus").innerText = (d.status || "").toUpperCase();
        document.getElementById("modalMedicine").innerText = d.medicine;
        document.getElementById("modalUnit").innerText = d.unit;
        document.getElementById("modalBatch").innerText = d.batch;
        document.getElementById("modalExpiry").innerText = d.expiry;
        document.getElementById("modalQty").innerText = d.qty;
        document.getElementById("modalPurpose").innerText = d.purpose;
        document.getElementById("modalNotes").innerText = d.notes;

        const approveBtn = document.getElementById("modalApproveBtn");
        const rejectBtn = document.getElementById("modalRejectBtn");

        if (d.status === "Pending Physician") {
            approveBtn.style.display = "inline-block";
            rejectBtn.style.display = "inline-block";
            approveBtn.onclick = () => updateStatus(d.id, "Approved");
            rejectBtn.onclick = () => updateStatus(d.id, "Rejected");
        } else {
            approveBtn.style.display = "none";
            rejectBtn.style.display = "none";
        }

        document.getElementById("requestModal").style.display = "block";
    }

    function closeRequestModal() {
        document.getElementById("requestModal").style.display = "none";
    }

    // Update Status (Approve / Reject)
    function updateStatus(id, status) {
        const actionLabel = status === 'Approved' ? 'Approve' : 'Reject';
        Swal.fire({
            title: `${actionLabel} this request?`,
            html: `
                <p style="margin:0 0 8px; color:#6B7280; font-size:13px; text-align:left;">Physician notes (optional)</p>
                <textarea id="physicianNotesInput" class="swal2-textarea" placeholder="Add notes or approval comments..." style="margin-top:0;"></textarea>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'Approved' ? '#10B981' : '#EF4444',
            confirmButtonText: `Yes, ${status}!`,
            preConfirm: () => document.getElementById('physicianNotesInput').value
        }).then((res) => {
            if (res.isConfirmed) {
                const notes = encodeURIComponent(res.value || '');
                window.location.href = `/admin/request/${id}/status/${status}?physician_notes=${notes}`;
            }
        });
    }

    // Delete Request
    function deleteReq(id) {
        Swal.fire({
            title: 'Delete Record?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            confirmButtonText: 'Yes, delete it!'
        }).then((res) => {
            if (res.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/request/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Table Filtering
    const statusLabels = {
        'Pending Physician': 'Pending Requests',
        'Approved': 'Approved Requests',
        'Rejected': 'Rejected Requests',
        'Completed': 'Completed Requests',
        'all': 'All Request Records'
    };

    let currentStatusFilter = 'all';

    function applyFilters() {
        const searchTerm = (document.getElementById('searchInput').value || '').toLowerCase().trim();
        document.querySelectorAll('.request-row').forEach(row => {
            const matchesStatus = currentStatusFilter === 'all' || row.dataset.rowStatus === currentStatusFilter;
            const matchesSearch = searchTerm === '' || row.dataset.search.includes(searchTerm);
            row.style.display = (matchesStatus && matchesSearch) ? '' : 'none';
        });
    }

    function filterRequests(status) {
        currentStatusFilter = status;
        document.getElementById('tableTitle').innerText = statusLabels[status] || 'All Request Records';

        // Highlight active card
        document.querySelectorAll('.kpi-card').forEach(card => card.classList.remove('active'));
        const activeCard = document.getElementById('kpi-' + status);
        if (activeCard) activeCard.classList.add('active');

        applyFilters();
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);
</script>

</body>
</html>
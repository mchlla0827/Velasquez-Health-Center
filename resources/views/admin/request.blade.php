<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Request Form</title>

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

    .logo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

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

/* New modifier class for form buttons inside the sidebar */
.nav-btn {
    width: 100%;
    text-align: left;
    border: none;
    background: transparent; /* Changed from 'none' so hover states can override it */
    cursor: pointer;
    font-family: inherit; /* Ensures the font matches your links */
}

    /* ================= MAIN ================= */
    .main {
        margin-left: 250px;
        width: calc(100% - 250px);
        padding: 24px;
        box-sizing: border-box;
    }

    /* ================= HEADER ================= */
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
        background: #9333EA;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
        text-transform: uppercase;
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
        margin: 16px 0;
    }

    /* ================= PAGE TOP ================= */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: -0.02em;
        margin: 0;
    }

    .page-subtitle {
        font-size: 14px;
        font-weight: 400;
        color: #1e293b;
        margin-top: 4px;
        font-style: italic;
    }

    .create-btn {
        background: #2563EB;
        color: white;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 13px;
        border: none;
        cursor: pointer;
    }

    /* ================= TABLE CARD ================= */
    .table-card {
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 16px;
    }

    .table-title {
        font-weight: bold;
        margin-bottom: 4px;
    }

    .table-sub {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 12px;
    }

    /* ================= TABLE FIX (IMPORTANT PART) ================= */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    /* HEADER */
    th {
        text-align: left;
        font-size: 11px;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 24px;   /* 👈 spacing fix */
        border-bottom: 1px solid #E5E7EB;
    }

    /* ROWS */
    td {
        padding: 16px 24px;   /* 👈 THIS fixes spacing between columns */
        font-size: 13px;
        color: #111827;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
    }

    /* medicine name */
    .medicine-name {
        font-weight: 600;
    }

    /* role text */
    .role-text {
        font-size: 11px;
        color: #6B7280;
    }

    /* status pills */
    .pill {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }

    .pill-pending { 
        background: #FEF9C3; 
        color: #A16207; 
    }

    .pill-approved { 
        background: #DCFCE7; 
        color: #166534; 
    }

    .pill-rejected { 
        background: #FEE2E2; 
        color: #991B1B; 
    }

    .pill-completed {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .pill.active-filter {
        outline: 2px solid #1A73E8;
        outline-offset: 2px;
    }


    /* ACTION COLUMN */
    .action-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgb(67, 114, 245);
    }

    .action-box {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        cursor: pointer;
    }

    .action-box:hover {
        background: #F3F4F6;
    }

    .action-box img {
        width: 18px;
        height: 18px;
    }

    /* row hover */
    tbody tr:hover {
        background: #F9FAFB;
    }
    th:last-child {
        padding-left: 60px;
    }
    /* ================= MODAL ================= */
    /* Ensure modals are hidden by default and act as full screen overlays */
.modal {
    display: none; 
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); /* Dim backdrop */
    overflow: auto;
}

/* Ensure the second confirmation layer displays layered over the first */
#confirmModal {
    z-index: 1010; 
}

    .modal-content {
        background: white;
        width: 900px;
        margin: 40px auto;
        border-radius: 12px;
        padding: 24px;
        max-height: 90vh;
        overflow-y: auto;
    }

    /* header */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
    }

    .modal-title {
        font-size: 20px;
        font-weight: bold;
    }

    .modal-sub {
        font-size: 12px;
        color: #6B7280;
    }

    .close-btn {
        font-size: 18px;
        cursor: pointer;
        color: #6B7280;
    }

    /* section spacing */
    .section {
        margin-top: 20px;
    }

    /* grid info */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        gap: 12px;
    }

    .info-box {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 12px;
        font-size: 13px;
    }

    /* requisition table */
    .req-table th {
        background: #F3F4F6;
    }

    /* signatures */
    .sign-grid {
        display: grid;
        grid-template-columns: repeat(2,1fr);
        gap: 20px;
        margin-top: 15px;
    }

    .sign-box {
        border-top: 1px solid #111;
        padding-top: 6px;
        font-size: 12px;
    }
    /* ================= ACTION BUTTONS ================= */
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-approve {
        background: #10B981;
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-reject {
        background: #EF4444;
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-close {
        background: white;
        border: 1px solid #E5E7EB;
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
    }

    /* ================= CONFIRMATION MODAL ================= */
    .confirm-box {
        text-align: center;
    }

    .confirm-text {
        font-size: 14px;
        margin-bottom: 20px;
    }

    .confirm-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
</style>
</head>
<script>
// ✅ Opens the RIS slip modal and fills it with the clicked row's data
function viewReq(btn){
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

    const actions = document.getElementById("modalActions");
    const approveBtn = document.getElementById("modalApproveBtn");
    const rejectBtn = document.getElementById("modalRejectBtn");

    // Only allow Approve/Reject from the modal while the request is still pending
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

function closeRequestModal(){
    document.getElementById("requestModal").style.display = "none";
}
</script>
<body>

<div class="container">

<x-sidebar />

    <div class="main">
        {{-- ✅ HEADER - ADMIN STYLE --}}
<div class="header">
    <div>
        <div class="welcome-text">Welcome back,</div>

        <div class="welcome-name">
            {{ $userName ?? Auth::user()->name ?? 'Administrator' }}

            @php
                $user = Auth::user();

                $role = strtolower($user->role);

                // ✅ If Doctor and PIC, display as PIC
                $displayRole = ($role === 'doctor' && $user->is_physician_in_charge == 1)
                    ? 'PIC'
                    : strtoupper($role);

                $roleColor = match($role) {
                    'admin'  => '#9333EA', // Purple
                    'nurse'  => '#10B981', // Green
                    'doctor' => '#3B82F6', // Red
                    'bhw'    => '#6366F1', // Indigo
                    default  => '#6B7280'
                };
            @endphp

            <span class="role"
                  style="background-color: {{ $roleColor }}; color:white; padding:4px 12px; border-radius:12px; font-size:12px;">
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

{{-- ✅ MAIN CONTENT --}}
<div class="module-container" style="padding: 10px 20px 20px 20px;">

    {{-- PAGE TITLE & BUTTON --}}
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; width: 100%;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; letter-spacing: -0.02em; margin: 0 0 4px 0; padding: 0; line-height: 1.2;">Medicine Request Management</h2>
            <p style="color: #6B7280; font-size: 14px; margin: 0;">View, create, and approve all medicine supply requests from staff</p>
        </div>
        <button class="btn-primary" style="background: #1A73E8; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 500; font-size: 14px; cursor: pointer; white-space: nowrap; transition: background 0.2s;" 
                onclick="openRequestModal()"
                onmouseover="this.style.background='#1557B0'"
                onmouseout="this.style.background='#1A73E8'">
            + New Request
        </button>
    </div>


            {{-- STATUS CARDS — click a card to filter the table below --}}
            <div class="kpi-row" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 20px; margin-bottom: 24px; width: 100%;">
    
    {{-- PENDING — GREY THEME --}}
    <div class="kpi" id="kpi-Pending Physician"
         style="background: rgba(243, 244, 246, 0.4); border: 1px solid rgba(209, 213, 219, 0.5); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 22px 24px; border-radius: 20px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; gap: 8px; text-align: left;"
         onclick="filterRequests('Pending Physician')"
         onmouseover="this.style.transform='translateY(-4px)';"
         onmouseout="this.style.transform='translateY(0px)';"
         onmousedown="this.style.transform='translateY(-2px)';">
        <h4 style="margin: 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280;">PENDING</h4>
        <span style="font-size: 28px; font-weight: 700; line-height: 1; margin-top: 0; color: #111827;">{{ $requests->where('status','Pending Physician')->count() }}</span>
    </div>

    {{-- APPROVED — GREEN THEME --}}
    <div class="kpi" id="kpi-Approved"
         style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 22px 24px; border-radius: 20px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; gap: 8px; text-align: left;"
         onclick="filterRequests('Approved')"
         onmouseover="this.style.transform='translateY(-4px)';"
         onmouseout="this.style.transform='translateY(0px)';"
         onmousedown="this.style.transform='translateY(-2px)';">
        <h4 style="margin: 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #059669;">APPROVED</h4>
        <span style="font-size: 28px; font-weight: 700; line-height: 1; margin-top: 0; color: #065F46;">{{ $requests->where('status','Approved')->count() }}</span>
    </div>

    {{-- REJECTED — RED THEME --}}
    <div class="kpi" id="kpi-Rejected"
         style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 22px 24px; border-radius: 20px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; gap: 8px; text-align: left;"
         onclick="filterRequests('Rejected')"
         onmouseover="this.style.transform='translateY(-4px)';"
         onmouseout="this.style.transform='translateY(0px)';"
         onmousedown="this.style.transform='translateY(-2px)';">
        <h4 style="margin: 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #DC2626;">REJECTED</h4>
        <span style="font-size: 28px; font-weight: 700; line-height: 1; margin-top: 0; color: #991B1B;">{{ $requests->where('status','Rejected')->count() }}</span>
    </div>

    {{-- COMPLETED — BLUE THEME --}}
    <div class="kpi" id="kpi-Completed"
         style="background: rgba(37, 99, 235, 0.08); border: 1px solid rgba(37, 99, 235, 0.2); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); padding: 22px 24px; border-radius: 20px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; gap: 8px; text-align: left;"
         onclick="filterRequests('Completed')"
         onmouseover="this.style.transform='translateY(-4px)';"
         onmouseout="this.style.transform='translateY(0px)';"
         onmousedown="this.style.transform='translateY(-2px)';">
        <h4 style="margin: 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #2563EB;">COMPLETED</h4>
        <span style="font-size: 28px; font-weight: 700; line-height: 1; margin-top: 0; color: #1E40AF;">{{ $requests->where('status','Completed')->count() }}</span>
    </div>

</div>


            {{-- TABLE --}}
            <div class="table-card" style="background:white; border:1px solid #E5E7EB; border-radius:8px; overflow:hidden;">
                
                <div class="toolbar-section" style="padding:16px; border-bottom:1px solid #E5E7EB; display:flex; justify-content:space-between; align-items:center;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="toolbar-title" style="font-weight:600; font-size:15px;" id="tableTitle">All Request Records</div>
                        <button type="button" onclick="filterRequests('all')" style="background:#EFF6FF; color:#1A73E8; border:none; padding:4px 10px; border-radius:20px; font-size:12px; cursor:pointer;">Show All</button>
                    </div>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search by requester, medicine, purpose, or RIS #..." style="border:1px solid #D1D5DB; border-radius:6px; padding:6px 12px; font-size:14px; width:280px;">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b" style="background:#F9FAFB;">
                                <th class="p-3" style="font-weight:600; color:#4B5563;">DATE</th>
                                <th class="p-3" style="font-weight:600; color:#4B5563;">REQUESTED BY</th>
                                <th class="p-3" style="font-weight:600; color:#4B5563;">MEDICINE</th>
                                <th class="p-3" style="font-weight:600; color:#4B5563;">QUANTITY</th>
                                <th class="p-3" style="font-weight:600; color:#4B5563;">NOTES</th>
                                <th class="p-3" style="font-weight:600; color:#4B5563;">STATUS</th>
                                <th class="p-3 text-right" style="font-weight:600; color:#4B5563;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr class="border-b hover:bg-gray-50 request-row" data-row-status="{{ $req->status ?? 'Pending Physician' }}"
                                data-search="{{ strtolower(($req->requester->name ?? 'unknown').' '.($req->medicine->name ?? 'deleted item').' '.($req->reason ?? '').' '.($req->ris_number ?? '')) }}">
                                <td class="p-3">{{ $req->created_at->format('M d, Y') }}</td>
                                <td class="p-3 font-semibold">
                                    {{ $req->requester->name ?? 'Unknown' }}
                                    <br><small style="color:#6B7280;">({{ strtoupper($req->requester->role ?? 'N/A') }})</small>
                                </td>
                                <td class="p-3">{{ $req->medicine->name ?? 'Deleted Item' }}</td>
                                <td class="p-3">{{ number_format($req->quantity_requested) }} units</td>
                                <td class="p-3 text-gray-600">{{ $req->reason ?? '—' }}</td>
                                <td class="p-3">
                                    @php
                                        $pillClass = match($req->status ?? 'Pending Physician') {
                                            'Approved' => 'pill-approved',
                                            'Rejected' => 'pill-rejected',
                                            'Completed' => 'pill-completed',
                                            default => 'pill-pending',
                                        };
                                    @endphp
                                    <span class="pill {{ $pillClass }}">
                                        {{ strtoupper($req->status ?? 'PENDING PHYSICIAN') }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    {{-- ✅ VIEW: OPENS FULL RIS SLIP MODAL --}}
                                    <button class="btn-sm view-req-btn"
                                        style="background:#F3E8FF; color:#6D28D9; border:none; padding:4px 8px; border-radius:4px; font-size:12px; margin:0 2px;"
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


                                    <button class="btn-sm" style="background:#F3F4F6; color:#374151; border:none; padding:4px 8px; border-radius:4px; font-size:12px; margin:0 2px;" onclick="deleteReq({{ $req->id }})">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-5 text-center text-gray-500">No request records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ✅ REQUEST SLIP VIEW MODAL (RIS-style, with Approve/Reject inside) --}}
<div class="modal" id="requestModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <div class="modal-title">Requisition and Issue Slip</div>
                <div class="modal-sub" id="modalRequestNo">Request #—</div>
            </div>
            <span class="close-btn" onclick="closeRequestModal()">&times;</span>
        </div>

        <div class="section info-grid">
            <div class="info-box"><b>Date Submitted:</b> <span id="modalDate">—</span></div>
            <div class="info-box"><b>RIS Control No.:</b> <span id="modalRis">—</span></div>
            <div class="info-box"><b>Responsibility Center Code:</b> <span id="modalCenter">—</span></div>
            <div class="info-box"><b>Date Prepared:</b> <span id="modalPrepared">—</span></div>
            <div class="info-box"><b>Requested By:</b> <span id="modalRequester">—</span></div>
            <div class="info-box"><b>Status:</b> <span id="modalStatus">—</span></div>
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
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="modalMedicine">—</td>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="modalUnit">—</td>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="modalBatch">—</td>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="modalExpiry">—</td>
                        <td style="padding:8px; border:1px solid #E5E7EB;" id="modalQty">—</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="info-box"><b>Purpose:</b> <span id="modalPurpose">—</span></div>
        </div>

        <div class="section">
            <div class="info-box"><b>Physician Notes:</b> <span id="modalNotes">—</span></div>
        </div>

        <div class="modal-actions" id="modalActions">
            <button class="btn-close" onclick="closeRequestModal()">Close</button>
            <button class="btn-reject" id="modalRejectBtn">Reject</button>
            <button class="btn-approve" id="modalApproveBtn">Approve</button>
        </div>
    </div>
</div>

{{-- ✅ SCRIPTS & ALERTS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Success / Error Messages
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Success!', text: "{{ session('success') }}", confirmButtonColor: '#1A73E8' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Error!', text: "{{ session('error') }}", confirmButtonColor: '#EF4444' });
    @endif


    // Example Functions
    function openRequestModal() {
        Swal.fire({ title: 'New Medicine Request', html: '<p>Form will load here...</p>', icon: 'info' });
    }

    function editReq(id) {
        Swal.fire({ title: 'Edit Request #'+id, icon: 'info' });
    }

    function updateStatus(id, status) {
        const actionLabel = status === 'Approved' ? 'Approve' : 'Reject';
        Swal.fire({
            title: `${actionLabel} this request?`,
            html: `
                <p style="margin:0 0 8px; color:#6B7280; font-size:13px; text-align:left;">Physician notes (optional)</p>
                <textarea id="physicianNotesInput" class="swal2-textarea" placeholder="e.g. Approved for immediate dispensing" style="margin-top:0;"></textarea>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'Approved' ? '#10B981' : '#EF4444',
            confirmButtonText: `Yes, ${status}!`,
            preConfirm: () => {
                return document.getElementById('physicianNotesInput').value;
            }
        }).then((res) => {
            if (res.isConfirmed) {
                const notes = encodeURIComponent(res.value || '');
                window.location.href = `/admin/request/${id}/status/${status}?physician_notes=${notes}`;
            }
        });
    }

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
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Filter the "All Request Records" table by status and/or search text
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

        // Highlight the active KPI card
        document.querySelectorAll('.kpi').forEach(card => card.style.outline = 'none');
        const activeCard = document.getElementById('kpi-' + status);
        if (activeCard) {
            activeCard.style.outline = '2px solid #1A73E8';
            activeCard.style.outlineOffset = '2px';
        }

        applyFilters();
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);

</script>
</body>
</html>
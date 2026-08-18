<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Reports</title>

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

    /* ================= MAIN ================= */
    .main {
        margin-left: 260px;
        width: calc(100% - 260px);
        padding: 24px;
        box-sizing: border-box;
    }

    /* ================= HEADER ================= */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .welcome-text { font-size: 14px; color: #374151; margin-bottom: 5px; }

    .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }

    .role {
        background: #9333EA;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 8px;
        text-transform: uppercase;
    }

    .right { text-align: right; font-size: 12px; color: #374151; }

    .header-divider {
        width: 100%;
        height: 1px;
        background: #E5E7EB;
        margin: 16px 0;
    }

    .page-title {
        font-size: 22px;
        font-weight: bold;
        color: #111827;
        margin-bottom: 16px;
    }

    /* ================= REPORT CONTROLS ================= */
    .report-controls {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .filters {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        font-size: 12px;
        color: #374151;
        gap: 4px;
    }

    .filter-group label {
        font-weight: 500;
    }

    .filter-group input,
    .filter-group select {
        padding: 0 10px;
        height: 38px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 13px;
        background: #FFFFFF;
        transition: 0.2s;
        box-sizing: border-box;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #1A73E8;
        box-shadow: 0 0 0 2px rgba(26,115,232,0.1);
    }

    /* ===== BUTTONS ===== */
    .btn-outline {
        border: 1px solid #D1D5DB;
        background: white;
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-outline:hover {
        background: #F9FAFB;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        padding: 25px;
        box-sizing: border-box;
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        min-height: 90px;
        transition: all 0.2s ease;
    }

    .action-card img {
        opacity: 0.85;
    }

    .action-card span {
        display: block;
        font-size: 15px;
        font-weight: 600;
    }

    .action-card small {
        display: block;
        font-size: 13px;
        color: #6B7280;
        margin-top: 2px;
    }

    .action-card:hover {
        border-color: #2563eb;
        background: #EFF6FF;
        transform: translateY(-2px);
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
                @php
                    $userName = session('admin_name') ?? Auth::user()->name ?? 'User';
                    $role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'admin');
                    $displayRole = strtoupper($role);
                @endphp
                {{ $userName }}
                <span class="role">{{ $displayRole }}</span>
            </div>
        </div>
        <div class="right">
            <b>Velasquez Health Center</b><br>
            @php
                date_default_timezone_set('Asia/Manila');
            @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>

    <div class="header-divider"></div>

    <div class="page-title">Reports</div>

    <!-- REPORTS OVERVIEW -->

        <div class="action-grid">

            <a href="{{ route('admin.reports.patient') }}" class="action-card">
                <img src="/icons/patient-records.png" width="30" alt="">
                <div>
                    <span>Patient Reports</span>
                    <small id="patientReports">—</small>
                </div>
            </a>

            <a href="{{ route('admin.reports.risk') }}" class="action-card">
                <img src="/icons/AI-forecast.png" width="25" alt="">
                <div>
                    <span>Patient Risk Reports</span>
                    <small id="riskReports">—</small>
                </div>
            </a>

            <a href="{{ route('admin.reports.medicine') }}" class="action-card">
                <img src="/icons/medicine-inventory.png" width="25" alt="">
                <div>
                    <span>Medicine Inventory Reports</span>
                    <small id="inventoryReports">—</small>
                </div>
            </a>

            <a href="{{ route('admin.reports.dispensing') }}" class="action-card">
                <img src="/icons/reports.png" width="25" alt="">
                <div>
                    <span>Dispensing Reports</span>
                    <small id="dispensingReports">—</small>
                </div>
            </a>

            <a href="{{ route('admin.reports.operational') }}" class="action-card">
                <img src="/icons/reports.png" width="25" alt="">
                <div>
                    <span>Operational Reports</span>
                    <small id="operationReports">—</small>
                </div>
            </a>

        </div>

</div>
</div>

<!-- API Counters - Will be updated as we build each report -->
<script>
// Replace these with Laravel API routes as we build each report
document.addEventListener('DOMContentLoaded', function() {
    // Patient Reports Count
    fetch("{{ route('admin.reports.api.patient') }}")
        .then(res => res.json())
        .then(data => {
            document.getElementById('patientReports').innerText = data.total ?? 'Ready';
        })
        .catch(() => document.getElementById('patientReports').innerText = 'Ready');

    // Risk Reports Count
    fetch("{{ route('admin.reports.api.risk') }}")
        .then(res => res.json())
        .then(data => {
            document.getElementById('riskReports').innerText = data.total ?? 'Ready';
        })
        .catch(() => document.getElementById('riskReports').innerText = 'Ready');

    // Inventory Reports Count
    fetch("{{ route('admin.reports.api.medicine') }}")
        .then(res => res.json())
        .then(data => {
            document.getElementById('inventoryReports').innerText = data.total ?? 'Ready';
        })
        .catch(() => document.getElementById('inventoryReports').innerText = 'Ready');

    // Dispensing Reports Count
    fetch("{{ route('admin.reports.api.dispensing') }}")
        .then(res => res.json())
        .then(data => {
            document.getElementById('dispensingReports').innerText = data.total ?? 'Ready';
        })
        .catch(() => document.getElementById('dispensingReports').innerText = 'Ready');

    // Operational Reports Count
    fetch("{{ route('admin.reports.api.operational') }}")
        .then(res => res.json())
        .then(data => {
            document.getElementById('operationReports').innerText = data.total ?? 'Ready';
        })
        .catch(() => document.getElementById('operationReports').innerText = 'Ready');
});
</script>

</body>
</html>
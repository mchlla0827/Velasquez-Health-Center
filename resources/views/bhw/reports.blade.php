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


    /* ================= SIDEBAR (EXACT COPY FROM DASHBOARD) ================= */
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


    .logo{ width: 40px; height: 40px; border-radius: 50%; }


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
        margin-left: 250px;
        width: calc(100% - 250px);
        padding: 24px;
        box-sizing: border-box;
    }


    /* ================= HEADER (EXACT COPY FROM DASHBOARD) ================= */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }


    .welcome-text{ font-size: 14px; color: #374151; margin-bottom: 5px; }


    .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }


    .role {
        background: #6366F1;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
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


    /* ================= TABLE ================= */


    /* ===== REPORT CONTROLS ===== */
.report-controls {
    display: flex;
    justify-content: space-between;
    align-items: flex-end; /* Keeps the right-side elements aligned to bottom */
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}


.filters {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    /* FIX: This aligns the button to the bottom of the input boxes */
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
    padding: 0 10px;       /* Adjusted for height control */
    height: 38px;          /* Defined height for consistency */
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 13px;
    background: #FFFFFF;
    transition: 0.2s;
    box-sizing: border-box; /* Crucial: ensures height includes padding/border */
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


/* Title */
.card-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 25px;
}


/* GRID - BIGGER SPACING */
.action-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}


/* BIGGER CARD */
.action-card {
    display: flex;
    align-items: center;
    gap: 15px;


    padding: 18px;              /* increased padding */
    border: 1px solid #E5E7EB;
    border-radius: 12px;


    text-decoration: none;
    color: inherit;


    min-height: 90px;           /* makes box taller */
}


/* ICON BIGGER */
.action-card img {
    opacity: 0.85;
}


/* TEXT */
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


/* optional hover (very subtle only) */
.action-card:hover {
    border-color: #D1D5DB;
    background: #FAFAFA;
}


</style>
</head>


<body>


<div class="container">

<!-- SIDEBAR (BHW Order) -->
<div class="sidebar">
    <div class="sidebar-header">
        <img src="/bhclogo.jpg" class="logo">
        <div class="brand-wrapper">
            <div class="brand">Barangay Health System</div>
            <div class="sub">Health Information System</div>
        </div>
    </div>

    <div class="group">Main</div>
    <a class="nav-item" href="/bhw/dashboard"><img src="/icons/dashboard2.png" class="nav-icon">Dashboard</a>

    <div class="group">Inventory</div>
    <a class="nav-item" href="/bhw/inventory"><img src="/icons/medicine-inventory.png" class="nav-icon">Medicine Inventory</a>

    <div class="group">Patients</div>
    <a class="nav-item" href="/bhw/patient-registration"><img src="/icons/patient-registration.png" class="nav-icon">Patient Registration</a>
    <a class="nav-item" href="/bhw/patient-records"><img src="/icons/patient-records.png" class="nav-icon">Patient Records</a>
    <a class="nav-item" href="/bhw/triage"><img src="/icons/patient-triage.png" class="nav-icon">Patient Triage</a>

    <div class="group">Reports</div>
    <a class="nav-item active" href="/bhw/reports"><img src="/icons/reports.png" class="nav-icon">Reports</a>

    <form method="POST" action="/logout" style="margin-top: 20px;">
        @csrf
        <button class="nav-item" style="width:100%; text-align:left; border:none; background:none; cursor:pointer;">
            <img src="/icons/logout.png" class="nav-icon"> Log Out
        </button>
    </form>
</div>

<!-- MAIN -->
<div class="main">

    <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ session('user_name') }}
                    <span class="role">BHW</span>
                </div>
            </div>
        <div class="right">
            <b>Velasquez Health Center</b><br>
            @php date_default_timezone_set('Asia/Manila'); @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>

    <div class="header-divider"></div>


   <!-- KPI SUMMARY -->
<div class="content-card">


    <div class="card-title">Reports Overview</div>


    <div class="action-grid">


        <a href="/bhw/reports/patient-records" class="action-card">
            <img src="/icons/patient-records.png" width="24">
            <div>
                <span>Patient Reports</span>
                <small id="patientReports"></small>
            </div>
        </a>


        <a href="/bhw/reports/risk" class="action-card">
            <img src="/icons/AI-forecast.png" width="24">
            <div>
                <span>Patient Risk Reports</span>
                <small id="riskReports"></small>
            </div>
        </a>


        <a href="/bhw/reports/medicine" class="action-card">
            <img src="/icons/medicine-inventory.png" width="24">
            <div>
                <span>Medicine Inventory</span>
                <small id="inventoryReports"></small>
            </div>
        </a>


        <a href="/bhw/reports/dispensing" class="action-card">
            <img src="/icons/reports.png" width="24">
            <div>
                <span>Dispensing Reports</span>
                <small id="dispensingReports"></small>
            </div>
        </a>


        <a href="/bhw/reports/operational" class="action-card">
            <img src="/icons/reports.png" width="24">
            <div>
                <span>Operational Reports</span>
                <small id="operationReports"></small>
            </div>
        </a>


    </div>


    </div>
</div>
<script>
fetch('api/patient_reports.php')
.then(res => res.json())
.then(data => {
    document.getElementById('patientReports').innerText = data.total;
});


fetch('api/risk_reports.php')
.then(res => res.json())
.then(data => {
    document.getElementById('riskReports').innerText = data.total;
});


fetch('api/inventory_reports.php')
.then(res => res.json())
.then(data => {
    document.getElementById('inventoryReports').innerText = data.total;
});


fetch('api/dispensing_reports.php')
.then(res => res.json())
.then(data => {
    document.getElementById('dispensingReports').innerText = data.total;
});


fetch('api/operation_reports.php')
.then(res => res.json())
.then(data => {
    document.getElementById('operationReports').innerText = data.total;
});
</script>
</div>
</div>
</body>
</html>

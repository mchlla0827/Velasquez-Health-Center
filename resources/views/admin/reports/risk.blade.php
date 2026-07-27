<!DOCTYPE html>
<html lang="en">
<head>
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
        background: #9333EA;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
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
.btn-primary {
    background: #1A73E8;
    color: white;
    border: none;
    padding: 9px 16px;
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    transition: 0.2s;
}


.btn-primary:hover {
    background: #1669C1;
}


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
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #E5E7EB;
    padding: 28px;
    box-sizing: border-box;
}


/* =========================
   TITLE
========================= */
.card-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 22px;
    letter-spacing: 0.2px;
}


/* =========================
   SUMMARY GRID
========================= */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}


.summary-box {
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 16px;
    background: #FAFAFA;
    transition: all 0.2s ease;
}


.summary-box:hover {
    border-color: #D1D5DB;
}


.summary-box span {
    font-size: 12px;
    color: #6B7280;
    display: block;
    margin-bottom: 6px;
}


.summary-box h3 {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}


/* =========================
   TABLE WRAPPER
========================= */
.table-container {
    overflow-x: auto;
    border-radius: 10px;
    border: 1px solid #E5E7EB;
}


/* =========================
   TABLE
========================= */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    background: #ffffff;
}


thead th {
    background: #F9FAFB;
    font-weight: 600;
    text-align: left;
    padding: 14px 12px;
    border-bottom: 1px solid #E5E7EB;
    font-size: 13px;
    color: #374151;
}


tbody td {
    padding: 12px;
    border-bottom: 1px solid #F1F5F9;
    color: #111827;
}


tbody tr:hover {
    background: #FAFAFA;
}


/* =========================
   RISK BADGE (MINIMAL STYLE)
========================= */
.risk-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    border: 1px solid #E5E7EB;
    background: #ffffff;
    font-weight: 500;
}


/* subtle emphasis only */
.risk-badge.high {
    border-color: #D1D5DB;
    font-weight: 600;
}


.risk-badge.moderate {
    opacity: 0.85;
}


.risk-badge.low {
    opacity: 0.7;
}


/* =========================
   RESPONSIVE
========================= */
@media (max-width: 1024px) {
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}


@media (max-width: 600px) {
    .summary-grid {
        grid-template-columns: 1fr;
    }
}


.system-insight {
    margin-top: 18px;
    padding: 16px 18px;
    border-radius: 12px;


    /* soft highlight background */
    background: #F0F9FF; /* very light blue */
    border: 1px solid #BAE6FD;


    box-sizing: border-box;
}


/* title */
.system-insight strong {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #0369A1; /* deep blue for emphasis */
    margin-bottom: 6px;
}


/* text */
.system-insight p {
    font-size: 13px;
    color: #1F2937;
    margin: 0;
    line-height: 1.5;
}


</style>
</head>


<body>


<div class="container">


<x-sidebar />


<!-- MAIN -->
<div class="main">


    <!-- HEADER (UNCHANGED) -->
    <div class="header">
        <div>
            <div class="welcome-text">Welcome back,</div>
            <div class="welcome-name">
                {{ Auth::user()->name }}
                <span class="role">{{ Auth::user()->role }}</span>
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


    <div class="page-title">Patient Risk-Scoring Reports</div>


    <!-- REPORTS -->
    <!-- REPORT CONTROLS -->
    <div class="report-controls">


        <div class="filters">
                <div class="filter-group">
                    <label>Time Range</label>
                    <select style="font-family: Arial, sans-serif">
                        <option>Current Month</option>
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>Custom Range</option>
                    </select>
                </div>
        <div class="filter-group">
            <label>From</label>
            <input type="date" style="font-family: Arial, sans-serif">
        </div>


        <div class="filter-group">
            <label>To</label>
            <input type="date" style="font-family: Arial, sans-serif">
        </div>


        <div class="filters">
        <button class="btn-primary">Generate</button>
        <button class="btn-outline" onclick="history.back()">Back</button>
        </div>
        </div>


    <div class="export-buttons">
        <button class="btn-outline">Export PDF</button>
        <button class="btn-outline">Export Excel</button>
    </div>


    </div>


<!-- KPI SUMMARY -->
<div class="content-card">


    <!-- SUMMARY -->
    <div class="summary-grid">


        <div class="summary-box">
            <span>High Risk</span>
            <h3>{{ $highRisk ?? '--' }}</h3>
        </div>


        <div class="summary-box">
            <span>Moderate Risk</span>
            <h3>{{ $moderateRisk ?? '--' }}</h3>
        </div>


        <div class="summary-box">
            <span>Low Risk</span>
            <h3>{{ $lowRisk ?? '--' }}</h3>
        </div>


        <div class="summary-box">
            <span>Total Evaluated</span>
            <h3>{{ $totalRisk ?? '--' }}</h3>
        </div>


    </div>


    <!-- TABLE -->
    <div class="table-container">


        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Risk Level</th>
                    <th>Score</th>
                    <th>Status</th>
                </tr>
            </thead>


            <tbody>
                @forelse($risks ?? [] as $risk)
                <tr>
                    <td>{{ $risk->full_name }}</td>
                    <td>{{ $risk->age }}</td>
                    <td>{{ $risk->gender }}</td>


                    <!-- Risk Level -->
                    <td>
                        <span class="risk-badge {{ strtolower($risk->risk_level) }}">
                            {{ $risk->risk_level }}
                        </span>
                    </td>


                    <!-- Score -->
                    <td>{{ $risk->score }}</td>


                    <!-- Status -->
                    <td>
                        @if($risk->risk_level == 'High')
                            Needs Immediate Attention
                        @elseif($risk->risk_level == 'Moderate')
                            Monitor Patient
                        @else
                            Stable
                        @endif
                    </td>


                </tr>
                @empty
                @endforelse
            </tbody>


        </table>


    </div>


</div>



   
</div>
</div>
</body>
</html>
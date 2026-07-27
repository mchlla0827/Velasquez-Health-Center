<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <title>Stock-out Log</title>

    <style>
        /* ================= GLOBAL BASE STYLES ================= */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            overflow-x: hidden;
        }

        .container { 
            display: flex; 
        }

        /* ================= SIDEBAR LAYOUT ================= */
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
            margin-left: 260px; /* Aligned precisely with sidebar width */
            width: calc(100% - 260px);
            padding: 24px;
            box-sizing: border-box;
        }

        /* ================= DASHBOARD HEADER ================= */
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

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .export-button {
            background: #1D4ED8;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

/* ================= STATS ROW — SAME AS MEDICINE INVENTORY CARDS ================= */
.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 22px 24px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.6);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.stat-card:active {
    transform: translateY(-2px);
}

/* Typography — same as inventory cards */
.stat-title {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
    margin-top: 0;
}

.stat-unit {
    font-size: 14px;
    font-weight: 500;
}

/* --- COLOR THEMES — same tint style as inventory --- */

/* 1. Total Stock-Out (Grey Theme) */
.stat-grey { 
    background: rgba(243, 244, 246, 0.4); 
    border-color: rgba(209, 213, 219, 0.5);
}
.stat-grey .stat-title { color: #6B7280; }
.stat-grey .stat-number { color: #111827; }

/* 2. Patients Affected (Yellow/Amber Theme) */
.stat-yellow { 
    background: rgba(245, 158, 11, 0.08); 
    border-color: rgba(245, 158, 11, 0.2);
}
.stat-yellow .stat-title { color: #B45309; }
.stat-yellow .stat-number { color: #78350F; }

/* 3. Average Duration (Red Theme) */
.stat-red { 
    background: rgba(239, 68, 68, 0.08); 
    border-color: rgba(239, 68, 68, 0.2);
}
.stat-red .stat-title { color: #DC2626; }
.stat-red .stat-number { color: #991B1B; }

        /* ================= RECORDS DATA TABLE ================= */
        .section-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
            margin: 0 0 16px 0;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .stockout-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stockout-table th {
            padding: 16px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6B7280;
            background: rgba(249, 250, 251, 0.6);
            border-bottom: 1px solid rgba(229, 231, 235, 0.7);
        }

        .stockout-table td {
            padding: 16px 20px;
            font-size: 14px;
            color: #1F2937;
            border-bottom: 1px solid rgba(243, 244, 246, 0.6);
        }

        .stockout-table tr:hover {
            background: rgba(249, 250, 251, 0.6);
        }

        .text-action {
            color: #165DFF;
            font-weight: 500;
        }

        .text-empty {
            padding: 40px 20px;
            text-align: center;
            color: #6B7280;
        }

/* ================= TWO COLUMN LAYOUT ================= */
.two-column-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    width: 100%;
    align-items: start;
    margin-bottom: 16px;
}

.column-section {
    display: flex;
    flex-direction: column;
    gap: 0;
}

/* ================= FULL WIDTH SECTION ================= */
.full-width-section {
    width: 100%;
    margin-bottom: 24px;
}

.summary-grid.full-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    margin: 0;
}

/* ================= SECTIONS & INFO BANNERS ================= */
.info-banner {
    position: relative;
    background: #f8fafc;
    backdrop-filter: blur(12px);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 24px 28px;
    margin: 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    height: 100%; /* make both banners same height */
}

.info-banner h2 {
    margin: 0 0 6px 0;
    color: #334155;
    font-weight: 700;
    font-size: 17px;
}

.info-banner p {
    margin: 0;
    color: #64748b;
    line-height: 1.5;
    font-size: 13px;
    opacity: 0.9;
    max-width: 78%;
    font-style: italic;
}

.banner-badge {
    position: absolute;
    top: 24px;
    right: 24px;
    backdrop-filter: blur(8px);
    border: 1px solid;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    line-height: 1;
}

.banner-badge::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    display: inline-block;
}

.badge-top {
    background: rgba(245, 158, 11, 0.08);
    border-color: rgba(245, 158, 11, 0.25);
    color: #b45309;
}
.badge-top::before { 
    background: #b45309; 
}

.badge-trend {
    background: rgba(59, 130, 246, 0.08);
    border-color: rgba(59, 130, 246, 0.25);
    color: #2563eb;
}
.badge-trend::before { 
    background: #2563eb; 
}

/* ================= CARD METRIC GRIDS ================= */
.summary-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    margin: 0;
}

.summary-card {
    background: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 24px;
    border: 1px solid rgba(255, 255, 255, 0.6);
    display: flex;
    flex-direction: column;
    gap: 8px;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.04);
    transition: all 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.6);
}

.card-demand {
    background: rgba(245, 158, 11, 0.08);
    border-color: rgba(245, 158, 11, 0.2);
}
.card-demand h4 { 
    color: #B45309; 
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.summary-value {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    line-height: 1;
}

.summary-note {
    margin-top: auto;
    font-size: 11px;
    color: #92400E;
}

.summary-empty {
    text-align: center;
    opacity: 0.7;
    padding: 32px !important;
}

/* ================= TREND ANALYSIS AREA ================= */
.trend-container {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 16px;
    width: 100%;
}

.trend-chart-area {
    padding: 0;
    min-height: 160px;
    align-items: center;
    justify-content: center;
}

.chart-placeholder {
    text-align: center;
    opacity: 0.6;
}

.chart-label {
    font-size: 15px;
    font-weight: 600;
    color: #374151;
}

.chart-sub {
    font-size: 12px;
    margin: 6px 0 0 0;
}

.trend-list { 
    gap: 12px; 
}
.trend-list h4 {
    color: #374151;
    margin: 0 0 8px 0;
    font-size: 14px;
    font-weight: 600;
}

.trend-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 10px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 8px;
    font-size: 13px;
}

.trend-count {
    font-weight: 600;
    color: #C2410C;
}

/* 📱 Responsive */
@media (max-width: 768px) {
    .two-column-grid,
    .trend-container {
        grid-template-columns: 1fr;
    }
}

    </style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
<x-sidebar />

    <!-- MAIN APP WRAPPER -->
    <div class="main">

        <!-- LOGGED-IN ACCOUNT METADATA -->
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ session('admin_name') }}
                    <span class="role">{{ session('admin_role') }}</span>
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

        <!-- TITLE + CTAs -->
        <div class="top-actions">
            <div>
                <div class="page-title">Stock-Out Log</div>
            </div>
            <button class="export-button">Export Report</button>
        </div>

        <!-- STATISTICS METRIC STATS ROW -->
        <!-- STATISTICS METRIC STATS ROW -->
<div class="stats-row">
    <div class="stat-card stat-grey">
        <div class="stat-title">Total Stock-Out Incidents</div>
        <div class="stat-number">{{ $totalStockOut ?? 0 }}</div>
    </div>
    <div class="stat-card stat-yellow">
        <div class="stat-title">Total Patients Affected</div>
        <div class="stat-number">{{ $patientsAffected ?? 0 }}</div>
    </div>
    <div class="stat-card stat-red">
        <div class="stat-title">Average Stock-Out Duration</div>
        <div class="stat-number">{{ $avgDuration ?? 0 }} <span class="stat-unit">days</span></div>
    </div>
</div>

        <!-- TABULAR RECORDS DATA SECTION -->
        <div class="records-section">
            <div class="section-header">
                <h3>Stock-Out Records</h3>
            </div>
            <div class="table-container">
                <table class="stockout-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Medicine Name</th>
                            <th>Quantity Needed</th>
                            <th>Patients Affected</th>
                            <th>Duration</th>
                            <th>Action Taken</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockOutRecords as $record)
                            <tr>
                                <td>{{ $record->date }}</td>
                                <td>{{ $record->medicine_name }}</td>
                                <td>{{ $record->quantity_needed }}</td>
                                <td>{{ $record->patients_affected }}</td>
                                <td>{{ $record->duration }} days</td>
                                <td><span class="text-action">View Details</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-empty">No stock-out records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

<div class="two-column-grid">
    <!-- LEFT COLUMN: MOST FREQUENTLY STOCKED-OUT -->
    <div class="column-section">
        <div class="info-banner">
            <div class="banner-badge badge-top">TOP SHORTAGE</div>
            <h2>Most Frequently Stocked-Out Medicine</h2>
            <p>Medicines with highest recurrence of stock shortages — prioritize these for procurement planning</p>
        </div>
    </div>

    <!-- RIGHT COLUMN: RECENT TREND -->
    <div class="column-section">
        <div class="info-banner">
            <div class="banner-badge badge-trend">TREND</div>
            <h2>Recent Stock-Out Trend</h2>
            <p>Shortage frequency over the last 6 months — helps identify seasonal or recurring supply issues</p>
        </div>
    </div>
</div>


<!-- ✅ FULL WIDTH: NO STOCK OUT HISTORY -->
<div class="full-width-section">
    <div class="summary-grid full-grid">
        @forelse($mostFrequent as $med)
            <div class="summary-card card-demand">
                <h4>{{ $med->name }}</h4>
                <span class="summary-value">{{ $med->count }} times</span>
                <small class="summary-note">Last recorded: {{ $med->last_date }}</small>
            </div>
        @empty
            <div class="summary-card summary-empty">
                <span>No stock-out history available</span>
            </div>
        @endforelse
    </div>
</div>


<!-- ✅ TREND CHART + MONTHLY SUMMARY SECTION -->
<div class="trend-container">
    <div class="summary-card trend-chart-area">
        <div class="chart-placeholder">
            <span class="chart-label">Trend Chart</span>
            <p class="chart-sub">Monthly shortage frequency (Jan–Jun 2026)</p>
        </div>
    </div>
    <div class="summary-card trend-list">
        <h4>Monthly Summary</h4>
        @foreach($monthlyTrend as $month)
            <div class="trend-item">
                <span>{{ $month->month }}</span>
                <span class="trend-count">{{ $month->count }} incidents</span>
            </div>
        @endforeach
    </div>
</div>

    </div>
</div>

</body>
</html>
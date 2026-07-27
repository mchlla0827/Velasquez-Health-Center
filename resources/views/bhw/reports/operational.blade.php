<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <title>BHW Portal - Operational Reports</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            overflow-x: hidden;
        }

        .container { display: flex; }

        /* ================= SIDEBAR (BHW Order) ================= */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: white;
            border-right: 1px solid #E5E7EB;
            padding: 24px;
            position: fixed;
            top: 0; left: 0;
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
            margin-left: 250px;
            width: calc(100% - 260px);
            padding: 24px;
            box-sizing: border-box;
        }

        /* ================= HEADER (BHW Branding) ================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .welcome-text { font-size: 14px; color: #374151; margin-bottom: 5px; }
        .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }

        .role {
            background: #6366F1; /* BHW Indigo Badge */
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

        /* ===== REPORT CONTROLS ===== */
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
            font-family: Arial, sans-serif;
        }

        .filter-group label { font-weight: 500; }

        .filter-group input, .filter-group select {
            padding: 0 10px;
            height: 38px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 13px;
            background: #FFFFFF;
            box-sizing: border-box;
        }

        .btn-primary {
            background: #1A73E8;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-outline {
            border: 1px solid #D1D5DB;
            background: white;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
        }

        /* ===== CONTENT CARD & SUMMARY ===== */
        .content-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            padding: 25px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .summary-box {
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 15px;
            background: #FAFAFA;
        }

        .summary-box span { font-size: 12px; color: #6B7280; }
        .summary-box h3 { font-size: 18px; margin-top: 5px; }

        /* TABLE */
        .table-container {
            overflow-x: auto;
        }

        table { width: 100%; border-collapse: collapse; font-size: 14px; background: #ffffff; }
        thead th { background: #F9FAFB; padding: 14px 12px; text-align: left; border-bottom: 1px solid #E5E7EB; font-weight: 600; color: #374151; }
        tbody td { padding: 12px; border-bottom: 1px solid #F1F5F9; color: #111827; }
        tbody tr:hover { background: #FAFAFA; }

        
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
        <div class="page-title">Daily Operational Logs</div>

        <!-- REPORT CONTROLS -->
        <div class="report-controls">
            <div class="filters">
                <div class="filter-group">
                    <label>Filter Date</label>
                    <select style="font-family: Arial, sans-serif">
                        <option>Today</option>
                        <option>Yesterday</option>
                        <option>This Week</option>
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
            <div class="summary-grid">
                <div class="summary-box">
                    <span>New Registrations</span>
                    <h3>{{ $newRegistrations ?? '0' }}</h3>
                </div>
                <div class="summary-box">
                    <span>Triage Entries</span>
                    <h3>{{ $triageEntries ?? '0' }}</h3>
                </div>
                <div class="summary-box">
                    <span>Stock Inquiries</span>
                    <h3>{{ $stockInquiries ?? '0' }}</h3>
                </div>
                <div class="summary-box">
                    <span>Your Transactions</span>
                    <h3>{{ $transactions ?? '0' }}</h3>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Action Performed</th>
                            <th>Target Module</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs ?? [] as $log)
                        <tr>
                            <td>{{ date('h:i A', strtotime($log->created_at)) }}</td>
                            <td>{{ $log->activity }}</td>
                            <td>{{ $log->module }}</td>
                            <td>{{ $log->status }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 20px; color: #6B7280;">No activity logs found for your account today.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

       
    </div>
</div>
</body>
</html>
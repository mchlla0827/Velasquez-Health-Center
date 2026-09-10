<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <title>Stock-Out Log - Velasquez Health Center</title>

    <style>
        /* ================= GLOBAL BASE STYLES ================= */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            color: #1F2937;
            overflow-x: hidden;
        }

        .container { 
            display: flex; 
        }

        /* ================= MAIN CONTENT WRAPPER ================= */
        .main {
            margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box;
        }

        /* ================= DASHBOARD HEADER ================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .welcome-text { 
            font-size: 13px; 
            color: #6B7280; 
            margin-bottom: 4px; 
        }

        .welcome-name { 
            font-weight: 700; 
            color: #111827; 
            font-size: 18px; 
            display: flex; 
            align-items: center; 
        }

        .role {
            background: {{ $roleColor ?? '#9333EA' }};
            color: white;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
            text-transform: uppercase;
        }

        .right { 
            text-align: right; 
            font-size: 12px; 
            color: #4B5563; 
        }

        .header-divider {
            width: 100%;
            height: 1px;
            background: #E5E7EB;
            margin: 16px 0 24px;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #1E293B;
            margin: 0;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn-primary {
            background: #2563EB;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary:hover { background: #1D4ED8; }

        .btn-secondary {
            background: #FFFFFF;
            color: #374151;
            border: 1px solid #D1D5DB;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .btn-secondary:hover { background: #F3F4F6; }

        /* ================= STATS ROW ================= */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #FFFFFF;
            padding: 20px 24px;
            border-radius: 14px;
            border: 1px solid #E5E7EB;
            display: flex;
            flex-direction: column;
            gap: 6px;
            transition: transform 0.2s ease;
        }
        .stat-card:hover { transform: translateY(-2px); }

        .stat-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
        }

        .stat-unit { font-size: 14px; font-weight: 500; }

        /* Themes */
        .stat-grey { background: #F9FAFB; border-color: #E5E7EB; }
        .stat-grey .stat-title { color: #6B7280; }
        .stat-grey .stat-number { color: #111827; }

        .stat-yellow { background: #FFFBEB; border-color: #FDE047; }
        .stat-yellow .stat-title { color: #D97706; }
        .stat-yellow .stat-number { color: #92400E; }

        .stat-red { background: #FEF2F2; border-color: #FCA5A5; }
        .stat-red .stat-title { color: #DC2626; }
        .stat-red .stat-number { color: #991B1B; }

        /* ================= DATA TABLE ================= */
        .table-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .toolbar-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #F3F4F6;
        }

        .toolbar-title { font-size: 15px; font-weight: 700; color: #1F2937; }

        .search-input {
            width: 250px;
            padding: 8px 12px;
            font-size: 13px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            outline: none;
        }
        .search-input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        .overflow-x-auto { overflow-x: auto; width: 100%; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; }

        thead th {
            background: #F9FAFB;
            color: #4B5563;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 12px 20px;
            border-bottom: 1px solid #E5E7EB;
            text-transform: uppercase;
        }

        tbody tr { border-bottom: 1px solid #F3F4F6; transition: background 0.15s ease; }
        tbody tr:hover { background-color: #F8FAFC; }
        tbody td { padding: 14px 20px; color: #374151; vertical-align: middle; }

        .text-action {
            color: #2563EB;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .text-action:hover { text-decoration: underline; }

        .text-empty {
            padding: 40px 20px;
            text-align: center;
            color: #6B7280;
        }

        /* ================= TWO COLUMN ANALYTICS GRID ================= */
        .analytics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .column-box {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-banner {
            position: relative;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 20px 24px;
        }

        .info-banner h2 { margin: 0 0 6px 0; color: #1E293B; font-weight: 700; font-size: 16px; }
        .info-banner p { margin: 0; color: #64748B; font-size: 12.5px; line-height: 1.4; }

        .banner-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            border: 1px solid;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-top { background: #FEF3C7; border-color: #FCD34D; color: #92400E; }
        .badge-trend { background: #EFF6FF; border-color: #BFDBFE; color: #1E40AF; }

        /* Top shortage list cards */
        .summary-grid { display: flex; flex-direction: column; gap: 12px; }

        .summary-card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 16px 20px;
            border: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-card h4 { margin: 0; font-size: 14px; font-weight: 700; color: #1F2937; }
        .summary-value { font-size: 14px; font-weight: 700; color: #DC2626; }
        .summary-note { font-size: 11px; color: #6B7280; display: block; margin-top: 2px; }

        /* Trend Box */
        .trend-container {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .chart-box { height: 180px; width: 100%; }

        .trend-list { display: flex; flex-direction: column; gap: 8px; }
        .trend-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            background: #F9FAFB;
            border-radius: 8px;
            font-size: 12px;
        }
        .trend-count { font-weight: 700; color: #C2410C; }

        /* MODAL STYLES */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4); display: none; justify-content: center; align-items: center; z-index: 1000;
        }
        .modal-card {
            background: white; border-radius: 14px; padding: 24px; width: 100%; max-width: 480px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .modal-header h3 { margin: 0; font-size: 16px; font-weight: 700; }
        .close-btn { background: none; border: none; font-size: 18px; cursor: pointer; color: #6B7280; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px; }
        .form-control { width: 100%; padding: 8px 12px; font-size: 13px; border: 1px solid #D1D5DB; border-radius: 8px; box-sizing: border-box; }

        @media (max-width: 992px) {
            .stats-row { grid-template-columns: 1fr; }
            .analytics-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
<div class="container">

    <!-- SIDEBAR -->
    <x-sidebar />

    <!-- MAIN CONTENT -->
    <div class="main">

        <!-- LOGGED-IN ACCOUNT METADATA -->
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ $userName ?? 'User' }}
                    <span class="role">{{ strtoupper($role ?? 'ADMIN') }}</span>
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

        @if (session('success'))
            <div style="margin: 0 0 20px; padding: 14px 18px; background: #DCFCE7; border: 1px solid #86EFAC; color: #166534; border-radius: 6px; font-size: 14px; font-weight: bold;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="margin: 0 0 20px; padding: 14px 18px; background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; border-radius: 6px; font-size: 14px;">
                <b>Could not save. Please fix the following:</b>
                <ul style="margin: 8px 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

                <!-- TITLE -->
        <div class="top-actions">
            <h1 class="page-title">Stock-Out Log</h1>
            <div style="font-size: 12.5px; color: #6B7280; font-weight: 500;">
                Automatically detected from inventory - no manual entry needed
            </div>
        </div>

        <!-- STATISTICS METRIC STATS ROW -->
        <div class="stats-row">
            <div class="stat-card stat-red">
                <div class="stat-title">Currently Out of Stock</div>
                <div class="stat-number">{{ number_format($activeCount ?? 0) }}</div>
            </div>
            <div class="stat-card stat-grey">
                <div class="stat-title">Total Stock-Out Incidents</div>
                <div class="stat-number">{{ number_format($totalStockOut ?? 0) }}</div>
            </div>
            <div class="stat-card stat-yellow">
                <div class="stat-title">Total Patients Affected</div>
                <div class="stat-number">{{ number_format($patientsAffected ?? 0) }}</div>
            </div>
            <div class="stat-card stat-grey">
                <div class="stat-title">Average Stock-Out Duration</div>
                <div class="stat-number">{{ number_format($avgDuration ?? 0, 1) }} <span class="stat-unit">days</span></div>
            </div>
        </div>

        <!-- SO-TABS -->
        <div class="so-tabs">
            <button type="button" class="so-tab active" onclick="soSwitchTab('active')" id="soTabActiveBtn">
                Active Stock-Outs <span class="so-tab-count">{{ $activeStockOuts->count() ?? 0 }}</span>
            </button>
            <button type="button" class="so-tab" onclick="soSwitchTab('history')" id="soTabHistoryBtn">
                Stock-Out History <span class="so-tab-count">{{ $stockOutHistory->count() ?? 0 }}</span>
            </button>
        </div>

        <!-- ACTIVE STOCK-OUTS TABLE -->
        <div class="table-card" id="soActivePanel">
            <div class="toolbar-section">
                <div class="toolbar-title">Active Stock-Outs</div>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Current Usable Stock</th>
                            <th>Stock-Out Date</th>
                            <th>Days Out of Stock</th>
                            <th>Patients Affected</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeStockOuts ?? [] as $item)
                            <tr>
                                <td class="font-semibold">{{ $item->medicine_name }}</td>
                                <td>{{ $item->usable_stock }}</td>
                                <td>{{ $item->stockout_date->format('M d, Y h:i A') }}</td>
                                <td>{{ $item->days_out }} {{ $item->days_out == 1 ? 'day' : 'days' }}</td>
                                <td>{{ $item->affected_count }}</td>
                                <td><span class="so-badge so-badge-active">Active</span></td>
                                <td><a href="#" class="text-action" onclick="soViewDetails({{ $item->id }}); return false;">View Details</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-empty">No active stock-outs right now.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- STOCK-OUT HISTORY TABLE -->
        <div class="table-card" id="soHistoryPanel" style="display:none;">
            <div class="toolbar-section">
                <div class="toolbar-title">Stock-Out History</div>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Stock-Out Date</th>
                            <th>Resolved Date</th>
                            <th>Duration</th>
                            <th>Patients Affected</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockOutHistory ?? [] as $item)
                            <tr>
                                <td class="font-semibold">{{ $item->medicine_name }}</td>
                                <td>{{ $item->stockout_date->format('M d, Y h:i A') }}</td>
                                <td>{{ $item->resolved_date ? $item->resolved_date->format('M d, Y h:i A') : '-' }}</td>
                                <td>{{ $item->duration_days ?? '-' }} {{ $item->duration_days == 1 ? 'day' : 'days' }}</td>
                                <td>{{ $item->affected_count }}</td>
                                <td><span class="so-badge so-badge-resolved">Resolved</span></td>
                                <td><a href="#" class="text-action" onclick="soViewDetails({{ $item->id }}); return false;">View Details</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-empty">No resolved stock-outs yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- VIEW DETAILS MODAL -->
        <div id="soDetailsModal" class="so-modal-backdrop" style="display:none;">
            <div class="so-modal-box">
                <div class="so-modal-header">
                    <h3 id="soDetailsTitle">Stock-Out Details</h3>
                    <button type="button" class="so-modal-close" onclick="soCloseDetails()">&times;</button>
                </div>
                <div class="so-modal-body" id="soDetailsBody">
                    <div style="text-align:center; padding: 40px; color: #9CA3AF;">Loading...</div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.main -->
</div>
<!-- /.container -->

<style>
    .so-tabs { display: flex; gap: 6px; margin: 20px 0 14px; border-bottom: 1px solid #E5E7EB; }
    .so-tab {
        background: none; border: none; padding: 10px 18px; font-size: 13.5px; font-weight: 600;
        color: #6B7280; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -1px;
        display: flex; align-items: center; gap: 8px;
    }
    .so-tab.active { color: #2563EB; border-bottom-color: #2563EB; }
    .so-tab-count { background: #F3F4F6; color: #374151; font-size: 11px; font-weight: 700; padding: 1px 8px; border-radius: 999px; }
    .so-tab.active .so-tab-count { background: #DBEAFE; color: #1D4ED8; }

    .so-badge { font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px; }
    .so-badge-active { background: #FEE2E2; color: #B91C1C; }
    .so-badge-resolved { background: #DCFCE7; color: #166534; }

    .so-modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1000; display: flex; align-items: center; justify-content: center; }
    .so-modal-box { background: #fff; width: 95%; max-width: 700px; max-height: 85vh; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; }
    .so-modal-header { padding: 18px 22px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; }
    .so-modal-header h3 { margin: 0; font-size: 16px; color: #111827; }
    .so-modal-close { background: none; border: none; font-size: 22px; cursor: pointer; color: #9CA3AF; }
    .so-modal-body { padding: 20px 22px; overflow-y: auto; }

    .so-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px 20px; margin-bottom: 18px; }
    .so-info-item .k { font-size: 10.5px; font-weight: 700; text-transform: uppercase; color: #9CA3AF; }
    .so-info-item .v { font-size: 13.5px; font-weight: 600; color: #111827; margin-top: 2px; }

    .so-impact-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
    .so-impact-table th { text-align: left; background: #F9FAFB; color: #6B7280; font-size: 10.5px; text-transform: uppercase; padding: 8px 10px; }
    .so-impact-table td { padding: 9px 10px; border-top: 1px solid #F1F5F9; }
    .so-status-pill { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; }
    .so-status-pill.partial { background: #FEF3C7; color: #92400E; }
    .so-status-pill.none { background: #FEE2E2; color: #991B1B; }
</style>

<script>
    function soSwitchTab(tab) {
        document.getElementById('soActivePanel').style.display = tab === 'active' ? 'block' : 'none';
        document.getElementById('soHistoryPanel').style.display = tab === 'history' ? 'block' : 'none';
        document.getElementById('soTabActiveBtn').classList.toggle('active', tab === 'active');
        document.getElementById('soTabHistoryBtn').classList.toggle('active', tab === 'history');
    }

    function soViewDetails(id) {
        document.getElementById('soDetailsModal').style.display = 'flex';
        document.getElementById('soDetailsBody').innerHTML = '<div style="text-align:center; padding: 40px; color: #9CA3AF;">Loading...</div>';

        fetch(`/stockout/${id}/details`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('soDetailsTitle').textContent = data.medicine_name + ' - Stock-Out Details';

                let rows = '';
                if (data.affected_patients && data.affected_patients.length) {
                    data.affected_patients.forEach(p => {
                        const statusClass = p.status === 'not_dispensed_stockout' ? 'none' : 'partial';
                        const statusLabel = p.status === 'not_dispensed_stockout' ? 'Not Dispensed' : 'Partially Dispensed';
                        rows += `<tr>
                            <td>${p.patient_name ?? '-'}</td>
                            <td>${p.prescribed ?? '-'}</td>
                            <td>${p.dispensed ?? 0}</td>
                            <td>${p.unfulfilled ?? 0}</td>
                            <td><span class="so-status-pill ${statusClass}">${statusLabel}</span></td>
                            <td>${p.date ?? '-'}</td>
                        </tr>`;
                    });
                } else {
                    rows = '<tr><td colspan="6" class="text-empty">No affected patients recorded for this stock-out.</td></tr>';
                }

                document.getElementById('soDetailsBody').innerHTML = `
                    <div class="so-info-grid">
                        <div class="so-info-item"><div class="k">Status</div><div class="v">${data.status === 'active' ? 'Active' : 'Resolved'}</div></div>
                        <div class="so-info-item"><div class="k">Duration</div><div class="v">${data.duration_days} ${data.duration_days == 1 ? 'day' : 'days'}</div></div>
                        <div class="so-info-item"><div class="k">Stock-Out Started</div><div class="v">${data.stockout_date}</div></div>
                        <div class="so-info-item"><div class="k">Resolved</div><div class="v">${data.resolved_date ?? 'Not yet resolved'}</div></div>
                    </div>
                    <div class="toolbar-title" style="margin-bottom: 10px;">Patient Impact</div>
                    <div class="overflow-x-auto">
                        <table class="so-impact-table">
                            <thead>
                                <tr><th>Patient</th><th>Prescribed</th><th>Dispensed</th><th>Unfulfilled</th><th>Status</th><th>Date</th></tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                `;
            })
            .catch(() => {
                document.getElementById('soDetailsBody').innerHTML = '<div style="text-align:center; padding: 40px; color: #DC2626;">Unable to load details.</div>';
            });
    }

    function soCloseDetails() {
        document.getElementById('soDetailsModal').style.display = 'none';
    }
</script>

</body>
</html>
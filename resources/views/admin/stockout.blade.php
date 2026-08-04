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
            margin-left: 250px;
            width: calc(100% - 250px);
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
            grid-template-columns: repeat(3, 1fr);
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

        <!-- TITLE + CTAs -->
        <div class="top-actions">
            <h1 class="page-title">Stock-Out Log</h1>
            <div class="btn-group">
                <button class="btn-primary" onclick="openModal()">+ Log Stock Out</button>
                <button class="btn-secondary">Export Report</button>
            </div>
        </div>

        <!-- STATISTICS METRIC STATS ROW -->
        <div class="stats-row">
            <div class="stat-card stat-grey">
                <div class="stat-title">Total Stock-Out Incidents</div>
                <div class="stat-number">{{ number_format($totalStockOut ?? 0) }}</div>
            </div>
            <div class="stat-card stat-yellow">
                <div class="stat-title">Total Patients Affected</div>
                <div class="stat-number">{{ number_format($patientsAffected ?? 0) }}</div>
            </div>
            <div class="stat-card stat-red">
                <div class="stat-title">Average Stock-Out Duration</div>
                <div class="stat-number">{{ number_format($avgDuration ?? 0, 1) }} <span class="stat-unit">days</span></div>
            </div>
        </div>

        <!-- TABULAR RECORDS DATA SECTION -->
        <div class="table-card">
            <div class="toolbar-section">
                <div class="toolbar-title">Stock-Out Records</div>
                <input type="text" id="tableSearch" class="search-input" placeholder="Search records...">
            </div>

            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Medicine Description</th>
                            <th>Quantity Needed</th>
                            <th>Patients Affected</th>
                            <th>Duration</th>
                            <th>Action Taken</th>
                        </tr>
                    </thead>
                    <tbody id="stockoutTableBody">
                        @forelse($stockOutRecords ?? [] as $record)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($record->date ?? now())->format('M d, Y') }}</td>
                                <td class="font-semibold">{{ $record->medicine_name ?? 'N/A' }}</td>
                                <td>{{ number_format($record->quantity_needed ?? 0) }}</td>
                                <td>{{ number_format($record->patients_affected ?? 0) }}</td>
                                <td>{{ $record->duration ?? 0 }} {{ ($record->duration ?? 0) == 1 ? 'day' : 'days' }}</td>
                                <td><a href="#" class="text-action">View Details</a></td>
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

        <!-- ANALYTICS SECTION GRID -->
        <div class="analytics-grid">
            
            <!-- LEFT COLUMN: MOST FREQUENT SHORTAGES -->
            <div class="column-box">
                <div class="info-banner">
                    <span class="banner-badge badge-top">Top Shortage</span>
                    <h2>Most Frequently Stocked-Out</h2>
                    <p>Medicines with recurring shortages. Prioritize these for upcoming procurement plans.</p>
                </div>

                <div class="summary-grid">
                    @forelse($mostFrequent ?? [] as $med)
                        <div class="summary-card">
                            <div>
                                <h4>{{ $med->name ?? 'Medicine Name' }}</h4>
                                <span class="summary-note">Last recorded: {{ $med->last_date ?? 'N/A' }}</span>
                            </div>
                            <div class="summary-value">{{ $med->count ?? 0 }} {{ ($med->count ?? 0) == 1 ? 'time' : 'times' }}</div>
                        </div>
                    @empty
                        <div class="summary-card" style="justify-content: center;">
                            <span style="font-size: 13px; color: #6B7280;">No shortage history recorded</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- RIGHT COLUMN: SHORTAGE TREND & MONTHLY SUMMARY -->
            <div class="column-box">
                <div class="info-banner">
                    <span class="banner-badge badge-trend">6-Month Trend</span>
                    <h2>Recent Shortage Frequency</h2>
                    <p>Tracks shortage incidents month-over-month to highlight recurring supply chain issues.</p>
                </div>

                <div class="trend-container">
                    <div class="chart-box">
                        <canvas id="trendChart"></canvas>
                    </div>

                    <div class="trend-list">
                        @forelse($monthlyTrend ?? [] as $month)
                            <div class="trend-item">
                                <span>{{ $month->month ?? 'Month' }}</span>
                                <span class="trend-count">{{ $month->count ?? 0 }} {{ ($month->count ?? 0) == 1 ? 'incident' : 'incidents' }}</span>
                            </div>
                        @empty
                            <div class="trend-item">
                                <span>No monthly trend data available</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- LOG STOCK OUT MODAL -->
<div class="modal-overlay" id="stockOutModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Log Stock-Out Incident</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form action="#" method="POST">
            @csrf
            <div class="form-group">
                <label>Medicine Name</label>
                <select class="form-control" name="medicine_id" required>
                    <option value="">Select Medicine</option>
                    @foreach($medicinesList ?? [] as $medicine)
                        <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Quantity Short / Needed</label>
                <input type="number" class="form-control" name="quantity_needed" min="1" required>
            </div>
            <div class="form-group">
                <label>Patients Affected</label>
                <input type="number" class="form-control" name="patients_affected" min="0" required>
            </div>
            <div class="form-group">
                <label>Estimated Stock-Out Duration (Days)</label>
                <input type="number" class="form-control" name="duration" min="1" required>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px;">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-primary">Save Record</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // JS Table Filter
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#stockoutTableBody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Modal Control Functions
    function openModal() { document.getElementById('stockOutModal').style.display = 'flex'; }
    function closeModal() { document.getElementById('stockOutModal').style.display = 'none'; }

    // Chart.js Monthly Shortage Trend Integration
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    
    // Extract Trend Data safely from Blade
    const rawTrend = @json($monthlyTrend ?? []);
    const labels = rawTrend.length ? rawTrend.map(i => i.month) : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const dataValues = rawTrend.length ? rawTrend.map(i => i.count) : [0, 0, 0, 0, 0, 0];

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Shortage Incidents',
                data: dataValues,
                borderColor: '#DC2626',
                backgroundColor: 'rgba(220, 38, 38, 0.08)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#DC2626'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

</body>
</html>
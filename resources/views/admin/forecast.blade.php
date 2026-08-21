<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Forecast - Velasquez Health Center</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            color: #1F2937;
            overflow-x: hidden;
        }

        .container { display: flex; }

        /* ================= MAIN CONTENT ================= */
        .main {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 24px;
            box-sizing: border-box;
        }

        /* ✅ YOUR ORIGINAL HEADER STYLES RETAINED */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .welcome-text { font-size: 13px; color: #6B7280; margin-bottom: 4px; }
        .welcome-name { font-weight: 700; color: #111827; font-size: 18px; display: flex; align-items: center; }
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
        .right { text-align: right; font-size: 12px; color: #4B5563; }
        .header-divider { width: 100%; height: 1px; background: #E5E7EB; margin: 16px 0 24px; }

        /* ✅ MINIMAL MAIN CONTENT STYLES */
        .forecast-container { display: flex; flex-direction: column; gap: 20px; }

        /* PAGE HEADER — Minimal */
        /* REFINED PAGE INTRO */
.page-intro {
    padding: 14px 20px;
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-intro .intro-text h2 {
    margin: 0 0 4px;
    font-size: 25px;
    font-weight: 600;
    color: #111827;
}

.page-intro .intro-text p {
    margin: 0;
    font-size: 13px;
    color: #6B7280;
    line-height: 1.4;
}

.page-intro .intro-meta {
    display: flex;
    gap: 10px;
}

.page-intro .meta-pill {
    padding: 4px 10px;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 20px;
    font-size: 12px;
    color: #4B5563;
    font-weight: 500;
}

@media (max-width: 768px) {
    .page-intro {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

        /* KPI CARDS — Minimal (no gradients, clean borders) */
        .forecast-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .summary-box {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 16px 20px;
        }
        .summary-box h4 {
            margin: 0 0 6px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6B7280;
        }
        .summary-box span { font-size: 26px; font-weight: 700; }

        .box-shortage { border-left: 3px solid #EF4444; }
        .box-shortage span { color: #DC2626; }

        .box-demand { border-left: 3px solid #F59E0B; }
        .box-demand span { color: #D97706; }

        .box-restock { border-left: 3px solid #2563EB; }
        .box-restock span { color: #2563EB; }

        /* CHART — Clean & Minimal */
        .chart-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 20px;
        }
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .chart-header h3 { margin: 0; font-size: 15px; font-weight: 600; }
        .chart-header p { margin: 2px 0 0; font-size: 12px; color: #6B7280; }
        .chart-select {
            padding: 6px 10px;
            font-size: 13px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
        }
        canvas { max-height: 280px; }

        /* TABLE — Clean & Minimal */
        .table-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            overflow: hidden;
        }
        .toolbar-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
        }
        .toolbar-title { font-size: 14px; font-weight: 600; }
        .search-input {
            width: 240px;
            padding: 7px 10px;
            font-size: 13px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            outline: none;
        }
        .search-input:focus { border-color: #2563EB; }

        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            background: #F9FAFB;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6B7280;
            border-bottom: 1px solid #E5E7EB;
        }
        tbody td { padding: 12px 16px; border-bottom: 1px solid #F3F4F6; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #FAFAFA; }

        .btn-reorder {
            padding: 5px 12px;
            background: #2563EB;
            color: #FFFFFF;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }
        .btn-reorder:hover { background: #1D4ED8; }
        .text-muted { color: #9CA3AF; font-size: 12px; }

        .forecast-note {
            padding: 10px 4px 0;
            font-size: 12px;
            color: #6B7280;
            display: flex;
            justify-content: space-between;
        }

        /* ALERTS */
        .alert-success { padding: 10px 14px; background: #ECFDF5; color: #065F46; border-radius: 8px; margin-bottom: 16px; font-size: 13px; }
        .alert-error { padding: 10px 14px; background: #FEF2F2; color: #991B1B; border-radius: 8px; margin-bottom: 16px; font-size: 13px; }

        @media (max-width: 992px) {
            .forecast-summary { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
<div class="container">

    {{-- ✅ YOUR SIDEBAR — FULLY RETAINED --}}
    <x-sidebar />

    <div class="main">

        {{-- ✅ YOUR TOP HEADER — FULLY RETAINED, NO CHANGES --}}
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ $userName ?? 'User' }}
                    <span class="role">
                        {{ strtoupper($role ?? 'ADMIN') }}
                    </span>
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

        {{-- ALERTS --}}
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif

        <div class="forecast-container">

            {{-- ✅ REFINED CONTENT STARTS HERE --}}

            <!-- PAGE INTRO -->
            <!-- REFINED PAGE INTRO -->
<div class="page-intro">
    <div class="intro-text">
        <h2>AI-Assisted Medicine Forecasting</h2>
        <p>Calculates 30-day projected demand based on 3-month rolling averages.</p>
    </div>
    <div class="intro-meta">
        <span class="meta-pill"><strong>Model:</strong> Moving Average</span>
        <span class="meta-pill"><strong>Window:</strong> 30 Days</span>
    </div>
</div>

            <!-- KPI CARDS -->
            <div class="forecast-summary">
                <div class="summary-box box-shortage">
                    <h4>Predicted Stock-Outs</h4>
                    <span>{{ $shortageCount ?? 0 }} {{ ($shortageCount ?? 0) == 1 ? 'Medicine' : 'Medicines' }}</span>
                </div>
                <div class="summary-box box-demand">
                    <h4>Threshold Alerts</h4>
                    <span>{{ $highDemandCount ?? 0 }} {{ ($highDemandCount ?? 0) == 1 ? 'Medicine' : 'Medicines' }}</span>
                </div>
                <div class="summary-box box-restock">
                    <h4>Restock Recommendations</h4>
                    <span>
                        @if($canManageForecast ?? true)
                            {{ $restockCount ?? 0 }} {{ ($restockCount ?? 0) == 1 ? 'Medicine' : 'Medicines' }}
                        @else
                            <span class="text-muted">View Only</span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- CHART -->
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3>Medicine Demand Forecast Trend</h3>
                        <p>Historical consumption vs. projected next month demand.</p>
                    </div>
                    <select class="chart-select" id="medicineFilter">
                        <option value="all">All Medicines</option>
                        @foreach($forecastData ?? [] as $data)
                            <option value="{{ $data['medicine']->id }}">{{ $data['medicine']->name }}</option>
                        @endforeach
                    </select>
                </div>
                <canvas id="forecastChart"></canvas>
            </div>

            <!-- TABLE -->
            <div class="table-card">
                <div class="toolbar-section">
                    <div class="toolbar-title">Predictive Forecast Metrics</div>
                    <input type="text" id="tableSearch" class="search-input" placeholder="Search medicine items...">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Medicine Description</th>
                            <th>Current Stock</th>
                            <th>Est. Demand (30-Day Avg)</th>
                            <th>Forecast Status</th>
                            <th>Recommendation Plan</th>
                            @if($canManageForecast ?? true)<th>Action</th>@endif
                        </tr>
                    </thead>
                    <tbody id="forecastTableBody">
                        @foreach($forecastData ?? [] as $data)
                        <tr>
                            <td><strong>{{ $data['medicine']->name }}</strong> {{ $data['medicine']->dosage_strength ?? '' }}</td>
                            <td>{{ number_format($data['current_stock']) }}</td>
                            <td><strong style="color:#2563EB;">{{ number_format($data['est_demand']) }}</strong></td>
                            <td>{!! $data['status_badge'] ?? $data['status'] !!}</td>
                            <td>
                                @if($canManageForecast ?? true)
                                    {{ $data['recommendation'] }}
                                @else
                                    @php echo preg_replace('/\| Suggested: \d+/', '', $data['recommendation']); @endphp
                                @endif
                            </td>
                            @if($canManageForecast ?? true)
                            <td>
                                @if($data['current_stock'] <= $data['est_demand'])
                                    <button class="btn-reorder">Restock</button>
                                @else
                                    <span class="text-muted">Sufficient</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="forecast-note">
                <span>Horizon Window Projection: <strong>Next 30 Days</strong></span>
                <small style="font-style: italic;">Insights are programmatically compiled utilizing rolling distribution volume calculations.</small>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rawForecastChart = @json($forecastChart ?? []);

    const monthNames = [
        "{{ \Carbon\Carbon::now()->subMonths(2)->format('F') }}",
        "{{ \Carbon\Carbon::now()->subMonth()->format('F') }}",
        "{{ \Carbon\Carbon::now()->format('F') }}",
        "Forecast ({{ \Carbon\Carbon::now()->addMonth()->format('F') }})"
    ];

    function getOverallData() {
        let totals = [0,0,0,0];
        rawForecastChart.forEach(item => {
            totals[0] += item.historical[0]||0;
            totals[1] += item.historical[1]||0;
            totals[2] += item.historical[2]||0;
            totals[3] += item.forecast||0;
        });
        return totals;
    }

    const ctx = document.getElementById('forecastChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Quantity Dispensed',
                data: getOverallData(),
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37,99,235,0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.2,
                pointRadius: [4,4,4,6],
                pointBackgroundColor: ['#2563EB','#2563EB','#2563EB','#EF4444'],
                segment: { borderDash: ctx => ctx.p0DataIndex === 2 ? [6,6] : undefined }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, title: { display:true, text:'Quantity Dispensed' } },
                x: { title: { display:true, text:'Forecast Period' } }
            }
        }
    });

    document.getElementById('medicineFilter').addEventListener('change', function(){
        const id = this.value;
        if(id === 'all'){
            myChart.data.datasets[0].data = getOverallData();
        } else {
            const item = rawForecastChart.find(i => i.medicine_id == id);
            if(item) myChart.data.datasets[0].data = [...item.historical, item.forecast];
        }
        myChart.update();
    });

    document.getElementById('tableSearch').addEventListener('keyup', function(){
        const q = this.value.toLowerCase();
        document.querySelectorAll('#forecastTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>

</body>
</html>
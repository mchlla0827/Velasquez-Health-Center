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

        /* HEADER SECTION */
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

        .forecast-container { display: flex; flex-direction: column; gap: 20px; }

        /* ================= COMPACT HERO CARD ================= */
        .hero-banner-card {
            position: relative;
            width: 100%;
            box-sizing: border-box;
            padding: 20px 24px;
            background: linear-gradient(135deg, #FAF5FF 0%, #FFFFFF 100%);
            border: 1px solid #E9D5FF;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hero-title h2 { margin: 0; font-size: 22px; font-weight: 700; color: #1E293B; }
        .hero-title p { margin: 4px 0 0; font-size: 13px; color: #64748B; }

        .banner-meta { display: flex; gap: 10px; }
        .meta-pill {
            padding: 5px 12px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 9999px;
            font-size: 12px;
            color: #475569;
            font-weight: 500;
        }

        /* ================= SUMMARY KPI CARDS ================= */
        .forecast-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            width: 100%;
        }

        .summary-box {
            position: relative;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            transition: transform .2s ease;
        }

        .summary-box:hover { transform: translateY(-2px); }
        .summary-box::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 4px; border-radius: 14px 14px 0 0; }

        .summary-box h4 { margin: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
        .summary-box span { font-size: 28px; font-weight: 700; line-height: 1; }

        .box-shortage::before { background: #EF4444; }
        .box-shortage { background: #FEF2F2; border-color: #FCA5A5; }
        .box-shortage h4 { color: #DC2626; }
        .box-shortage span { color: #991B1B; }

        .box-demand::before { background: #F59E0B; }
        .box-demand { background: #FFFBEB; border-color: #FDE047; }
        .box-demand h4 { color: #D97706; }
        .box-demand span { color: #92400E; }

        .box-restock::before { background: #2563EB; }
        .box-restock { background: #EFF6FF; border-color: #BFDBFE; }
        .box-restock h4 { color: #2563EB; }
        .box-restock span { color: #1E40AF; }

        /* ================= CHART CONTAINER ================= */
        .chart-card {
            background: #FFFFFF;
            border-radius: 14px;
            border: 1px solid #E5E7EB;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .chart-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #111827; }
        .chart-header p { margin: 2px 0 0; font-size: 12px; color: #6B7280; }

        .chart-select {
            padding: 6px 12px;
            font-size: 13px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            outline: none;
            color: #374151;
            background: #F9FAFB;
        }

        /* ================= TABLE CARD ================= */
        .table-card {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            overflow: hidden;
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

        /* ACTION & STATUS BADGES */
        .btn-reorder {
            padding: 5px 12px;
            background: #2563EB;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-reorder:hover { background: #1D4ED8; }

        .forecast-note {
            padding: 12px 4px 0;
            font-size: 12px;
            color: #6B7280;
            display: flex;
            justify-content: space-between;
        }

        @media (max-width: 992px) {
            .forecast-summary { grid-template-columns: 1fr; }
            .hero-banner-card { flex-direction: column; align-items: flex-start; gap: 12px; }
        }
    </style>
</head>

<body>
<div class="container">

    <x-sidebar />

    <div class="main">
        <!-- TOP WELCOME BAR -->
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ $userName ?? 'User' }}
                    <span class="role" style="background-color: {{ $roleColor ?? '#9333EA' }};">
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

        <div class="forecast-container">

            <!-- STREAMLINED HERO BANNER -->
            <div class="hero-banner-card">
                <div class="hero-title">
                    <h2>AI-Assisted Medicine Forecasting</h2>
                    <p>Calculates 30-day projected demand based on 3-month rolling averages.</p>
                </div>
                <div class="banner-meta">
                    <span class="meta-pill"><strong>Model:</strong> Moving Average</span>
                    <span class="meta-pill"><strong>Window:</strong> 30 Days</span>
                </div>
            </div>

            <!-- TOP KPI METRIC CARDS -->
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
                            View Only
                        @endif
                    </span>
                </div>
            </div>

            <!-- DYNAMIC CHART WITH DROPDOWN SELECTOR -->
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3>Medicine Demand Forecast Trend</h3>
                        <p>Historical consumption vs. projected next month demand.</p>
                    </div>
                    <div>
                        <select class="chart-select" id="medicineFilter">
                            <option value="all">Overall Demand Trend</option>
                            @foreach($forecastData ?? [] as $data)
                                <option value="{{ $data['medicine']->id }}">{{ $data['medicine']->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <canvas id="forecastChart" style="max-height: 300px;"></canvas>
            </div>

            <!-- PREDICTIVE METRICS TABLE -->
            <div class="table-card">
                <div class="toolbar-section">
                    <div class="toolbar-title">Predictive Forecast Metrics</div>
                    <input type="text" id="tableSearch" class="search-input" placeholder="Search medicine items...">
                </div>

                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Medicine Description</th>
                                <th>Current Stock</th>
                                <th>Est. Demand (30-Day Avg)</th>
                                <th>Forecast Status</th>
                                <th>Recommendation Plan</th>
                                @if($canManageForecast ?? true)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="forecastTableBody">
                            @foreach($forecastData ?? [] as $data)
                            <tr>
                                <td class="medicine-name font-semibold">
                                    {{ $data['medicine']->name }} ({{ $data['medicine']->dosage_strength ?? '' }})
                                </td>
                                <td>{{ number_format($data['current_stock']) }}</td>
                                <td class="font-bold text-blue-700">{{ number_format($data['est_demand']) }}</td>
                                <td>
                                    <span class="{{ $data['status_class'] }}">
                                        {{ $data['status'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($canManageForecast ?? true)
                                        {{ $data['recommendation'] }}
                                    @else
                                        @php
                                            $cleanText = preg_replace('/\| Suggested: \d+/', '', $data['recommendation']);
                                        @endphp
                                        {{ $cleanText }}
                                    @endif
                                </td>
                                @if($canManageForecast ?? true)
                                <td>
                                    @if($data['current_stock'] <= $data['est_demand'])
                                        <button class="btn-reorder">Reorder</button>
                                    @else
                                        <span style="color:#9CA3AF; font-size:12px;">Sufficient</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
    // 1. Pass PHP forecastChart array safely into JavaScript
    const rawForecastChart = @json($forecastChart ?? []);

    // Dynamic month labels based on current date
    const monthNames = [
        "{{ \Carbon\Carbon::now()->subMonths(2)->format('F') }}",
        "{{ \Carbon\Carbon::now()->subMonth()->format('F') }}",
        "{{ \Carbon\Carbon::now()->format('F') }}",
        "Forecast ({{ \Carbon\Carbon::now()->addMonth()->format('F') }})"
    ];

    const ctx = document.getElementById('forecastChart').getContext('2d');

    // 2. Compute "Overall" baseline (sums up historical & forecast for all medicines)
    function getOverallData() {
        let totals = [0, 0, 0, 0];
        rawForecastChart.forEach(item => {
            totals[0] += item.historical[0] || 0;
            totals[1] += item.historical[1] || 0;
            totals[2] += item.historical[2] || 0;
            totals[3] += item.forecast || 0;
        });
        return totals;
    }

    // 3. Initialize Chart.js
    let myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Quantity Dispensed',
                data: getOverallData(),
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.2,
                pointRadius: [4, 4, 4, 6],
                pointBackgroundColor: ['#2563EB', '#2563EB', '#2563EB', '#EF4444'],
                segment: {
                    borderDash: ctx => ctx.p0DataIndex === 2 ? [6, 6] : undefined
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Quantity Dispensed' }
                },
                x: {
                    title: { display: true, text: 'Forecast Period' }
                }
            }
        }
    });

    // 4. Connect Dropdown Filter to Chart Updates
    document.getElementById('medicineFilter').addEventListener('change', function() {
        const selectedId = this.value;

        if (selectedId === 'all') {
            myChart.data.datasets[0].data = getOverallData();
        } else {
            // Find selected medicine in array
            const matchedItem = rawForecastChart.find(item => item.medicine_id == selectedId);
            
            if (matchedItem) {
                myChart.data.datasets[0].data = [
                    matchedItem.historical[0],
                    matchedItem.historical[1],
                    matchedItem.historical[2],
                    matchedItem.forecast
                ];
            }
        }
        
        myChart.update(); // Redraw chart smoothly
    });

    // 5. Table Search Filter
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#forecastTableBody tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

</body>
</html>
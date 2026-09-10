<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Forecast - Velasquez Health Center</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            color: #1F2937;
        }
        .container { display: flex; }
        .main { margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; }
        .welcome-text { font-size: 14px; color: #374151; margin-bottom: 5px; }
        .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }
        .role { color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-left: 6px; text-transform: uppercase; }
        .right { text-align: right; font-size: 12px; color: #374151; }
        .header-divider { width: 100%; height: 1px; background: #E5E7EB; margin: 16px 0; }

        .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .page-title { font-size: 24px; font-weight: 700; color: #1e293b; letter-spacing: -0.02em; margin: 0; }

        .method-strip {
            display: flex; gap: 22px; flex-wrap: wrap;
            background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px;
            padding: 10px 16px; margin-bottom: 20px; font-size: 12.5px; color: #1E3A8A;
        }
        .method-strip b { color: #1E40AF; }

        .ai-banner {
            display: flex; align-items: flex-start; gap: 12px;
            border-radius: 8px; padding: 14px 16px; margin-bottom: 24px;
            border: 1px solid; font-size: 13px;
        }
        .ai-banner.warning { background: #FFFBEB; border-color: #FDE68A; color: #92400E; }
        .ai-banner.success { background: #ECFDF5; border-color: #A7F3D0; color: #065F46; }
        .ai-banner .dot { width: 9px; height: 9px; border-radius: 50%; margin-top: 4px; flex-shrink: 0; }
        .ai-banner.warning .dot { background: #F59E0B; }
        .ai-banner.success .dot { background: #10B981; }
        .ai-banner b { display: block; font-size: 14px; margin-bottom: 3px; }

        /* Summary cards - same card language as filter-card/table-card */
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .summary-card { background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 16px 18px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
        .summary-card .label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #4B5563; }
        .summary-card .value { font-size: 24px; font-weight: 700; margin-top: 6px; color: #111827; }
        .summary-card.shortage .value { color: #DC2626; }
        .summary-card.low .value { color: #D97706; }
        .summary-card.expiring .value { color: #7C3AED; }

        /* Filter card - matches Dispense Medicine exactly */
        .filter-card {
            background: white; border: 1px solid #E5E7EB; border-radius: 12px;
            padding: 20px; margin-bottom: 24px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .filter-grid { display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap; }
        .filter-grid > div { flex: 1 1 200px; }
        label { font-size: 12px; font-weight: 600; color: #374151; display: block; margin-bottom: 6px; }
        input, select {
            width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px;
            font-size: 13px; background: #fff; color: #0F172A; outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        input:focus, select:focus { border-color: #1A73E8; box-shadow: 0 0 0 3px rgba(26,115,232,0.15); }
        .filter-actions { flex: 0 0 auto; }
        .clear-filters-btn {
            min-height: 42px; padding: 10px 16px; border: 1px solid #93C5FD; border-radius: 8px;
            background: #EFF6FF; color: #1D4ED8; font-size: 14px; font-weight: 600; cursor: pointer;
        }
        .clear-filters-btn:hover { background: #DBEAFE; border-color: #60A5FA; color: #1E40AF; }

        .btn-primary {
            background-color: #1A73E8; color: white; font-size: 14px; font-weight: 600;
            padding: 10px 18px; border: none; border-radius: 8px; cursor: pointer;
        }
        .btn-primary:hover { background-color: #1557B0; }

        /* Table card - matches Dispense Medicine exactly */
        .table-card { background: white; border: 1px solid #E5E7EB; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.03); margin-bottom: 24px; }
        .table-header-title { padding: 16px 20px; font-size: 16px; font-weight: 700; color: #111827; border-bottom: 1px solid #E5E7EB; background: #FAFAFA; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        th, td { padding: 14px 20px; border-bottom: 1px solid #E5E7EB; color: #374151; vertical-align: middle; }
        th { background: #F9FAFB; font-weight: 600; color: #4B5563; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; }
        tbody tr:hover { background-color: #F9FAFB; cursor: pointer; }

        .status-chip { display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 999px; }
        .status-chip.stable { background: #DCFCE7; color: #166534; }
        .status-chip.low { background: #FEF3C7; color: #92400E; }
        .status-chip.shortage { background: #FEE2E2; color: #991B1B; }
        .status-chip.critical { background: #FECACA; color: #7F1D1D; }
        .status-chip.insufficient { background: #F3F4F6; color: #6B7280; }

        /* Detail panel */
        .detail-panel { display: none; background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px; margin-bottom: 24px; box-shadow: 0 1px 2px rgba(0,0,0,0.03); }
        .detail-panel.open { display: block; }
        .detail-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
        .detail-head h3 { margin: 0; font-size: 17px; font-weight: 700; color: #111827; }
        .detail-close { background: none; border: none; font-size: 20px; cursor: pointer; color: #9CA3AF; }
        .metric-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px; }
        .metric-box { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px 14px; }
        .metric-box .k { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6B7280; }
        .metric-box .v { font-size: 18px; font-weight: 700; margin-top: 3px; color: #111827; }
        .metric-box.warn .v { color: #DC2626; }

        .calc-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 12px 16px; font-size: 12.5px; color: #1E3A8A; margin-bottom: 16px; line-height: 1.6; }
        .calc-box code { background: #fff; padding: 1px 6px; border-radius: 4px; font-weight: 700; }

        .detail-cols { display: grid; grid-template-columns: 1.1fr 1fr; gap: 20px; }
        .hist-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 12px; }
        .hist-table th, .hist-table td { padding: 8px 10px; font-size: 12.5px; }
        .hist-table tr.forecast-row td { font-weight: 700; color: #2563EB; background: #EFF6FF; }

        .context-box { border: 1px solid #E5E7EB; border-radius: 8px; padding: 10px 14px; font-size: 12px; color: #4B5563; margin-bottom: 10px; }
        .context-box b { color: #111827; }
        .transparency-footer { margin-top: 14px; padding-top: 14px; border-top: 1px dashed #E5E7EB; display: flex; gap: 24px; flex-wrap: wrap; font-size: 11.5px; color: #6B7280; }
        .transparency-footer b { color: #374151; }

        @media (max-width: 1000px) {
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
            .metric-grid { grid-template-columns: repeat(2, 1fr); }
            .detail-cols { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ========== AI INSIGHTS SECTION (replaces the old Recommendation text) ========== -->
<div id="aiInsightsSection" style="max-width: 1100px; margin: 24px auto; padding: 0 24px;">
    <style>
        .ai-insights-card { background: #fff; border: 1px solid #E5E7EB; border-radius: 10px; overflow: hidden; }
        .ai-insights-header { padding: 18px 20px 14px; border-bottom: 1px solid #F1F5F9; }
        .ai-insights-title { font-size: 17px; font-weight: 700; color: #111827; margin: 0; }
        .ai-insights-subtitle { font-size: 12px; color: #6B7280; margin-top: 3px; }
        .ai-filter-tabs { display: flex; gap: 8px; padding: 14px 20px; flex-wrap: wrap; }
        .ai-filter-tab { padding: 7px 14px; border-radius: 999px; border: 1px solid #E5E7EB; background: #fff; color: #4B5563; font-size: 12.5px; font-weight: 600; cursor: pointer; }
        .ai-filter-tab.active { background: #111827; color: #fff; border-color: #111827; }
        .ai-insight-item { border-left: 4px solid #E5E7EB; padding: 14px 20px; border-bottom: 1px solid #F8FAFC; }
        .ai-insight-item.sev-critical { border-left-color: #DC2626; background: #FEF2F2; }
        .ai-insight-item.sev-warning { border-left-color: #D97706; background: #FFFBEB; }
        .ai-insight-item.sev-info { border-left-color: #2563EB; background: #F0F9FF; }
        .ai-insight-item.sev-positive { border-left-color: #16A34A; background: #F0FDF4; }
        .ai-sev-badge { display: inline-block; font-size: 10.5px; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; padding: 3px 9px; border-radius: 4px; margin-bottom: 6px; }
        .ai-sev-badge.sev-critical { background: #FEE2E2; color: #991B1B; }
        .ai-sev-badge.sev-warning { background: #FEF3C7; color: #92400E; }
        .ai-sev-badge.sev-info { background: #DBEAFE; color: #1E40AF; }
        .ai-sev-badge.sev-positive { background: #DCFCE7; color: #166534; }
        .ai-insight-title-text { font-size: 14px; font-weight: 700; color: #111827; margin: 0 0 4px; }
        .ai-insight-detail { font-size: 12.5px; color: #4B5563; line-height: 1.55; margin: 0; }
        .ai-insights-empty { padding: 40px 20px; text-align: center; color: #9CA3AF; font-size: 13px; }
        .ai-insights-footnote { padding: 12px 20px; font-size: 11px; color: #9CA3AF; border-top: 1px solid #F1F5F9; }
    </style>

    <div class="ai-insights-card">
        <div class="ai-insights-header">
            <h2 class="ai-insights-title">AI Insights</h2>
            <div class="ai-insights-subtitle">Automatically generated observations from the existing forecast and inventory data. Decision-support only - final ordering decisions remain with authorized staff.</div>
        </div>
        <div class="ai-filter-tabs" id="aiFilterTabs">
            <button type="button" class="ai-filter-tab active" data-filter="all">All</button>
            <button type="button" class="ai-filter-tab" data-filter="critical">Critical</button>
            <button type="button" class="ai-filter-tab" data-filter="warning">Warning</button>
            <button type="button" class="ai-filter-tab" data-filter="info">Info</button>
            <button type="button" class="ai-filter-tab" data-filter="positive">Positive</button>
        </div>
        <div id="aiInsightsList"></div>
        <div class="ai-insights-footnote">Insights are generated from the same 3-month moving-average forecast used elsewhere in the system. Verify before ordering.</div>
    </div>
</div>

<script>
    (function () {
        let allInsights = [];

        function renderInsights(filter) {
            const list = document.getElementById('aiInsightsList');
            const filtered = filter === 'all' ? allInsights : allInsights.filter(i => i.severity === filter);

            if (filtered.length === 0) {
                list.innerHTML = '<div class="ai-insights-empty">No insights in this category right now.</div>';
                return;
            }

            list.innerHTML = filtered.map(i => `
                <div class="ai-insight-item sev-${i.severity}">
                    <span class="ai-sev-badge sev-${i.severity}">${i.severity}</span>
                    <p class="ai-insight-title-text">${i.title}</p>
                    <p class="ai-insight-detail">${i.detail}</p>
                </div>
            `).join('');
        }

        document.getElementById('aiFilterTabs').addEventListener('click', function (e) {
            if (!e.target.classList.contains('ai-filter-tab')) return;
            document.querySelectorAll('.ai-filter-tab').forEach(t => t.classList.remove('active'));
            e.target.classList.add('active');
            renderInsights(e.target.dataset.filter);
        });

        fetch('/forecast/insights')
            .then(res => res.json())
            .then(data => {
                allInsights = data.insights || [];
                renderInsights('all');
            })
            .catch(() => {
                document.getElementById('aiInsightsList').innerHTML = '<div class="ai-insights-empty">Unable to load insights right now.</div>';
            });
    })();
</script>
<div class="container">
    <x-sidebar />

    <div class="main">
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ $userName }}
                    <span class="role" style="background-color: {{ $roleColor }};">{{ strtoupper($role) }}</span>
                </div>
            </div>
            <div class="right">
                <b>Velasquez Health Center</b><br>
                @php date_default_timezone_set('Asia/Manila'); @endphp
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="header-divider"></div>

        <div class="page-title-row">
            <h2 class="page-title">AI-Assisted Medicine Inventory Forecasting</h2>
        </div>

        <div class="method-strip">
            <span><b>Forecasting Method:</b> 3-Month Moving Average</span>
            <span><b>Data Basis:</b> Historical Medicine Dispensing Records</span>
            <span><b>Forecast Horizon:</b> Next 30 Days</span>
        </div>

        <div class="ai-banner {{ $aiStatus }}">
            <div class="dot"></div>
            <div>
                <b>{{ $aiTitle }}</b>
                {{ $aiMessage }} {{ $aiAction }}
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="label">Medicines Analyzed</div>
                <div class="value">{{ count($forecastData) }}</div>
            </div>
            <div class="summary-card shortage">
                <div class="label">Possible Shortage</div>
                <div class="value">{{ $shortageCount }}</div>
            </div>
            <div class="summary-card low">
                <div class="label">Low Stock / Critical</div>
                <div class="value">{{ $lowStockCount }}</div>
            </div>
            <div class="summary-card expiring">
                <div class="label">Expiring Soon (180d)</div>
                <div class="value">{{ $expiringSoonCount }}</div>
            </div>
        </div>

        <div class="filter-card">
            <div class="filter-grid">
                <div>
                    <label>Search</label>
                    <input type="text" id="searchBox" placeholder="Search medicine...">
                </div>
                <div>
                    <label>Status Filter</label>
                    <select id="statusFilter">
                        <option value="all">All</option>
                        <option value="stable">Stable</option>
                        <option value="low">Low Stock</option>
                        <option value="shortage">Possible Shortage</option>
                        <option value="critical">Critical</option>
                        <option value="insufficient">Insufficient Data</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="button" class="clear-filters-btn" id="clearFiltersBtn">Clear Filters</button>
                </div>
            </div>
        </div>
        <div class="table-card">
            <div class="table-header-title">Predictive Forecast Metrics</div>
            <table>
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Usable Stock</th>
                        <th>3-Month Avg</th>
                        <th>Forecast (30d)</th>
                        <th>Shortage</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="forecastTableBody">
                    @foreach ($forecastData as $i => $data)
                        @php
                            $statusKey = match(true) {
                                $data['status'] === 'Critical' => 'critical',
                                $data['status'] === 'Possible Shortage' => 'shortage',
                                $data['status'] === 'Low Stock Alert' => 'low',
                                $data['status'] === 'Insufficient Data' => 'insufficient',
                                default => 'stable',
                            };
                            $threeMonthAvg = $data['months_available'] > 0
                                ? round(($data['period1'] + $data['period2'] + $data['period3']) / max(1, $data['months_available']))
                                : 0;
                            $shortageAmt = $data['forecast_available'] ? max(0, $data['estimated_demand'] - $data['usable_stock']) : 0;
                        @endphp
                        <tr class="med-row" data-index="{{ $i }}" data-status="{{ $statusKey }}" data-name="{{ strtolower($data['medicine']->name) }}">
                            <td><strong>{{ $data['medicine']->name }}</strong> <span style="color:#9CA3AF;">{{ $data['medicine']->dosage_strength ?? '' }}</span></td>
                            <td>{{ number_format($data['usable_stock']) }}</td>
                            <td>{{ $data['forecast_available'] ? number_format($threeMonthAvg) : '-' }}</td>
                            <td>{{ $data['forecast_available'] ? number_format($data['estimated_demand']) : 'N/A' }}</td>
                            <td>{{ $data['forecast_available'] && $shortageAmt > 0 ? number_format($shortageAmt) : '-' }}</td>
                            <td><span class="status-chip {{ $statusKey }}">{{ $data['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="detail-panel" id="detailPanel">
            <div class="detail-head">
                <div>
                    <h3 id="dp-name">-</h3>
                    <span class="status-chip" id="dp-status-chip">-</span>
                </div>
                <button class="detail-close" onclick="closeDetail()">&times;</button>
            </div>

            <div class="metric-grid">
                <div class="metric-box"><div class="k">Usable Stock</div><div class="v" id="dp-usable">-</div></div>
                <div class="metric-box warn"><div class="k">Expired Stock</div><div class="v" id="dp-expired">-</div></div>
                <div class="metric-box"><div class="k">Low-Stock Threshold</div><div class="v" id="dp-threshold">-</div></div>
                <div class="metric-box"><div class="k">Expiring Soon (180d)</div><div class="v" id="dp-expiring">-</div></div>
            </div>

            <div class="calc-box" id="dp-calc"></div>

            <div class="detail-cols">
                <div>
                    <table class="hist-table">
                        <thead><tr><th>Period</th><th>Consumption</th></tr></thead>
                        <tbody id="dp-hist-body"></tbody>
                    </table>
                    <canvas id="dp-chart" height="160"></canvas>
                </div>
                <div>
                    <div class="context-box" id="dp-stockout-box"></div>
                    <div class="context-box" id="dp-request-box"></div>
                    <div class="metric-grid" style="grid-template-columns: repeat(2,1fr); margin-top: 14px;">
                        <div class="metric-box"><div class="k">Projected Shortage</div><div class="v" id="dp-shortage">-</div></div>
                        <div class="metric-box"><div class="k">Suggested Restock</div><div class="v" id="dp-restock">-</div></div>
                    </div>
                    <div class="context-box" id="dp-recommendation" style="margin-top: 10px;"></div>
                </div>
            </div>

            <div class="transparency-footer">
                <span><b>Method:</b> 3-Month Moving Average</span>
                <span><b>Data Basis:</b> Historical Dispensing Records</span>
                <span><b>Horizon:</b> Next 30 Days</span>
                <span><b>Not an automatic purchase order</b> - staff review required</span>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const forecastData = @json($forecastData);
    let detailChart = null;

    // ---------- Filtering / search ----------
    const rows = document.querySelectorAll('#forecastTableBody tr.med-row');
    const searchBox = document.getElementById('searchBox');
    const statusFilter = document.getElementById('statusFilter');
    const clearBtn = document.getElementById('clearFiltersBtn');

    function applyFilters() {
        const q = searchBox.value.toLowerCase().trim();
        const status = statusFilter.value;
        rows.forEach(row => {
            const matchesFilter = status === 'all' || row.dataset.status === status;
            const matchesSearch = !q || row.dataset.name.includes(q);
            row.style.display = (matchesFilter && matchesSearch) ? '' : 'none';
        });
    }

    searchBox.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    clearBtn.addEventListener('click', () => {
        searchBox.value = '';
        statusFilter.value = 'all';
        applyFilters();
    });

    // ---------- Detail panel ----------
    function closeDetail() {
        document.getElementById('detailPanel').classList.remove('open');
    }

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const data = forecastData[row.dataset.index];
            openDetail(data);
        });
    });

    function statusClass(status) {
        if (status === 'Critical') return 'critical';
        if (status === 'Possible Shortage') return 'shortage';
        if (status === 'Low Stock Alert') return 'low';
        if (status === 'Insufficient Data') return 'insufficient';
        return 'stable';
    }

    function openDetail(data) {
        const med = data.medicine;
        document.getElementById('dp-name').textContent = med.name + (med.dosage_strength ? ' ' + med.dosage_strength : '');
        const chip = document.getElementById('dp-status-chip');
        chip.textContent = data.status;
        chip.className = 'status-chip ' + statusClass(data.status);

        document.getElementById('dp-usable').textContent = data.usable_stock;
        document.getElementById('dp-expired').textContent = data.expired_stock;
        document.getElementById('dp-threshold').textContent = data.threshold;
        document.getElementById('dp-expiring').textContent = data.expiring_soon_qty;

        const avg = data.months_available > 0
            ? Math.round((data.period1 + data.period2 + data.period3) / Math.max(1, data.months_available))
            : 0;

        if (!data.forecast_available) {
            document.getElementById('dp-calc').innerHTML =
                '<b>Insufficient historical data for a reliable 3-month forecast.</b><br>' +
                'This medicine has ' + data.months_available + ' month(s) of usable history. A forecast will become available once at least 3 full months of dispensing history exist.';
        } else {
            document.getElementById('dp-calc').innerHTML =
                'Moving Average = (' + data.period3 + ' + ' + data.period2 + ' + ' + data.period1 + ') / 3 = <code>' + avg + ' units</code><br>' +
                'Forecast Demand (next 30 days) = <code>' + data.estimated_demand + ' units</code>';
        }

        document.getElementById('dp-hist-body').innerHTML =
            '<tr><td>3 months ago</td><td>' + data.period3 + '</td></tr>' +
            '<tr><td>2 months ago</td><td>' + data.period2 + '</td></tr>' +
            '<tr><td>Last month</td><td>' + data.period1 + '</td></tr>' +
            (data.forecast_available ? '<tr class="forecast-row"><td>Forecast (next 30d)</td><td>' + data.estimated_demand + '</td></tr>' : '');

        const shortageAmt = data.forecast_available ? Math.max(0, data.estimated_demand - data.usable_stock) : 0;
        document.getElementById('dp-shortage').textContent = data.forecast_available ? shortageAmt : 'N/A';
        document.getElementById('dp-restock').textContent = data.suggested_reorder_qty || 0;
        document.getElementById('dp-recommendation').innerHTML = '<b>Recommendation:</b> ' + data.recommendation;

        const so = data.stock_out_context;
        document.getElementById('dp-stockout-box').innerHTML = so.incident_count > 0
            ? '<b>Stock-Out History (90d):</b> ' + so.incident_count + ' incident(s), ' + so.total_patients_affected + ' patient(s) affected. <i>(Context only - not counted as demand)</i>'
            : '<b>Stock-Out History (90d):</b> No incidents recorded.';

        const rq = data.request_context;
        document.getElementById('dp-request-box').innerHTML = rq.request_count > 0
            ? '<b>Medicine Requests (90d):</b> ' + rq.request_count + ' request(s) totaling ' + rq.total_requested + ' units requested. <i>(Requested is not the same as dispensed - context only)</i>'
            : '<b>Medicine Requests (90d):</b> No requests recorded.';

        if (detailChart) detailChart.destroy();
        const ctx = document.getElementById('dp-chart').getContext('2d');
        detailChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['3mo ago', '2mo ago', 'Last mo', data.forecast_available ? 'Forecast' : 'Forecast (N/A)'],
                datasets: [{
                    label: 'Units',
                    data: [data.period3, data.period2, data.period1, data.forecast_available ? data.estimated_demand : 0],
                    backgroundColor: ['#93C5FD', '#93C5FD', '#93C5FD', '#2563EB'],
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        document.getElementById('detailPanel').classList.add('open');
        document.getElementById('detailPanel').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>AI Forecast</title>

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

.nav-item:hover { 
    background: #F3F4F6; 
    }

/* New modifier class for form buttons inside the sidebar */
    .nav-btn {
        width: 100%;
        text-align: left;
        border: none;
        background: transparent; /* Changed from 'none' so hover states can override it */
        cursor: pointer;
        font-family: inherit; /* Ensures the font matches your links */
    }

    /* ================= MAIN ================= */
   .main {
        margin-left: 250px;
        width: calc(100% - 250px);
        padding: 24px;
        box-sizing: border-box;
    }

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
        margin-left: 6px;
    }
    .role { color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-left: 6px; text-transform: uppercase; }

    .right { text-align: right; font-size: 12px; color: #374151; }

    .header-divider {
        width: 100%;
        height: 1px;
        background: #E5E7EB;
        margin: 16px 0;
    }

    /* ================= FORECAST CONTAINER ================= */
.forecast-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* =========================================================
   AI Forecast Information Banner
========================================================= */

.info-banner-card {
    position: relative;
    width: 100%;
    box-sizing: border-box;

    margin: 12px 0 0px;
    padding: 32px 36px;

    background: linear-gradient(
        135deg,
        rgba(248, 245, 255, 0.95) 0%,
        rgba(255, 255, 255, 0.98) 100%
    );

    border: 1px solid #E9D5FF;
    border-radius: 20px;

    overflow: hidden;
}

/* Decorative glow */
.info-banner-card::after {
    content: "";
    position: absolute;
    top: -60px;
    right: -60px;

    width: 220px;
    height: 220px;

    background: radial-gradient(
        circle,
        rgba(168, 85, 247, 0.10) 0%,
        transparent 70%
    );

    pointer-events: none;
}

.info-banner-card h2 {
    margin: 0 0 12px;
    font-size: 30px;
    font-weight: 700;
    color: #1E293B;
    letter-spacing: -0.03em;
    line-height: 1.2;
}

.info-banner-card p {
    max-width: 760px;
    margin: 0;

    font-size: 15px;
    line-height: 1.8;

    color: #475569;
    font-style: normal;
}

/* =========================================================
   Footer Information
========================================================= */

.banner-footer {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    margin-top: 22px;
}

.banner-footer small {
    display: inline-flex;
    align-items: center;

    padding: 8px 14px;

    background: #F8FAFC;
    border: 1px solid #E5E7EB;
    border-radius: 999px;

    font-size: 12px;
    color: #64748B;
}

.banner-footer strong {
    color: #334155;
    margin-right: 4px;
}

/* =========================================================
   AI Status Badge
========================================================= */

.badge-status-ai {
    position: absolute;
    top: 28px;
    right: 32px;

    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 8px 16px;

    border-radius: 999px;

    background: #ECFDF5;
    border: 1px solid #A7F3D0;

    color: #065F46;
    font-size: 13px;
    font-weight: 600;
}

.badge-status-ai::before {
    content: "";
    width: 8px;
    height: 8px;

    border-radius: 50%;
    background: #10B981;

    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }

    50% {
        transform: scale(1.3);
        opacity: .6;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* =========================================================
   Responsive
========================================================= */

@media (max-width: 768px) {

    .info-banner-card {
        padding: 24px;
    }

    .info-banner-card h2 {
        font-size: 26px;
        padding-right: 120px;
    }

    .info-banner-card p {
        max-width: 100%;
    }

    .banner-footer {
        gap: 10px;
    }

    .badge-status-ai {
        position: static;
        margin-bottom: 16px;
        width: fit-content;
    }
}

    /* =========================================================
   Forecast Summary Cards
========================================================= */

.forecast-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
    width: 100%;
    margin-bottom: 10px;
}

.summary-box {
    position: relative;
    overflow: hidden;

    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 20px;

    padding: 24px 26px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 8px;
    transition: all .25s ease;
}

.summary-box:hover{
    transform: translateY(-4px);
}

/* Colored top accent */
.summary-box::before{
    content:"";
    position:absolute;
    top:0;
    left:0;

    width:100%;
    height:5px;
}

/* Heading */

.summary-box h4{
    margin:0;

    font-size:12px;
    font-weight:700;

    text-transform:uppercase;
    letter-spacing:.08em;

    color:#6B7280;
}

/* Value */

.summary-box span{
    font-size:30px;
    font-weight:700;
    color:#111827;
    line-height:1;
}

/* Small label under number */

.summary-box small{
    font-size:13px;
    color:#6B7280;
}

/* =========================================================
   Shortages
========================================================= */

.box-shortage::before{
    background:#EF4444;
}

.box-shortage{
    background:#FEF2F2;
}

.box-shortage h4{
    color:#DC2626;
}

/* =========================================================
   Threshold Alert
========================================================= */

.box-demand::before{
    background:#F59E0B;
}

.box-demand{
    background:#FFFBEB;
}

.box-demand h4{
    color:#D97706;
}

/* =========================================================
   AI Restock
========================================================= */

.box-restock::before{
    background:#2563EB;
}

.box-restock{
    background:#EFF6FF;
}

.box-restock h4{
    color:#2563EB;
}

/* =========================================================
   Responsive
========================================================= */

@media(max-width:992px){

    .forecast-summary{
        grid-template-columns:1fr;
    }

}

    /* TABLE CONTAINER CARD */
.table-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    margin-top: 10px;
    overflow: hidden;
}

/* NAVBAR TOOLBAR TOP SECTION */
.toolbar-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #F3F4F6;
    gap: 16px;
}

.toolbar-title {
    font-size: 16px;
    font-weight: 700;
    color: #1F2937;
    letter-spacing: -0.01em;
}

/* SEARCH INPUT BAR */
.search-input {
    width: 280px;
    padding: 8px 14px;
    font-size: 14px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    color: #1F2937;
    outline: none;
    transition: all 0.15s ease-in-out;
}

.search-input:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* TABLE BASE ARCHITECTURE */
.overflow-x-auto {
    overflow-x: auto;
    width: 100%;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    text-align: left;
}

/* TABLE HEADER ELEMENT ROW */
thead th {
    background: #F9FAFB;
    color: #4B5563;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 14px 24px;
    border-bottom: 1px solid #E5E7EB;
    text-transform: uppercase;
}

/* TABLE BODY FIELDS */
tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background 0.15s ease;
}

tbody tr:last-child {
    border-bottom: none;
}

tbody tr:hover {
    background-color: #F8FAFC;
}

tbody td {
    padding: 16px 24px;
    color: #374151;
    vertical-align: middle;
}

/* CUSTOM APP COMPONENTS */
.medicine-desc {
    font-weight: 600;
    color: #111827;
}

.demand-metric {
    font-weight: 700;
    color: #1D4ED8; /* Premium dark blue demand text */
}

/* STATUS PILL BADGES (Map these in your Backend Controller) */
.badge-status {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
}

.badge-status.stable {
    background: #DCFCE7;
    border: 1px solid #A7F3D0;
    color: #14532D;
}

.badge-status.low {
    background: #FEF9C3;
    border: 1px solid #FDE047;
    color: #713F12;
}

.badge-status.critical {
    background: #FEE2E2;
    border: 1px solid #FCA5A5;
    color: #7F1D1D;
}


    /* Dynamic Action Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-danger { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
    .badge-warning { background-color: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
    .badge-success { background-color: #D1FAE5; color: #065F46; border: 1px solid #6EE7B7; }

    .forecast-note {
        padding: 0 8px;
        font-size: 13px;
        color: #6B7280;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Responsive Windows */
    @media (max-width: 1024px) {
        .forecast-summary { grid-template-columns: 1fr; }
    }

    /* =========================================================
   AI Recommendation Card
========================================================= */

.ai-recommendation-card{
    background:#ffffff;
    border:1px solid #E5E7EB;
    border-radius:18px;

    padding:20px 24px;
    margin:10px 0;

    box-shadow:0 3px 10px rgba(15,23,42,.04);
}

.recommendation-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:14px;
}

.recommendation-header h3{
    margin:0;
    font-size:17px;
    font-weight:700;
    color:#1F2937;
}

.recommendation-header p{
    margin-top:4px;
    font-size:13px;
    color:#6B7280;
}

.recommendation-badge{
    background:#EEF2FF;
    color:#4338CA;
    border:1px solid #C7D2FE;

    padding:6px 12px;
    border-radius:999px;

    font-size:11px;
    font-weight:600;
}

.recommendation-content{
    display:flex;
    align-items:flex-start;
    gap:16px;
}

.recommendation-icon{
    width:42px;
    height:42px;

    border-radius:12px;

    display:flex;
    justify-content:center;
    align-items:center;

    font-size:18px;
    font-weight:bold;

    flex-shrink:0;
}

.success .recommendation-icon{
    background:#DCFCE7;
    color:#16A34A;
}

.warning .recommendation-icon{
    background:#FEF3C7;
    color:#D97706;
}

.recommendation-content h4{
    margin:0;
    margin-bottom:6px;

    font-size:16px;
    font-weight:600;

    color:#111827;
}

.recommendation-content p{
    margin:0;

    font-size:13px;
    line-height:1.7;

    color:#4B5563;
}

.recommendation-footer{
    display:flex;
    flex-wrap:wrap;
    gap:10px;

    margin-top:14px;
}

.recommendation-footer span{
    display:inline-flex;
    align-items:center;

    background:#F9FAFB;

    border:1px solid #E5E7EB;

    border-radius:999px;

    padding:6px 12px;

    font-size:12px;
    color:#4B5563;
}

.recommendation-footer strong{
    color:#111827;
    margin-left:4px;
}

/* Hover */

.ai-recommendation-card:hover{
    border-color:#D8B4FE;
    box-shadow:0 6px 16px rgba(124,58,237,.08);
    transition:.25s;
}

/* Responsive */

@media(max-width:768px){

    .recommendation-header{
        flex-direction:column;
        align-items:flex-start;
        gap:10px;
    }

    .recommendation-content{
        flex-direction:column;
    }

    .recommendation-footer{
        flex-direction:column;
    }

}

.chart-card{

    background:#fff;

    border-radius:18px;

    border:1px solid #E5E7EB;

    padding:24px;

    margin:24px 0;

    box-shadow:0 4px 12px rgba(15,23,42,.04);

}

.chart-header{

    margin-bottom:20px;

}

.chart-header h3{

    margin:0;

    font-size:18px;

    font-weight:700;

    color:#111827;

}

.chart-header p{

    margin-top:6px;

    font-size:13px;

    color:#6B7280;

}

#forecastChart{

    width:100%;

    height:340px !important;

}
</style>
</head>

<body>
<div class="container">

<x-sidebar />

    <div class="main">
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
                @php date_default_timezone_set('Asia/Manila'); @endphp
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="header-divider"></div>

        <div class="forecast-container">
<div class="info-banner-card">

    <div class="badge-status-ai">
        Active
    </div>

    <h2>AI-Assisted Medicine Forecasting</h2>

    <p>
        Utilizes the <strong>Moving Average Forecasting</strong> method to analyze historical
        medicine consumption, predict future demand for the next 30 days, identify
        potential stock shortages, and recommend optimal restock quantities to support
        timely inventory decisions.
    </p>

    <div class="banner-footer">
        <small>
            <strong>Forecast Model:</strong> Moving Average
        </small>

        <small>
            <strong>Forecast Horizon:</strong> 30 Days
        </small>

        <small>
            <strong>Viewing as:</strong> {{ ucfirst($role) }}
        </small>
    </div>

</div>

<div class="ai-recommendation-card">

    <div class="recommendation-header">

        <div>
            <h3>AI Recommendation</h3>
            <p>Generated from the latest Moving Average Forecast</p>
        </div>

        <span class="recommendation-badge">
            {{ ucfirst($aiStatus) }}
        </span>

    </div>

    <div class="recommendation-content {{ $aiStatus }}">

        <div class="recommendation-icon">
            {{ $aiStatus == 'success' ? '✓' : '⚠' }}
        </div>

        <div>

            <h4>{{ $aiTitle }}</h4>

            <p>{{ $aiMessage }}</p>

            <div class="recommendation-footer">

                <span>
                    <strong>Recommended Action:</strong>
                    {{ $aiAction }}
                </span>

            </div>

        </div>

    </div>

</div>

<div class="forecast-summary">

    <div class="summary-box box-shortage">
        <h4>Predicted Stock-Outs</h4>
        <span>
            {{ $shortageCount ?? 0 }}
            {{ ($shortageCount ?? 0) == 1 ? 'Medicine' : 'Medicines' }}
        </span>
    </div>

    <div class="summary-box box-demand">
        <h4>Threshold Alerts</h4>
        <span>
            {{ $highDemandCount ?? 0 }}
            {{ ($highDemandCount ?? 0) == 1 ? 'Medicine' : 'Medicines' }}
        </span>
    </div>

    <div class="summary-box box-restock">
        <h4>AI Restock Recommendations</h4>

        <span>
            @if($canManageForecast)
                {{ $restockCount ?? 0 }}
                {{ ($restockCount ?? 0) == 1 ? 'Medicine' : 'Medicines' }}
            @else
                View Only
            @endif
        </span>

    </div>

</div>

<div class="chart-card">

    <div class="chart-header">

        <div>
            <h3>Medicine Demand Forecast Trend</h3>
            <p>Moving Average Forecast based on the previous three months of medicine dispensing.</p>
        </div>

    </div>

    <canvas id="forecastChart"></canvas>

</div>

            <div class="table-card">
                <div class="toolbar-section">
                    <div class="toolbar-title">Predictive Forecast Metrics</div>
                    <input type="text" class="search-input" placeholder="Search medicine items...">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="p-3">MEDICINE DESCRIPTION</th>
                                <th class="p-3">CURRENT STOCK</th>
                                <th class="p-3">EST. DEMAND (30-DAY AVG)</th>
                                <th class="p-3">FORECAST STATUS</th>
                                <th class="p-3">RECOMMENDATION PLAN</th>                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecastData as $data)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-semibold">{{ $data['medicine']->name }} ({{ $data['medicine']->dosage_strength ?? '' }})</td>
                                
                                <!-- CURRENT STOCK: Galing sa Inventory -->
                                <td class="p-3">{{ number_format($data['current_stock']) }}</td>
                                
                                <!-- EST. DEMAND: May numero na, hindi na 0 -->
                                <td class="p-3 font-bold text-blue-700">{{ number_format($data['est_demand']) }}</td>
                                
                                <!-- STATUS: Nagbabago kulay base sa dami -->
                                <td class="p-3">
                                    <span class="{{ $data['status_class'] }}">
                                        {{ $data['status'] }}
                                    </span>
                                </td>
                                
                                <!-- RECOMMENDATION: ✅ MAY PAGKAKAIBA ADMIN vs DOCTOR -->
                                <td class="p-3 text-gray-600">
                                    @if($canManageForecast)
                                        {{-- ✅ ADMIN: BUONG DETALYE KASAMA ANG BILANG --}}
                                        {{ $data['recommendation'] }}
                                    @else
                                        {{-- ✅ DOCTOR: ITINANGGAL ANG BILANG / SUGGESTED QTY --}}
                                        @php
                                            // Tanggalin ang numero at suhestiyon para sa Doctor
                                            $cleanText = preg_replace('/\| Suggested: \d+/', '', $data['recommendation']);
                                        @endphp
                                        {{ $cleanText }}
                                    @endif
                                </td>
                                </td>
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

const ctx=document.getElementById('forecastChart');

new Chart(ctx,{type:'line', data:{labels:['April','May','June','Forecast'],
        datasets:[{
        label:'Average Consumption', data:[62,74,81,89],
        borderWidth:3,
        fill:true,
        tension:.35,
        pointRadius:5,
        pointHoverRadius:7
        }]
        },

        options:{responsive:true, plugins:{
            legend:{
            display:false
            }

            },
            scales:{y:{beginAtZero:true,
            title:{
                display:true,
                text:'Quantity Dispensed'
                }
            },
            x:{title:{display:true,
                text:'Forecast Period'
            }   
            }

            }

        }

    });
</script>
</body>
</html>
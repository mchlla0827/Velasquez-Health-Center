
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

.info-banner-card {
    position: relative;
    background: linear-gradient(135deg, rgba(245, 243, 255, 0.6) 0%, rgba(250, 245, 255, 0.4) 100%);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(233, 213, 255, 0.7);
    border-radius: 20px;
    padding: 28px 32px;
    margin: 12px 0 20px 0;
    width: 100%;
    box-sizing: border-box;
}



.info-banner-card h2 {
    margin: 0 0 10px 0;
    line-height: 1.3;
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: -0.02em;
    margin: 0;
}

.info-banner-card p {
    margin: 0;
    line-height: 1.7;
    max-width: 75%;
    opacity: 0.85;
    font-size: 14px;
    font-weight: 400;
    color: #1e293b;
    font-style: italic;
}

/* Badge — improved to match your table style perfectly + rounded pill shape */
.badge-status-ai {
    position: absolute;
    top: 28px;
    right: 32px;
    background: rgba(209, 250, 229, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(167, 243, 208, 0.7);
    color: #065F46;
    padding: 6px 14px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Green dot — slightly bigger & smoother */
.badge-status-ai::before {
    content: '';
    width: 7px;
    height: 7px;
    background-color: #16A34A;
    border-radius: 50%;
    display: inline-block;
    animation: pulse 2s infinite;
}

/* Optional subtle pulse animation for the dot */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

    .forecast-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        width: 100%;
    }

    .summary-box {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 24px;
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.6);
        display: flex;
        flex-direction: column;
        gap: 8px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.04);
    }

    .summary-box h4 {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4B5563;
    }

    .summary-box span {
        font-size: 25px;
        font-weight: 700;
        color: #111827;
    }

    /* Modifiers transition background tints instead of thick borders */
    .box-shortage { background: rgba(239, 68, 68, 0.08); border-color: rgba(239, 68, 68, 0.2); }
    .box-shortage h4 { color: #DC2626; }
    .box-demand { background: rgba(245, 158, 11, 0.08); border-color: rgba(245, 158, 11, 0.2); }
    .box-demand h4 { color: #D97706; }
    .box-restock { background: rgba(59, 130, 246, 0.08); border-color: rgba(59, 130, 246, 0.2); }
    .box-restock h4 { color: #2563EB; }

    /* TABLE CONTAINER CARD */
.table-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    margin-top: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
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
                    {{ $userName }}
                    @php
                        $roleColor = match($role) {
                            'admin' => '#9333EA', // <-- INAYOS: Tinanggal ang ;
                            'nurse' => '#10B981',
                            'bhw' => '#6366F1',
                            default => '#6B7280'
                        };
                    @endphp
                    <span class="role" style="background-color: {{ $roleColor }};">{{ strtoupper($role) }}</span>
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
            <div class="info-banner-card">
                <h2>AI-Assisted Medicine Forecasting</h2>
                <p>Utilizes moving average method to analyze past consumption data from multiple months, calculating average demand to forecast upcoming needs and suggest restock quantities.</p>
                <div class="badge-status-ai">Active</div>
                {{-- ✅ IPINAKITA ANG USER ROLE --}}
                <small style="margin-left: 10px; color:#666;">Viewing as: <strong>{{ ucfirst($role) }}</strong></small>
            </div>

            <div class="forecast-summary">
                <div class="summary-box box-shortage">
                    <h4>Predicted Shortages</h4>
                    <span>{{ $shortageCount ?? 0 }} {{ ($shortageCount ?? 0) == 1 ? 'Item' : 'Items' }}</span>
                </div>
                <div class="summary-box box-demand">
                    <h4>Low Stock / Threshold Alert</h4>
                    <span>{{ $highDemandCount ?? 0 }} {{ ($highDemandCount ?? 0) == 1 ? 'Item' : 'Items' }}</span>
                </div>
                <div class="summary-box box-restock">
                    <h4>Recommended Restocks</h4>
                    <span>
                        {{-- ✅ ADMIN: Nakikita ang bilang | DOCTOR: View Only lang --}}
                        @if($canManageForecast)
                            {{ $restockCount ?? 0 }} {{ ($restockCount ?? 0) == 1 ? 'Item' : 'Items' }}
                        @else
                            View Only
                        @endif
                    </span>
                </div>
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
</body>
</html>
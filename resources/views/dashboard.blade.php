@php
    use Illuminate\Support\Facades\Auth;
    $role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw');
    $userName = Auth::user()->name ?? session('admin_name') ?? 'User';
    $is_pic = session('is_pic') ?? Auth::user()->is_pic ?? 0; 

    $routePrefix = $role . '.';

    // Access Rules — define ONCE at top
    $canViewInventory   = in_array($role, ['admin', 'nurse', 'doctor', 'bhw']);
    $canViewForecast    = in_array($role, ['admin', 'nurse']);
    $canViewRequestForm = ($role === 'admin' || ($role === 'doctor' && $is_pic == 1));
    $canViewFullStats   = $canViewRequestForm;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <title>Barangay Health System - {{ ucfirst($role) }} Portal</title>

    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #F9FAFB; overflow-x: hidden; }
        .container { display: flex; }

        .sidebar { width: 260px; height: 100vh; background: white; border-right: 1px solid #E5E7EB; padding: 24px; position: fixed; top: 0; left: 0; box-sizing: border-box; overflow-y: auto; }
        .sidebar-header { display: flex; align-items: center; gap: 14px; padding-bottom: 14px; border-bottom: 1px solid #E5E7EB; margin-bottom: 10px; }
        .logo { width: 40px; height: 40px; border-radius: 50%; }
        .brand { font-weight: bold; color: #1E3A8A; font-size: 13.3px; }
        .sub { font-size: 11px; color: #6B7280; }
        .group { margin-top: 22px; font-size: 11px; font-weight: bold; color: #9CA3AF; text-transform: uppercase; }
        .nav-item { display: flex; align-items: center; gap: 14px; padding: 10px; margin-top: 6px; text-decoration: none; color: #5f6570; border-radius: 6px; font-size: 14px; }
        .nav-icon { width: 22px; height: 22px; object-fit: contain; flex-shrink: 0; }
        .nav-item.active { background: #EFF6FF; color: #1A73E8; border-left: 4px solid #1A73E8; font-weight: bold; }
        .nav-item:hover { background: #F3F4F6; }

        .nav-btn {
            width: 100%;
            text-align: left;
            border: none;
            background: transparent;
            cursor: pointer;
            font-family: inherit;
        }

        .main { margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; }
        .welcome-text { font-size: 14px; color: #374151; margin-bottom: 5px; }
        .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }

        .role { color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-left: 6px; text-transform: uppercase; }
        .role-admin { background: #9333EA; }
        .role-doctor { background: #3B82F6; }
        .role-nurse { background: #10B981; }
        .role-bhw { background: #6366F1; }

        .right { text-align: right; font-size: 12px; color: #374151; }
        .header-divider { width: 100%; height: 1px; background: #E5E7EB; margin: 16px 0; }
        .page-title { font-size: 22px; font-weight: bold; color: #111827; margin-bottom: 12px; }

        .alert { background: #FEF2F2; border-left: 4px solid #DC2626; padding: 18px; border-radius: 6px; color: #B91C1C; font-weight: bold; margin-bottom: 20px; }

        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 24px; }
        .card { background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; }
        .card-icon-box { width: 44px; height: 44px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        
        .kpi-icon { width: 44px; height: 44px; object-fit: contain; }
        .value { font-size: 32px; font-weight: bold; color: #111827; line-height: 1; }
        .label { font-size: 13px; color: #6B7280; margin-top: 4px; }

        .row-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 28px; }
        .row-1col { display: grid; grid-template-columns: 1fr; gap: 24px; margin-top: 28px; }

        .data-card { background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px; width: 100%; box-sizing: border-box; }
        .data-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; font-weight: bold; color: #111827; font-size: 15px; }
        .data-header-title { display: flex; align-items: center; gap: 8px; }
        .badge-blue { background: #E8F0FE; color: #1A73E8; font-size: 11px; padding: 3px 8px; border-radius: 20px; font-weight: 500; }
        .badge-red { background: #FEE2E2; color: #B91C1C; font-size: 11px; padding: 3px 8px; border-radius: 20px; font-weight: 500; }
        
        .row { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid #F3F4F6; }
        .row:last-child { border-bottom: none; }
        .name, .stock-name { font-size: 14px; color: #111827; padding-bottom: 3px; font-weight: 500; }
        .time, .stock-meta { font-size: 12px; color: #6B7280; }
        .stock-value-red { color: #DC2626; font-weight: bold; text-align: right; }
        .pill { font-size: 11px; padding: 4px 12px; border-radius: 20px; background: #D1FAE5; color: #065F46; }

        .card-link { text-decoration: none; color: inherit; display: block; }
        .card-link .card { transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease; cursor: pointer; }
        .card-link:hover .card { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08); border-color: #2563eb; }

        .link-more { color: #2563EB; font-size: 13px; font-weight: 600; text-decoration: none; }
        .link-more:hover { text-decoration: underline; }

        /* --- CLEAN AI FORECAST TABLE --- */
        .forecast-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .forecast-table th { text-align: left; font-size: 12px; font-weight: 600; color: #6B7280; uppercase; letter-spacing: 0.5px; padding: 10px 12px; background: #F9FAFB; border-bottom: 1px solid #E5E7EB; }
        .forecast-table td { font-size: 14px; color: #374151; padding: 12px; border-bottom: 1px solid #F3F4F6; }
        .forecast-table tr:last-child td { border-bottom: none; }
        .text-danger { color: #DC2626; font-weight: 600; }
        .text-success { color: #059669; font-weight: 600; }
        .text-warning { color: #D97706; font-weight: 600; }
        .forecast-note { font-size: 12px; color: #6B7280; font-style: italic; margin-top: 12px; }

        /* --- CLEAN PATIENT RISK SUMMARY GRID --- */
        .summary-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 12px; }
        .summary-item { background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px; text-align: center; }
        .summary-item .num { font-size: 26px; font-weight: 700; line-height: 1; }
        .summary-item .lbl { font-size: 12px; font-weight: 600; color: #6B7280; margin-top: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>

<div class="container">
    <x-sidebar />
</div>

<div class="main">
    <div class="header">
        <div>
            <div class="welcome-text">Welcome back,</div>

            @php
                $user = Auth::user();

                $displayRole = (
                    strtolower($user->role) === 'doctor' &&
                    $user->is_physician_in_charge == 1
                ) ? 'PIC' : strtoupper($role);

                $badgeClass = strtolower($user->role);
            @endphp

            <div class="welcome-name">
                {{ $userName }}

                <span class="role role-{{ $badgeClass }}">
                    {{ $displayRole }}
                </span>
            </div>
        </div>

        <div class="right">
            <b>Velasquez Health Center</b><br>
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>

    <div class="header-divider"></div>
    <div class="page-title">Dashboard</div>

    @if(in_array($role, ['admin', 'nurse']) && $lowStockMedicines->count() > 0)
        <div class="alert">
            ⚠ CRITICAL LOW STOCK: {{ $lowStockMedicines->count() }} medicine(s) need attention
        </div>
    @endif

    @php
        $role = strtolower(auth()->user()->role ?? 'admin');
    @endphp

    <div class="cards">
        <!-- 1. New Patients Today -->
        <a href="{{ route($role . '.patient-records', ['filter' => 'today_registered']) }}" class="card-link">
            <div class="card">
                <div class="card-icon-box">
                    <img src="/icons/total-patients.png" class="kpi-icon" style="filter: invert(39%) sepia(99%) saturate(1200%) hue-rotate(210deg) brightness(100%) contrast(95%);">
                </div>
                <div>
                    <div class="value">{{ $patientsToday->count() }}</div>
                    <div class="label">New Patients Today</div>
                </div>
            </div>
        </a>

        <!-- 2. Medicines Dispensed Today -->
        @if($canViewFullStats)
        <a href="{{ route($role . '.dispense', ['filter' => 'today']) }}" class="card-link">
            <div class="card">
                <div class="card-icon-box">
                    <img src="/icons/clipboard.png" class="kpi-icon" style="filter: invert(51%) sepia(98%) saturate(600%) hue-rotate(5deg) brightness(102%) contrast(98%);">
                </div>
                <div>
                    <div class="value">{{ $medicinesDispensedToday }}</div>
                    <div class="label">Medicines Dispensed Today</div>
                </div>
            </div>
        </a>
        @endif

        <!-- 3. Patients Consulted Today -->
        @if($canViewFullStats)
        <a href="{{ route($role . '.patient-records', ['filter' => 'today_consulted']) }}" class="card-link">
            <div class="card">
                <div class="card-icon-box">
                    <img src="/icons/heart-rate.png" class="kpi-icon" style="filter: invert(28%) sepia(96%) saturate(1100%) hue-rotate(270deg) brightness(95%) contrast(105%);">
                </div>
                <div>
                    <div class="value">{{ $patientsConsultedToday }}</div>
                    <div class="label">Patients Consulted Today</div>
                </div>
            </div>
        </a>
        @endif

        <!-- 4. Total Patients -->
        <a href="{{ route($role . '.patient-records') }}" class="card-link">
            <div class="card">
                <div class="card-icon-box">
                    <img src="/icons/folder.png" class="kpi-icon" style="filter: invert(45%) sepia(90%) saturate(800%) hue-rotate(110deg) brightness(95%) contrast(100%);">
                </div>
                <div>
                    <div class="value">{{ $totalRegisteredPatients }}</div>
                    <div class="label">Total Patients</div>
                </div>
            </div>
        </a>

        <!-- 5. Critical Stock Items -->
        @if($canViewInventory)
        <a href="{{ route($role . '.inventory', ['status' => 'critical']) }}" class="card-link">
            <div class="card">
                <div class="card-icon-box">
                    <img src="/icons/box.png" class="kpi-icon" style="filter: invert(62%) sepia(99%) saturate(550%) hue-rotate(15deg) brightness(105%) contrast(92%);">
                </div>
                <div>
                    <div class="value">{{ $lowStockMedicines->count() }}</div>
                    <div class="label">Critical Stock Items</div>
                </div>
            </div>
        </a>
        @endif

        <!-- 6. High-Risk Patients -->
        <a href="{{ route($role . '.triage', ['risk' => 'high']) }}" class="card-link">
            <div class="card">
                <div class="card-icon-box">
                    <img src="/icons/alert.png" class="kpi-icon" style="filter: invert(22%) sepia(98%) saturate(1400%) hue-rotate(350deg) brightness(90%) contrast(110%);">
                </div>
                <div>
                    <div class="value">{{ $highRiskCount }}</div>
                    <div class="label">High-Risk Patients</div>
                </div>
            </div>
        </a>
    </div>

    <!-- FIRST SECTION: Recent Patients + Stock Alerts -->
    <div class="row-2col">
        <div class="data-card">
            <div class="data-header">
                <span class="data-header-title">Recent Patients <span class="badge-blue">{{ $patientsTodayCount }} today</span></span>
            </div>
            
            @forelse($recentPatients as $patient)
                <div class="row">
                    <div>
                        <div class="name">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                        <div class="time">
                            @if(in_array($patient->id, $consultedPatientIds))
                                Consulted · 
                            @else
                                Recorded · 
                            @endif
                            {{ optional($patient->created_at)->diffForHumans() ?? 'No date available' }}
                        </div>
                    </div>
                    <span class="pill">
                        @if(in_array($patient->id, $consultedPatientIds))
                            Consulted
                        @else
                            Recorded
                        @endif
                    </span>
                </div>
            @empty
                <p style="color: #6B7280; font-size: 14px; padding: 1rem 0;">No recent patients</p>
            @endforelse
        </div>

        <div class="data-card">
            <div class="data-header">
                <span class="data-header-title">
                    @if($role === 'doctor') 
                        Low Stock Alert
                    @elseif($role === 'bhw') 
                        Stock Alerts
                    @else 
                        Critical Stock Alerts
                    @endif
                    <span class="badge-red">{{ $lowStockMedicines->count() }} items</span>
                </span>
            </div>

            @if($canViewInventory || $role === 'bhw')
                @forelse($lowStockMedicines as $medicine)
                    <div class="row">
                        <div>
                            <div class="stock-name">{{ $medicine->name }}</div>
                            <div class="stock-meta">
                                @if($role === 'doctor') 
                                    Available for prescription
                                @elseif($role === 'bhw') 
                                    Notify Admin for re-stocking
                                @else 
                                    Needs Attention
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="stock-value-red">{{ $medicine->stock }} pcs</div>
                            <div class="stock-meta" style="text-align: right;">remaining</div>
                        </div>
                    </div>
                @empty
                    <p style="color: #6B7280; font-size: 14px; padding: 1rem 0;">No low stock medicines</p>
                @endforelse
            @else
                <p style="color: #6B7280; font-size: 14px; padding: 1rem 0;">Not available for your role</p>
            @endif
        </div>
    </div>

    <!-- SECOND SECTION: AI-Forecast Summary (Structured Table View) -->
    @if($canViewForecast)
    <div class="row-1col">
        <div class="data-card">
            <div class="data-header">
                <span class="data-header-title">AI-Forecast Summary</span>
                <a href="{{ route($routePrefix . 'forecast') }}" class="link-more">View Full Forecast →</a>
            </div>
            
            <table class="forecast-table">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Predicted Shortage</th>
                        <th>Recommended Restock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forecastData as $item)
                        <tr>
                            <td style="font-weight: 500;">{{ $item['name'] ?? '—' }}</td>
                            <td class="text-danger">{{ $item['predicted_shortage'] ?? '—' }}</td>
                            <td class="text-success">{{ $item['recommended_restock'] ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #6B7280; font-style: italic; padding: 16px;">
                                No forecast data available yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="forecast-note">
                *Forecast based on usage trends from the last 30 days.
            </div>
        </div>
    </div>
    @endif

    <!-- THIRD SECTION: Patient Risk Summary (Clean Equal Stat Cards) -->
    <div class="row-1col">
        <div class="data-card">
            <div class="data-header">
                <span class="data-header-title">Patient Risk Summary</span>
                <a href="{{ route($routePrefix . 'triage') }}" class="link-more">View Full Triage →</a>
            </div>
            <div class="summary-row">
                <div class="summary-item">
                    <div class="num" style="color:#374151;">{{ $totalInQueue ?? 0 }}</div>
                    <div class="lbl">Total in Queue</div>
                </div>
                <div class="summary-item">
                    <div class="num" style="color:#DC2626;">{{ $highRiskCount ?? 0 }}</div>
                    <div class="lbl">High Risk</div>
                </div>
                <div class="summary-item">
                    <div class="num" style="color:#D97706;">{{ $mediumRiskCount ?? 0 }}</div>
                    <div class="lbl">Medium Risk</div>
                </div>
                <div class="summary-item">
                    <div class="num" style="color:#059669;">{{ $lowRiskCount ?? 0 }}</div>
                    <div class="lbl">Low Risk</div>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
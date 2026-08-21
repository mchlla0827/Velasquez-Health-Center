<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reports — Velasquez Health Center</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            overflow-x: hidden;
        }

        .container { display: flex; min-height: 100vh; }

        /* ================= MAIN LAYOUT ================= */
        .main {
            margin-left: 250px;
            width: calc(100% - 260px);
            padding: 24px;
            box-sizing: border-box;
        }

        /* ================= HEADER — SAME AS ALL PAGES ================= */
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
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .role {
            color: white;
            padding: 3px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-admin  { background: #9333EA; }
        .role-nurse  { background: #10B981; }
        .role-bhw    { background: #6366F1; }
        .role-doctor { background: #3B82F6; }

        .header-right {
            text-align: right;
            font-size: 12px;
            color: #374151;
            line-height: 1.5;
        }

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
            margin-bottom: 24px;
        }

        /* ================= REPORT CARDS ================= */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .action-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 18px 20px;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            text-decoration: none;
            color: inherit;
            min-height: 90px;
            transition: all 0.2s ease;
            background: white;
        }

        .action-card img {
            opacity: 0.85;
            flex-shrink: 0;
        }

        .action-card > div {
            display: flex;
            flex-direction: column;
        }

        .action-card span {
            font-size: 15px;
            font-weight: 600;
            color: #1F2937;
        }

        .action-card small {
            font-size: 13px;
            color: #6B7280;
            margin-top: 4px;
        }

        .action-card:hover {
            border-color: #2563EB;
            background: #EFF6FF;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
        }

        .loading {
            color: #9CA3AF;
            font-style: italic;
        }

        /* Responsive */
        @media (max-width: 860px) {
            .action-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- ✅ USES YOUR SIDEBAR COMPONENT (NO DUPLICATE CODE) -->
    <x-sidebar />

    <div class="main">

        <!-- ✅ HEADER — EXACTLY SAME AS TRIAGE & PATIENT RECORDS -->
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    @php
                        $userName = session('admin_name') ?? session('user_name') ?? Auth::user()->name ?? 'User';
                        $role = strtolower(session('admin_role') ?? session('user_role') ?? Auth::user()->role ?? 'bhw');

                        $displayRole = ($role === 'doctor' && (string)(Auth::user()->is_physician_in_charge ?? '') === '1')
                            ? 'PIC'
                            : strtoupper($role);

                        $roleClass = match($role) {
                            'admin' => 'role-admin',
                            'nurse' => 'role-nurse',
                            'doctor' => 'role-doctor',
                            default => 'role-bhw',
                        };
                    @endphp
                    {{ $userName }}
                    <span class="role {{ $roleClass }}">{{ $displayRole }}</span>
                </div>
            </div>
            <div class="header-right">
                <b>Velasquez Health Center</b><br>
                @php date_default_timezone_set('Asia/Manila'); @endphp
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="header-divider"></div>

        <div class="page-title">Reports</div>

        <!-- REPORT CARDS -->
        <div class="action-grid">

            <a href="{{ route('admin.reports.patient') }}" class="action-card">
                <img src="/icons/patient-records.png" width="30" alt="">
                <div>
                    <span>Patient Reports</span>
                    <small id="patientReports" class="loading">Loading...</small>
                </div>
            </a>

            <a href="{{ route('admin.reports.risk') }}" class="action-card">
                <img src="/icons/AI-forecast.png" width="25" alt="">
                <div>
                    <span>Patient Risk Reports</span>
                    <small id="riskReports" class="loading">Loading...</small>
                </div>
            </a>

            <a href="{{ route('admin.reports.medicine') }}" class="action-card">
                <img src="/icons/medicine-inventory.png" width="25" alt="">
                <div>
                    <span>Medicine Inventory Reports</span>
                    <small id="inventoryReports" class="loading">Loading...</small>
                </div>
            </a>

            <a href="{{ route('admin.reports.dispensing') }}" class="action-card">
                <img src="/icons/reports.png" width="25" alt="">
                <div>
                    <span>Dispensing Reports</span>
                    <small id="dispensingReports" class="loading">Loading...</small>
                </div>
            </a>

            <a href="{{ route('admin.reports.operational') }}" class="action-card">
                <img src="/icons/reports.png" width="25" alt="">
                <div>
                    <span>Operational Reports</span>
                    <small id="operationReports" class="loading">Loading...</small>
                </div>
            </a>

        </div>

    </div>
</div>

<!-- ⚡ OPTIMIZED API CALLS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const API_ENDPOINTS = [
        { id: 'patientReports', url: "{{ route('admin.reports.api.patient') }}" },
        { id: 'riskReports', url: "{{ route('admin.reports.api.risk') }}" },
        { id: 'inventoryReports', url: "{{ route('admin.reports.api.medicine') }}" },
        { id: 'dispensingReports', url: "{{ route('admin.reports.api.dispensing') }}" },
        { id: 'operationReports', url: "{{ route('admin.reports.api.operational') }}" }
    ];

    // ⚡ Load one at a time instead of all at once + timeout protection
    async function loadCounter(item) {
        const el = document.getElementById(item.id);
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 8000); // 8s timeout

        try {
            const res = await fetch(item.url, {
                signal: controller.signal,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            clearTimeout(timeoutId);

            if (!res.ok) throw new Error('Not OK');
            const data = await res.json();
            el.textContent = data.total ?? 'Ready';
        } catch (err) {
            clearTimeout(timeoutId);
            el.textContent = 'Ready';
            el.classList.remove('loading');
        }
    }

    // Start loading sequentially — page shows instantly!
    API_ENDPOINTS.forEach(loadCounter);
});
</script>

</body>
</html>
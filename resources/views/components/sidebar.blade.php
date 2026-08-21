<style>
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
        z-index: 1000;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #E5E7EB;
        margin-bottom: 10px;
    }

    .logo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .brand-wrapper {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .brand {
        font-weight: bold;
        color: #1E3A8A;
        font-size: 13.3px;
    }

    .sub {
        font-size: 11px;
        color: #6B7280;
    }

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
        transition: background .2s;
    }

    .nav-icon {
        width: 22px;
        height: 22px;
        object-fit: contain;
    }

    .nav-item:hover {
        background: #F3F4F6;
    }

    .nav-item.active {
        background: #EFF6FF;
        color: #1A73E8;
        border-left: 4px solid #1A73E8;
        font-weight: bold;
    }

    /* ✅ Log Out — matches exactly */
    .logout-nav {
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        cursor: pointer;
        font-family: inherit;
    }
    .logout-nav:hover {
        background: #F3F4F6;
    }
    .logout-nav:active {
        background: #EFF6FF;
        color: #1A73E8;
        border-left: 4px solid #1A73E8;
        font-weight: bold;
    }
    form:has(.logout-nav) {
        margin: 0;
        padding: 0;
        margin-top: 22px;
    }
</style>


@php
    $role = session('admin_role')
        ?? session('user_role')
        ?? (Auth::check() ? Auth::user()->role : 'bhw');

    $roleLower = strtolower(trim($role));

    $allowedRoles = ['admin', 'nurse', 'doctor', 'bhw'];

    if (!in_array($roleLower, $allowedRoles)) {
        $roleLower = 'bhw';
    }

    $isPIC = (string) Auth::user()->is_physician_in_charge === '1';
@endphp


<div class="sidebar">

    <div class="sidebar-header">
        <img src="/bhclogo.jpg" class="logo">

        <div class="brand-wrapper">
            <div class="brand">Barangay Health System</div>
            <div class="sub">Health Information System</div>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="group">MAIN</div>

    <a class="nav-item {{ Request::is("$roleLower/dashboard") ? 'active' : '' }}"
       href="/{{ $roleLower }}/dashboard">
        <img src="/icons/dashboard2.png" class="nav-icon">
        Dashboard
    </a>

    {{-- INVENTORY --}}
    @if(in_array($roleLower,['admin','nurse','doctor','bhw']))

        <div class="group">INVENTORY</div>

        <a class="nav-item {{ Request::is("$roleLower/inventory*") ? 'active' : '' }}"
           href="/{{ $roleLower }}/inventory">
            <img src="/icons/medicine-inventory.png" class="nav-icon">
            Medicine Inventory
        </a>

        @if(in_array($roleLower,['admin','nurse']))
            <a class="nav-item {{ Request::is("$roleLower/dispense*") ? 'active' : '' }}"
               href="/{{ $roleLower }}/dispense">
                <img src="/icons/dispense-medicine.png" class="nav-icon">
                Dispense Medicine
            </a>

            <a class="nav-item {{ Request::is("$roleLower/forecast*") ? 'active' : '' }}"
               href="/{{ $roleLower }}/forecast">
                <img src="/icons/AI-forecast.png" class="nav-icon">
                AI Forecast
            </a>

            <a class="nav-item {{ Request::is("$roleLower/stockout*") ? 'active' : '' }}"
               href="/{{ $roleLower }}/stockout">
                <img src="/icons/stock-out.png" class="nav-icon">
                Stock-Out Log
            </a>
        @endif

        {{-- REQUEST FORM --}}
        @if(
            $roleLower === 'admin' ||
            $roleLower === 'nurse' ||
            ($roleLower === 'doctor' && $isPIC)
        )

            <a class="nav-item {{ Request::is("$roleLower/request*") ? 'active' : '' }}"
               href="/{{ $roleLower }}/request">
                <img src="/icons/request-form.png" class="nav-icon">
                Request Form
            </a>

        @endif

    @endif

    {{-- PATIENTS --}}
    <div class="group">PATIENTS</div>

    @if(in_array($roleLower,['admin','nurse', 'bhw']))

        <a class="nav-item {{ Request::is("$roleLower/patient-registration*") ? 'active' : '' }}"
           href="/{{ $roleLower }}/patient-registration">
            <img src="/icons/patient-registration.png" class="nav-icon">
            Patient Registration
        </a>

    @endif

    <a class="nav-item {{ Request::is("$roleLower/patient-records*") ? 'active' : '' }}"
       href="/{{ $roleLower }}/patient-records">
        <img src="/icons/patient-records.png" class="nav-icon">
        Patient Records
    </a>

    <a class="nav-item {{ Request::is("$roleLower/triage*") ? 'active' : '' }}"
       href="/{{ $roleLower }}/triage">
        <img src="/icons/patient-triage.png" class="nav-icon">
        Patient Triage
    </a>

    {{-- REPORTS --}}
    <div class="group">
        {{ $roleLower == 'admin' ? 'REPORTS & ADMIN' : 'REPORTS' }}
    </div>

    <a class="nav-item {{ Request::is("$roleLower/reports*") ? 'active' : '' }}"
       href="/{{ $roleLower }}/reports">
        <img src="/icons/reports.png" class="nav-icon">
        Main Reports
    </a>

    {{-- ADMIN ONLY --}}
    @if($roleLower == 'admin')

        <a class="nav-item {{ Request::is('admin/create-user*') ? 'active' : '' }}"
           href="/admin/create-user">
            <img src="/icons/create-user.png" class="nav-icon">
            Create Users
        </a>

        <a class="nav-item {{ Request::is('admin/manage-user*') ? 'active' : '' }}"
           href="/admin/manage-user">
            <img src="/icons/manage-user.png" class="nav-icon">
            Manage Users
        </a>

        <a class="nav-item {{ Request::is('admin/logs*') ? 'active' : '' }}"
           href="/admin/logs">
            <img src="/icons/activity-logs.png" class="nav-icon">
            Activity Logs
        </a>

    @endif

    {{-- ✅ FIXED LOGOUT --}}
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" class="nav-item logout-nav">
            <img src="/icons/logout.png" class="nav-icon">
            Log Out
        </button>
    </form>

</div>
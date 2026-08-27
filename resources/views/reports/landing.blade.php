<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Reports</title>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #F9FAFB;
        overflow-x: hidden;
    }

    .container { display: flex; }

    .main {
        margin-left: 260px;
        width: calc(100% - 260px);
        padding: 24px;
        box-sizing: border-box;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .welcome-text{ font-size: 14px; color: #374151; margin-bottom: 5px; }
    .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }

    .role {
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
    }

    .right { text-align: right; font-size: 12px; color: #374151; }

    .header-divider {
        width: 100%;
        height: 1px;
        background: #E5E7EB;
        margin: 16px 0;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        padding: 25px;
        box-sizing: border-box;
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        min-height: 90px;
    }

    .action-card img { opacity: 0.85; }

    .action-card span {
        display: block;
        font-size: 15px;
        font-weight: 600;
    }

    .action-card small {
        display: block;
        font-size: 13px;
        color: #6B7280;
        margin-top: 2px;
    }

    .action-card:hover {
        border-color: #D1D5DB;
        background: #FAFAFA;
    }
</style>
</head>

<body>

@php
    $__u = Auth::user();
    $__role = strtolower($__u->role ?? 'bhw');
    $__isPIC = ($__role === 'doctor' && (string) $__u->is_physician_in_charge === '1');
    $__displayRole = $__isPIC ? 'PIC' : strtoupper($__role);
    $__roleColor = match($__role) {
        'admin'  => '#9333EA',
        'nurse'  => '#10B981',
        'doctor' => '#3B82F6',
        'bhw'    => '#6366F1',
        default  => '#6B7280',
    };
    $__baseUrl = "/{$__role}/reports";
@endphp

<div class="container">

<x-sidebar />

<div class="main">

    <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ session('user_name') ?? $__u->name }}
                    <span class="role" style="background: {{ $__roleColor }};">{{ $__displayRole }}</span>
                </div>
            </div>
        <div class="right">
            <b>Velasquez Health Center</b><br>
            @php date_default_timezone_set('Asia/Manila'); @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>

    <div class="header-divider"></div>

<div class="content-card">

    <div class="card-title">Reports Overview</div>

    <div class="action-grid">

        <a href="{{ $__baseUrl }}/patient" class="action-card">
            <img src="/icons/patient-records.png" width="30">
            <div>
                <span>Patient Reports</span>
            </div>
        </a>

        <a href="{{ $__baseUrl }}/risk" class="action-card">
            <img src="/icons/AI-forecast.png" width="30">
            <div>
                <span>Patient Risk Reports</span>
            </div>
        </a>

        <a href="{{ $__baseUrl }}/medicine" class="action-card">
            <img src="/icons/medicine-inventory.png" width="30">
            <div>
                <span>Medicine Inventory</span>
            </div>
        </a>

        <a href="{{ $__baseUrl }}/dispensing" class="action-card">
            <img src="/icons/reports.png" width="30">
            <div>
                <span>Dispensing Reports</span>
            </div>
        </a>

        <a href="{{ $__baseUrl }}/operational" class="action-card">
            <img src="/icons/reports.png" width="30">
            <div>
                <span>Operational Reports</span>
            </div>
        </a>

    </div>

    </div>
</div>
</div>
</body>
</html>
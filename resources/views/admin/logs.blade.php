@php
date_default_timezone_set('Asia/Manila');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Activity Logs</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #F9FAFB;
    overflow-x: hidden;
}

.container { display: flex; }

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

.nav-icon {
    width: 22px;
    height: 22px;
    object-fit: contain;
    flex-shrink: 0;
}

.nav-item.active {
    background: #EFF6FF;
    color: #1A73E8;
    border-left: 4px solid #1A73E8;
    font-weight: bold;
}

.nav-item:hover { background: #F3F4F6; }

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
    text-transform: uppercase;
}

.right { text-align: right; font-size: 12px; color: #374151; }

.header-divider {
    width: 100%;
    height: 1px;
    background: #E5E7EB;
    margin: 16px 0;
}

/* INSIDE CONTENT */
.content-wrapper {
    background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
    border-radius: 18px;
    padding: 22px;
}

.top-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.page-title {
    font-size: 24px;
    color: #111827;
    font-weight: bold;
}

.page-subtitle {
    font-size: 13px;
    color: #6B7280;
    margin-top: 4px;
}

.filters {
    display: flex;
    gap: 10px;
}

.filters input {
    width: 160px;
    height: 38px;
    border: 1px solid #D1D5DB;
    border-radius: 10px;
    background: white;
    padding: 0 12px;
    outline: none;
}

.filters input:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px #DBEAFE;
}

.export-btn {
    height: 38px;
    background: #2563EB;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 0 18px;
    font-weight: bold;
    cursor: pointer;
}

.export-btn:hover { background: #1D4ED8; }

.cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 22px;
}

.card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
}

.card-label {
    font-size: 13px;
    color: #6B7280;
}

.card-number {
    font-size: 28px;
    font-weight: bold;
    margin-top: 8px;
    color: #111827;
}

.card.blue { background: #EFF6FF; border-color: #BFDBFE; }
.card.green { background: #ECFDF5; border-color: #A7F3D0; }
.card.purple { background: #FAF5FF; border-color: #E9D5FF; }

.blue-text { color: #2563EB; }
.green-text { color: #16A34A; }
.purple-text { color: #9333EA; }

.table-box {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
}

.table-title {
    padding: 18px 22px;
    font-size: 18px;
    font-weight: 800;
    border-bottom: 1px solid #E5E7EB;
    color: #111827;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

th {
    text-align: left;
    background: #F9FAFB;
    padding: 14px 22px;
    font-size: 11px;
    color: #374151;
    border-bottom: 1px solid #111827;
}

td {
    padding: 15px 22px;
    border-bottom: 1px solid #E5E7EB;
    color: #374151;
}

tr:hover td {
    background: #F8FAFC;
}

td b { color: #111827; }

.badge {
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: bold;
}

.dispense { background: #DBEAFE; color: #2563EB; }
.create { background: #DCFCE7; color: #16A34A; }
.approve { background: #FEF3C7; color: #D97706; }
.update { background: #F3E8FF; color: #9333EA; }
.report { background: #F3F4F6; color: #4B5563; }
.delete { background: #FEE2E2; color: #DC2626; }
.view { background: #F3F4F6; color: #6B7280; }
.login { background: #E0F2FE; color: #0369A1; }
.logout { background: #F1F5F9; color: #475569; }

.footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 18px;
    font-size: 13px;
    color: #6B7280;
}

.custom-pagination {
    display: flex;
    align-items: center;
    gap: 8px;
}

.page-btn {
    min-width: 34px;
    height: 34px;
    padding: 0 12px;
    border-radius: 10px;
    border: 1px solid #E5E7EB;
    background: white;
    color: #374151;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
}

.page-btn:hover {
    background: #F3F4F6;
}

.page-btn.active {
    background: #2563EB;
    border-color: #2563EB;
    color: white;
}

.page-btn.disabled {
    color: #9CA3AF;
    background: #F9FAFB;
    pointer-events: none;
}

.arrow {
    font-size: 18px;
    font-weight: bold;
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
                {{ session('admin_name') ?? 'Admin' }}
                <span class="role">{{ session('admin_role') ?? 'admin' }}</span>
            </div>
        </div>

        <div class="right">
            <b>Velasquez Health Center</b><br>
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>

    <div class="header-divider"></div>

    <div class="content-wrapper">

        <div class="top-section">
            <div>
                <div class="page-title">Activity Logs</div>
                <div class="page-subtitle">System audit trail of all user actions</div>
            </div>

            <form method="GET" action="/admin/logs" class="filters">
                <input type="text" name="search" placeholder="Search logs..." value="{{ request('search') }}">
                <input type="date" name="date" value="{{ request('date') }}">
                <button class="export-btn" type="submit">Filter</button>
            </form>
        </div>

        <div class="cards">
            <div class="card">
                <div class="card-label">Total Actions Today</div>
                <div class="card-number">{{ $totalToday ?? 0 }}</div>
            </div>

            <div class="card blue">
                <div class="card-label">Medicine Dispensed</div>
                <div class="card-number blue-text">{{ $dispensedToday ?? 0 }}</div>
            </div>

            <div class="card green">
                <div class="card-label">Created Records</div>
                <div class="card-number green-text">{{ $createdToday ?? 0 }}</div>
            </div>

            <div class="card purple">
                <div class="card-label">Active Users</div>
                <div class="card-number purple-text">{{ $activeUsers ?? 0 }}</div>
            </div>
        </div>

        <div class="table-box">
            <div class="table-title">Recent Activity</div>

            <table>
                <thead>
                    <tr>
                        <th>DATE & TIME</th>
                        <th>USER</th>
                        <th>ACTION</th>
                        <th>DETAILS</th>
                        <th>TYPE</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d h:i A') }}</td>
                            <td><b>{{ $log->user_name }}</b></td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->details }}</td>
                            <td>
                                <span class="badge {{ strtolower($log->type) }}">
                                    {{ $log->type }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px;">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div>
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() ?? 0 }} activity logs
            </div>

            <div class="custom-pagination">
                @if ($logs->onFirstPage())
                    <span class="page-btn disabled"><span class="arrow">‹</span></span>
                @else
                    <a class="page-btn" href="{{ $logs->previousPageUrl() }}"><span class="arrow">‹</span></a>
                @endif

                @for ($i = 1; $i <= $logs->lastPage(); $i++)
                    @if ($i == $logs->currentPage())
                        <span class="page-btn active">{{ $i }}</span>
                    @else
                        <a class="page-btn" href="{{ $logs->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor

                @if ($logs->hasMorePages())
                    <a class="page-btn" href="{{ $logs->nextPageUrl() }}"><span class="arrow">›</span></a>
                @else
                    <span class="page-btn disabled"><span class="arrow">›</span></span>
                @endif
            </div>
        </div>

    </div>

</div>
</div>

</body>
</html>
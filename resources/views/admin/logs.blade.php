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
    margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box;
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

/* ================= ACTIVITY LOGS ================= */
.content-wrapper { border-radius: 18px; }

.top-section { display:flex; justify-content:space-between; align-items:flex-end; gap:16px; flex-wrap:wrap; margin-bottom:18px; }
.page-title { font-size:24px; font-weight:700; color:#1e293b; letter-spacing:-0.02em; margin:0; }
.page-subtitle { font-size:13px; color:#6B7280; margin-top:4px; }

/* ---- stat cards ---- */
.cards { display:grid; grid-template-columns:repeat(4,1fr); gap:15px; margin-bottom:20px; }
.card { background:white; border:1px solid #E5E7EB; border-radius:14px; padding:16px 18px; box-shadow:0 4px 14px rgba(15,23,42,.04); }
.card-label { font-size:12px; font-weight:600; color:#6B7280; text-transform:uppercase; letter-spacing:.04em; }
.card-number { font-size:28px; font-weight:700; margin-top:8px; color:#111827; line-height:1; }
.card-note { font-size:11px; color:#9CA3AF; margin-top:6px; }
.card.blue   { background:#EFF6FF; border-color:#BFDBFE; }
.card.green  { background:#ECFDF5; border-color:#A7F3D0; }
.card.purple { background:#FAF5FF; border-color:#E9D5FF; }
.blue-text{color:#2563EB}.green-text{color:#16A34A}.purple-text{color:#9333EA}

/* ---- filter toolbar ---- */
.toolbar { background:white; border:1px solid #E5E7EB; border-radius:14px; padding:14px; margin-bottom:16px; box-shadow:0 4px 14px rgba(15,23,42,.03); }
.toolbar-row { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
.tb-field { display:flex; flex-direction:column; gap:4px; min-width:130px; }
.tb-field.grow { flex:1 1 240px; }
.tb-field label { font-size:11px; font-weight:600; color:#6B7280; text-transform:uppercase; letter-spacing:.04em; }
.tb-field input, .tb-field select { height:38px; border:1px solid #D1D5DB; border-radius:10px; background:white; padding:0 12px; font:inherit; font-size:13px; color:#1F2937; outline:none; box-sizing:border-box; width:100%; }
.tb-field input:focus, .tb-field select:focus { border-color:#2563EB; box-shadow:0 0 0 3px #DBEAFE; }
.search-wrap { position:relative; }
.search-wrap svg { position:absolute; left:11px; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#9CA3AF; pointer-events:none; }
.search-wrap input { padding-left:34px; }
.btn { height:38px; border-radius:10px; padding:0 16px; font:inherit; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; text-decoration:none; box-sizing:border-box; }
.btn-primary { background:#2563EB; color:white; border:none; }
.btn-primary:hover { background:#1D4ED8; }
.btn-ghost { background:white; color:#475569; border:1px solid #CBD5E1; }
.btn-ghost:hover { background:#F8FAFC; border-color:#94A3B8; }
.filter-summary { margin-top:12px; padding-top:12px; border-top:1px dashed #E5E7EB; display:flex; flex-wrap:wrap; gap:8px; align-items:center; font-size:12px; color:#6B7280; }
.chip { background:#EFF6FF; color:#1D4ED8; border:1px solid #BFDBFE; border-radius:999px; padding:3px 10px; font-weight:600; }

/* ---- table (fits the screen: no sideways or inner scrolling) ---- */
.table-box { background:white; border:1px solid #E5E7EB; border-radius:14px; overflow:hidden; box-shadow:0 6px 18px rgba(15,23,42,.05); }
.table-title { padding:16px 20px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #E5E7EB; }
.table-title h2 { margin:0; font-size:17px; font-weight:800; color:#111827; }
.table-title span { font-size:12px; color:#6B7280; }
.table-scroll { overflow:visible; }
table { width:100%; table-layout:fixed; border-collapse:collapse; font-size:13px; }
th { text-align:left; background:#F9FAFB; padding:11px 14px; font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#475569; box-shadow:inset 0 -1px 0 #E5E7EB; }
td { padding:11px 14px; border-bottom:1px solid #F1F5F9; color:#374151; vertical-align:top; overflow-wrap:anywhere; }
tbody tr:hover td { background:#F8FAFC; }
tbody tr:last-child td { border-bottom:0; }

/* column widths add up to 100% */
th:nth-child(1), td:nth-child(1) { width:13%; }  /* date & time  */
th:nth-child(2), td:nth-child(2) { width:18%; }  /* user         */
th:nth-child(3), td:nth-child(3) { width:15%; }  /* action       */
th:nth-child(4), td:nth-child(4) { width:12%; }  /* module       */
th:nth-child(5), td:nth-child(5) { width:29%; }  /* description  */
th:nth-child(6), td:nth-child(6) { width:13%; }  /* type         */

.cell-time .d { font-weight:600; color:#111827; }
.cell-time .t { font-size:12px; color:#6B7280; margin-top:2px; }
.cell-time .ago { display:none; }

.user { display:flex; align-items:center; gap:9px; }
.avatar { width:30px; height:30px; border-radius:50%; color:white; font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.user-name { font-weight:700; color:#111827; line-height:1.25; }
.user-role { font-size:11px; font-weight:600; margin-top:2px; }

.cell-action { font-weight:600; color:#111827; }
.module-tag { display:inline-block; background:#F1F5F9; color:#475569; border-radius:6px; padding:3px 8px; font-size:11.5px; font-weight:600; }
.cell-details { line-height:1.45; }

.badge { display:inline-block; padding:4px 10px; border-radius:999px; font-size:11px; font-weight:700; line-height:1.3; text-align:center; }
.t-create   { background:#DCFCE7; color:#166534; }
.t-update   { background:#F3E8FF; color:#7E22CE; }
.t-delete   { background:#FEE2E2; color:#B91C1C; }
.t-dispense { background:#DBEAFE; color:#1D4ED8; }
.t-login    { background:#E0F2FE; color:#0369A1; }
.t-logout   { background:#F1F5F9; color:#475569; }
.t-approve  { background:#FEF3C7; color:#92400E; }
.t-reject   { background:#FFE4E6; color:#BE123C; }
.t-export   { background:#CCFBF1; color:#0F766E; }
.t-review   { background:#E0E7FF; color:#4338CA; }
.t-other    { background:#F3F4F6; color:#4B5563; }

.empty-state { text-align:center; padding:48px 20px !important; color:#6B7280; }
.empty-state .big { font-size:15px; font-weight:700; color:#374151; margin-bottom:6px; }
.empty-state a { color:#2563EB; font-weight:600; text-decoration:none; }

/* ---- footer / pagination ---- */
.footer { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-top:16px; font-size:13px; color:#6B7280; }
.custom-pagination { display:flex; align-items:center; gap:6px; flex-wrap:wrap; }
.page-btn { min-width:34px; height:34px; padding:0 10px; border-radius:10px; border:1px solid #E5E7EB; background:white; color:#374151; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; font-size:13px; font-weight:600; }
.page-btn:hover { background:#F3F4F6; }
.page-btn.active { background:#2563EB; border-color:#2563EB; color:white; }
.page-btn.disabled { color:#9CA3AF; background:#F9FAFB; pointer-events:none; }
.page-gap { color:#9CA3AF; padding:0 4px; }
.arrow { font-size:18px; font-weight:bold; line-height:1; }

@media (max-width: 1100px) { .cards { grid-template-columns:repeat(2,1fr); } }
@media (max-width: 900px)  { .table-scroll { overflow-x:auto; } table { min-width:760px; } }
@media (max-width: 640px)  { .cards { grid-template-columns:1fr; } .tb-field { min-width:100%; } }
</style>
</head>

<body>

<div class="container">

<x-sidebar />

<div class="main">

    @php
        $authUser = Auth::user();
        $viewerName = $authUser->name ?? session('admin_name') ?? 'Admin';
        $viewerRole = $authUser->role ?? session('admin_role') ?? 'admin';

        $typeClass = fn ($t) => match (strtoupper((string) $t)) {
            'CREATE' => 't-create', 'UPDATE' => 't-update', 'DELETE' => 't-delete',
            'DISPENSE' => 't-dispense', 'LOGIN' => 't-login', 'LOGOUT' => 't-logout',
            'APPROVE' => 't-approve', 'REJECT', 'REJECTED' => 't-reject',
            'EXPORT' => 't-export', 'FORECAST_REVIEW' => 't-review',
            default => 't-other',
        };
        $roleColor = fn ($r) => match (strtolower((string) $r)) {
            'admin' => '#9333EA', 'nurse' => '#10B981', 'doctor' => '#3B82F6', 'bhw' => '#6366F1',
            default => '#6B7280',
        };

        $hasFilters = collect(['search', 'type', 'role', 'module', 'from', 'to'])
            ->contains(fn ($k) => request()->filled($k));
    @endphp

    <div class="header">
        <div>
            <div class="welcome-text">Welcome back,</div>
            <div class="welcome-name">
                {{ $viewerName }}
                <span class="role">{{ $viewerRole }}</span>
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
                <div class="page-subtitle">A record of who did what in the system.</div>
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <div class="card-label">Total Actions</div>
                <div class="card-number">{{ $totalToday ?? 0 }}</div>
                <div class="card-note">Today</div>
            </div>
            <div class="card blue">
                <div class="card-label">Medicine Dispensed</div>
                <div class="card-number blue-text">{{ $dispensedToday ?? 0 }}</div>
                <div class="card-note">Today</div>
            </div>
            <div class="card green">
                <div class="card-label">Records Created</div>
                <div class="card-number green-text">{{ $createdToday ?? 0 }}</div>
                <div class="card-note">Today</div>
            </div>
            <div class="card purple">
                <div class="card-label">Active Users</div>
                <div class="card-number purple-text">{{ $activeUsers ?? 0 }}</div>
                <div class="card-note">Today</div>
            </div>
        </div>

        <form method="GET" action="/admin/logs" class="toolbar" id="logFilters">
            <div class="toolbar-row">
                <div class="tb-field grow">
                    <label for="f-search">Search</label>
                    <div class="search-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input id="f-search" type="text" name="search" placeholder="User, action, module or description..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="tb-field">
                    <label for="f-type">Type</label>
                    <select id="f-type" name="type" data-auto>
                        <option value="">All types</option>
                        @foreach(($types ?? []) as $t)
                            <option value="{{ $t }}" @selected(strcasecmp(request('type', ''), $t) === 0)>{{ ucwords(strtolower(str_replace('_', ' ', $t))) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tb-field">
                    <label for="f-role">Role</label>
                    <select id="f-role" name="role" data-auto>
                        <option value="">All roles</option>
                        @foreach(($roles ?? []) as $r)
                            <option value="{{ $r }}" @selected(strcasecmp(request('role', ''), $r) === 0)>{{ ucfirst(strtolower($r)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tb-field">
                    <label for="f-module">Module</label>
                    <select id="f-module" name="module" data-auto>
                        <option value="">All modules</option>
                        @foreach(($modules ?? []) as $m)
                            <option value="{{ $m }}" @selected(strcasecmp(request('module', ''), $m) === 0)>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tb-field">
                    <label for="f-from">From</label>
                    <input id="f-from" type="date" name="from" value="{{ request('from') }}" max="{{ date('Y-m-d') }}" data-auto>
                </div>

                <div class="tb-field">
                    <label for="f-to">To</label>
                    <input id="f-to" type="date" name="to" value="{{ request('to') }}" max="{{ date('Y-m-d') }}" data-auto>
                </div>

                <button class="btn btn-primary" type="submit">Apply</button>
                @if($hasFilters)
                    <a class="btn btn-ghost" href="/admin/logs">Clear filters</a>
                @endif
            </div>

            @if($hasFilters)
                <div class="filter-summary">
                    <span>Filtered by:</span>
                    @if(request()->filled('search')) <span class="chip">"{{ request('search') }}"</span> @endif
                    @if(request()->filled('type'))   <span class="chip">Type: {{ ucwords(strtolower(str_replace('_', ' ', request('type')))) }}</span> @endif
                    @if(request()->filled('role'))   <span class="chip">Role: {{ ucfirst(strtolower(request('role'))) }}</span> @endif
                    @if(request()->filled('module')) <span class="chip">Module: {{ request('module') }}</span> @endif
                    @if(request()->filled('from'))   <span class="chip">From {{ \Carbon\Carbon::parse(request('from'))->format('M d, Y') }}</span> @endif
                    @if(request()->filled('to'))     <span class="chip">To {{ \Carbon\Carbon::parse(request('to'))->format('M d, Y') }}</span> @endif
                </div>
            @endif
        </form>

        <div class="table-box">
            <div class="table-title">
                <h2>{{ $hasFilters ? 'Search Results' : 'Recent Activity' }}</h2>
                <span>{{ number_format($logs->total()) }} {{ \Illuminate\Support\Str::plural('entry', $logs->total()) }}</span>
            </div>

            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Type</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="cell-time">
                                    <div class="d">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="t">{{ $log->created_at->format('h:i A') }}</div>
                                    <div class="ago">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="user">
                                        <div class="avatar" style="background: {{ $roleColor($log->role) }};">{{ strtoupper(mb_substr($log->user_name ?: '?', 0, 1)) }}</div>
                                        <div>
                                            <div class="user-name">{{ $log->user_name }}</div>
                                            <div class="user-role" style="color: {{ $roleColor($log->role) }};">{{ $log->role ? ucfirst(strtolower($log->role)) : '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="cell-action">{{ $log->action }}</td>
                                <td>
                                    @if($log->module)
                                        <span class="module-tag">{{ $log->module }}</span>
                                    @else
                                        <span style="color:#9CA3AF;">-</span>
                                    @endif
                                </td>
                                <td class="cell-details">{{ $log->details }}</td>
                                <td>
                                    <span class="badge {{ $typeClass($log->type) }}">
                                        {{ ucwords(strtolower(str_replace('_', ' ', $log->type))) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <div class="big">No activity logs found</div>
                                    @if($hasFilters)
                                        Nothing matches your filters. <a href="/admin/logs">Clear filters</a>
                                    @else
                                        Actions taken in the system will appear here.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer">
            <div>
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ number_format($logs->total()) }} activity logs
            </div>

            @php
                $cur  = $logs->currentPage();
                $last = $logs->lastPage();
                $pageList = collect([1, $last])
                    ->merge(range(max(1, $cur - 1), min($last, $cur + 1)))
                    ->unique()->sort()->values();
                $prevPage = 0;
            @endphp

            @if($last > 1)
            <div class="custom-pagination">
                @if ($logs->onFirstPage())
                    <span class="page-btn disabled"><span class="arrow">&lsaquo;</span></span>
                @else
                    <a class="page-btn" href="{{ $logs->previousPageUrl() }}" aria-label="Previous page"><span class="arrow">&lsaquo;</span></a>
                @endif

                @foreach($pageList as $p)
                    @if($prevPage && $p - $prevPage > 1)
                        <span class="page-gap">&hellip;</span>
                    @endif
                    @if($p == $cur)
                        <span class="page-btn active">{{ $p }}</span>
                    @else
                        <a class="page-btn" href="{{ $logs->url($p) }}">{{ $p }}</a>
                    @endif
                    @php $prevPage = $p; @endphp
                @endforeach

                @if ($logs->hasMorePages())
                    <a class="page-btn" href="{{ $logs->nextPageUrl() }}" aria-label="Next page"><span class="arrow">&rsaquo;</span></a>
                @else
                    <span class="page-btn disabled"><span class="arrow">&rsaquo;</span></span>
                @endif
            </div>
            @endif
        </div>

    </div>

</div>
</div>

<script>
    // Dropdowns and dates apply instantly; the search box applies on Enter / Apply.
    document.querySelectorAll('#logFilters [data-auto]').forEach(function (el) {
        el.addEventListener('change', function () { document.getElementById('logFilters').submit(); });
    });
</script>

</body>
</html>
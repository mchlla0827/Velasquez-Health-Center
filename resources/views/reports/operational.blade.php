@extends('reports.report-layout')

@section('content')
<div class="page-title">Operational Report</div>

<form method="GET" class="report-controls">
    <div class="filters">
        <div class="filter-group">
            <label>Time Range</label>
            <select name="range" onchange="document.getElementById('customDateWrap').style.display = (this.value === 'custom') ? 'flex' : 'none';">
                <option value="all" {{ ($selectedRange ?? 'all') == 'all' ? 'selected' : '' }}>All Time</option>
                <option value="month" {{ ($selectedRange ?? '') == 'month' ? 'selected' : '' }}>Current Month</option>
                <option value="7days" {{ ($selectedRange ?? '') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30days" {{ ($selectedRange ?? '') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="custom" {{ ($selectedRange ?? '') == 'custom' ? 'selected' : '' }}>Custom Range</option>
            </select>
        </div>

        <div class="filter-group" id="customDateWrap" style="display: {{ ($selectedRange ?? 'all') == 'custom' ? 'flex' : 'none' }}; gap:14px;">
            <div class="filter-group">
                <label>From</label>
                <input type="date" name="from" value="{{ $fromDate ?? '' }}">
            </div>
            <div class="filter-group">
                <label>To</label>
                <input type="date" name="to" value="{{ $toDate ?? '' }}">
            </div>
        </div>

        <button type="submit" class="btn-primary">Generate</button>
        <button type="button" class="btn-outline" onclick="window.location.href = window.location.pathname">Reset</button>
    </div>
</form>

<div class="content-card">
    <div class="summary-grid">
        <div class="summary-box"><span>Total Consultations</span><h3>{{ $totalConsultations ?? '--' }}</h3></div>
        <div class="summary-box"><span>Active Users (Staff)</span><h3>{{ $activeUsers ?? '--' }}</h3></div>
        <div class="summary-box"><span>Peak Patient Load</span><h3>{{ $peakPatientLoad ?? '--' }}</h3></div>
        <div class="summary-box"><span>System Transactions</span><h3>{{ $systemTransactions ?? '--' }}</h3></div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Activity</th>
                    <th>User</th>
                    <th>Module</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($operations ?? [] as $operation)
                <tr>
                    <td>{{ $operation->date }}</td>
                    <td>{{ $operation->activity }}</td>
                    <td>{{ $operation->user }}</td>
                    <td>{{ $operation->module }}</td>
                    <td>{{ $operation->status }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; padding:20px; color:#6B7280;">No activity recorded for the selected period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
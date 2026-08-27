@extends('reports.report-layout')

@section('content')
<div class="page-title">Medicine Inventory Report</div>

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
        <div class="summary-box"><span>Total Medicines</span><h3>{{ $totalMedicines ?? '--' }}</h3></div>
        <div class="summary-box"><span>Low Stock Items</span><h3>{{ $lowStock ?? '--' }}</h3></div>
        <div class="summary-box"><span>Expiring Soon</span><h3>{{ $expiring ?? '--' }}</h3></div>
        <div class="summary-box"><span>Most Used Medicine</span><h3>{{ $mostUsed ?? '--' }}</h3></div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Expiry Date</th>
                    <th>Usage Level</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines ?? [] as $med)
                <tr>
                    <td>{{ $med->medicine_name }}</td>
                    <td>{{ $med->stock }}</td>
                    <td>
                        <span class="badge">{{ $med->stock <= 10 ? 'Low Stock' : 'Normal' }}</span>
                    </td>
                    <td>{{ $med->expiry_date }}</td>
                    <td>{{ $med->usage_level ?? 'Normal' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; padding:20px; color:#6B7280;">No inventory data available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
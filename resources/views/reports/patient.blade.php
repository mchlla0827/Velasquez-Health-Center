@extends('reports.report-layout')

@section('content')
<div class="page-title">Patient Records Report</div>

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
        <div class="summary-box"><span>Total Patients</span><h3>{{ $totalPatients ?? '--' }}</h3></div>
        <div class="summary-box"><span>New Patients</span><h3>{{ $newPatients ?? '--' }}</h3></div>
        <div class="summary-box"><span>Returning Patients</span><h3>{{ $returningPatients ?? '--' }}</h3></div>
        <div class="summary-box"><span>Most Common Case</span><h3>{{ $commonCase ?? '--' }}</h3></div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Barangay</th>
                    <th>Diagnosis</th>
                    <th>Date Visited</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients ?? [] as $patient)
                <tr>
                    <td>{{ $patient->full_name }}</td>
                    <td>{{ $patient->age }}</td>
                    <td>{{ $patient->gender }}</td>
                    <td>{{ $patient->barangay }}</td>
                    <td>{{ $patient->diagnosis }}</td>
                    <td>{{ $patient->date_visited }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:20px; color:#6B7280;">No records found for the selected period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
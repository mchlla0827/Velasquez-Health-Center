@extends('doctor.reports.report-layout')


@section('content')
<div class="page-title">Operational Reports</div>


<div class="report-controls">
    <div class="filters">
        <div class="filter-group">
            <label>Time Range</label>
            <select>
                <option>Current Month</option>
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Custom Range</option>
            </select>
        </div>


        <div class="filter-group">
            <label>From</label>
            <input type="date">
        </div>


        <div class="filter-group">
            <label>To</label>
            <input type="date">
        </div>


        <button class="btn-primary">Generate</button>
        <button class="btn-outline" onclick="history.back()">Back</button>
    </div>


    <div>
        <button class="btn-outline">Export PDF</button>
        <button class="btn-outline">Export Excel</button>
    </div>
</div>


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
                @endforelse
            </tbody>
        </table>
    </div>
</div>



@endsection

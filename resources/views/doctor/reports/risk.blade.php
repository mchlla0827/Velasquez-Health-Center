@extends('doctor.reports.report-layout')


@section('content')
<div class="page-title">Patient Risk-Scoring Reports</div>


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
        <div class="summary-box"><span>High Risk</span><h3>{{ $highRisk ?? '--' }}</h3></div>
        <div class="summary-box"><span>Moderate Risk</span><h3>{{ $moderateRisk ?? '--' }}</h3></div>
        <div class="summary-box"><span>Low Risk</span><h3>{{ $lowRisk ?? '--' }}</h3></div>
        <div class="summary-box"><span>Total Evaluated</span><h3>{{ $totalRisk ?? '--' }}</h3></div>
    </div>


    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Risk Level</th>
                    <th>Score</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($risks ?? [] as $risk)
                <tr>
                    <td>{{ $risk->full_name }}</td>
                    <td>{{ $risk->age }}</td>
                    <td>{{ $risk->gender }}</td>
                    <td><span class="risk-badge">{{ $risk->risk_level }}</span></td>
                    <td>{{ $risk->score }}</td>
                    <td>
                        @if($risk->risk_level == 'High')
                            Needs Immediate Attention
                        @elseif($risk->risk_level == 'Moderate')
                            Monitor Patient
                        @else
                            Stable
                        @endif
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>



@endsection

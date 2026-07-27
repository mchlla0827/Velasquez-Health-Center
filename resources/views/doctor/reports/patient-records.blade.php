@extends('doctor.reports.report-layout')


@section('content')
<div class="page-title">Patient Records Reports (Summary)</div>


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
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

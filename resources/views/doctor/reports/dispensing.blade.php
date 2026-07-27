
@extends('doctor.reports.report-layout')


@section('content')
<div class="page-title">Dispensing of Medicine Report</div>


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
        <div class="summary-box"><span>Total Dispensed</span><h3>{{ $totalDispensed ?? '--' }}</h3></div>
        <div class="summary-box"><span>Today’s Dispensed</span><h3>{{ $todayDispensed ?? '--' }}</h3></div>
        <div class="summary-box"><span>Most Dispensed Medicine</span><h3>{{ $mostDispensed ?? '--' }}</h3></div>
        <div class="summary-box"><span>Active Patients</span><h3>{{ $activePatients ?? '--' }}</h3></div>
    </div>


    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Date Dispensed</th>
                    <th>Prescribed By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dispensings ?? [] as $dispense)
                <tr>
                    <td>{{ $dispense->patient_name }}</td>
                    <td>{{ $dispense->medicine_name }}</td>
                    <td>{{ $dispense->quantity }}</td>
                    <td>{{ $dispense->date_dispensed }}</td>
                    <td>{{ $dispense->prescribed_by }}</td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>



@endsection

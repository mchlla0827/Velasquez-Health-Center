@extends('doctor.reports.report-layout')


@section('content')
<div class="page-title">Medicine Inventory Report</div>


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
                        <span class="badge">
                            {{ $med->stock <= 10 ? 'Low Stock' : 'Normal' }}
                        </span>
                    </td>
                    <td>{{ $med->expiry_date }}</td>
                    <td>{{ $med->usage_level ?? 'Normal' }}</td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>



@endsection

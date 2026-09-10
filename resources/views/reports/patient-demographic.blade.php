@extends('reports.report-layout')

@section('content')
<div class="page-title">Patient Demographic Report</div>
<p style="color:#6B7280; margin-bottom:16px; font-size:13px;">Age, gender, and barangay distribution of all registered patients.</p>

<form method="GET" class="report-controls">
    <div class="filters">
        <div class="filter-group">
            <label>Time Range</label>
            <select name="range" onchange="document.getElementById('customDateWrap').style.display = (this.value === 'custom') ? 'contents' : 'none';">
                <option value="all" {{ ($selectedRange ?? 'all') == 'all' ? 'selected' : '' }}>All Time</option>
                <option value="month" {{ ($selectedRange ?? '') == 'month' ? 'selected' : '' }}>Current Month</option>
                <option value="7days" {{ ($selectedRange ?? '') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30days" {{ ($selectedRange ?? '') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="custom" {{ ($selectedRange ?? '') == 'custom' ? 'selected' : '' }}>Custom Range</option>
            </select>
        </div>

        <div class="filter-group" id="customDateWrap" style="display: {{ ($selectedRange ?? 'all') == 'custom' ? 'contents' : 'none' }};">
            <div class="filter-group"><label>From</label><input type="date" name="from" value="{{ $fromDate ?? '' }}"></div>
            <div class="filter-group"><label>To</label><input type="date" name="to" value="{{ $toDate ?? '' }}"></div>
        </div>

        <button type="submit" class="btn-primary">Generate</button>
        <button type="button" class="btn-outline" onclick="window.location.href = '/{{ strtolower(Auth::user()->role) }}/reports/patient'">Back</button>
    </div>

    <div style="display:flex; gap:8px;">
        <button type="button" class="btn-outline" onclick="window.print()">Print / PDF</button>
        <a href="{{ route('reports.export.patient-demographic', request()->query()) }}" class="btn-outline" style="text-decoration:none; display:inline-block;">Export Excel</a>
    </div>
</form>

<div class="content-card" id="printableArea">

    <div class="print-only" style="display:none; text-align:center; margin-bottom:20px;">
        <h2 style="margin:0;">Velasquez Health Center</h2>
        <p style="margin:4px 0; font-size:13px;">Patient Demographic Report</p>
        <p style="margin:0; font-size:12px; color:#6B7280;">Generated: {{ date('F d, Y | h:i A') }}</p>
    </div>

    <div class="summary-grid">
        <div class="summary-box"><span>Total Registered Patients</span><h3>{{ $totalPatients ?? '--' }}</h3></div>
        <div class="summary-box"><span>Male</span><h3>{{ $maleCount ?? '--' }}</h3></div>
        <div class="summary-box"><span>Female</span><h3>{{ $femaleCount ?? '--' }}</h3></div>
        <div class="summary-box"><span>New / Returning</span><h3 style="font-size:16px;">{{ $newPatients ?? 0 }} / {{ $returningPatients ?? 0 }}</h3></div>
    </div>

    <h3 style="font-size:15px; margin:24px 0 12px;">Age Group Distribution</h3>
    <div class="table-container" style="margin-bottom:24px;">
        <table>
            <thead>
                <tr>
                    @foreach(($ageGroups ?? []) as $label => $count)
                    <th>{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach(($ageGroups ?? []) as $label => $count)
                    <td>{{ $count }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <h3 style="font-size:15px; margin:24px 0 12px;">Demographic Summary by Barangay</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Barangay</th>
                    <th>Male</th>
                    <th>Female</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangayRows ?? [] as $b)
                <tr>
                    <td>{{ $b->barangay }}</td>
                    <td>{{ $b->male_count }}</td>
                    <td>{{ $b->female_count }}</td>
                    <td><b>{{ $b->total }}</b></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center; padding:20px; color:#6B7280;">No records available for the selected period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printableArea, #printableArea * { visibility: visible; }
    #printableArea { position: absolute; left: 0; top: 0; width: 100%; }
    .print-only { display: block !important; }
    .summary-box { break-inside: avoid; }
    table { font-size: 11px; }
    thead { display: table-header-group; }
}
</style>

<script>
function exportToExcel() {
    let content = 'Velasquez Health Center - Patient Demographic Report\nGenerated: {{ date("F d, Y | h:i A") }}\n\n';
    document.querySelectorAll('#printableArea table').forEach(table => {
        table.querySelectorAll('tr').forEach(tr => {
            const cols = [];
            tr.querySelectorAll('th, td').forEach(cell => {
                cols.push('"' + (cell.innerText || '').replace(/"/g, '""').replace(/\n/g, ' ') + '"');
            });
            content += cols.join('\t') + '\n';
        });
        content += '\n';
    });
    const blob = new Blob([content], { type: 'application/vnd.ms-excel;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'patient-demographic-{{ date("Y-m-d") }}.xls';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
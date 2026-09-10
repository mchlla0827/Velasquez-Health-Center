@extends('reports.report-layout')

@section('content')
<div class="page-title">Medicine Request Report</div>
<p style="color:#6B7280; margin-bottom:16px; font-size:13px;">All medicine restock requests with approval status.</p>

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
        <button type="button" class="btn-outline" onclick="window.location.href = '/{{ strtolower(Auth::user()->role) }}/reports/operational'">Back</button>
    </div>

    <div style="display:flex; gap:8px;">
        <button type="button" class="btn-outline" onclick="window.print()">Print / PDF</button>
        <a href="{{ route('reports.export.medicine-request', request()->query()) }}" class="btn-outline" style="text-decoration:none; display:inline-block;">Export Excel</a>
    </div>
</form>

<div class="content-card" id="printableArea">

    <div class="print-only" style="display:none; text-align:center; margin-bottom:20px;">
        <h2 style="margin:0;">Velasquez Health Center</h2>
        <p style="margin:4px 0; font-size:13px;">Medicine Request Report</p>
        <p style="margin:0; font-size:12px; color:#6B7280;">Generated: {{ date('F d, Y | h:i A') }}</p>
    </div>

    <div class="summary-grid">
        <div class="summary-box"><span>Total Requests</span><h3>{{ $total ?? '--' }}</h3></div>
        <div class="summary-box"><span>Approved</span><h3>{{ $approved ?? '--' }}</h3></div>
        <div class="summary-box"><span>Rejected</span><h3>{{ $rejected ?? '--' }}</h3></div>
        <div class="summary-box"><span>Pending</span><h3>{{ $pending ?? '--' }}</h3></div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Medicine</th>
                    <th>Quantity Requested</th>
                    <th>Request Date</th>
                    <th>Requested By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows ?? [] as $row)
                <tr>
                    <td>#{{ $row->request_id }}</td>
                    <td>{{ $row->medicine_name }}</td>
                    <td>{{ $row->quantity_requested }}</td>
                    <td>{{ $row->request_date }}</td>
                    <td>{{ $row->requested_by }}</td>
                    <td>
                        @php
                            $badgeStyle = match($row->status) {
                                'Approved' => 'background:#DCFCE7; color:#166534;',
                                'Rejected' => 'background:#FEE2E2; color:#991B1B;',
                                default => 'background:#FEF3C7; color:#92400E;',
                            };
                        @endphp
                        <span style="{{ $badgeStyle }} padding:3px 8px; border-radius:12px; font-size:11px; font-weight:bold;">
                            {{ strtoupper($row->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:20px; color:#6B7280;">No records available for the selected period.</td></tr>
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
    const table = document.querySelector('#printableArea table');
    if (!table) return;
    const rows = [];
    table.querySelectorAll('tr').forEach(tr => {
        const cols = [];
        tr.querySelectorAll('th, td').forEach(cell => {
            cols.push('"' + (cell.innerText || '').replace(/"/g, '""').replace(/\n/g, ' ') + '"');
        });
        rows.push(cols.join('\t'));
    });
    const header = 'Velasquez Health Center - Medicine Request Report\nGenerated: {{ date("F d, Y | h:i A") }}\n\n';
    const content = header + rows.join('\n');
    const blob = new Blob([content], { type: 'application/vnd.ms-excel;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'medicine-request-report-{{ date("Y-m-d") }}.xls';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
@extends('reports.report-layout')

@section('content')
<div class="page-title">Patient Risk Assessment Report</div>
<p style="color:#6B7280; margin-bottom:16px; font-size:13px;">System-generated risk assessments based on patient triage records.</p>

<div style="background:#FFFBEB; border:1px solid #FDE68A; color:#92400E; padding:12px 16px; border-radius:8px; font-size:12px; margin-bottom:16px;">
    <b>Note:</b> This report reflects a rule-based risk assessment intended to assist health personnel in prioritizing care. It does not replace professional medical diagnosis or clinical judgment.
</div>

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
        <button type="button" class="btn-outline" onclick="window.location.href = '/{{ strtolower(Auth::user()->role) }}/reports'">Back</button>
    </div>

    <div style="display:flex; gap:8px;">
        <button type="button" class="btn-outline" onclick="window.print()">Print / PDF</button>
        <button type="button" class="btn-outline" onclick="exportToExcel()">Export Excel</button>
    </div>
</form>

<div class="content-card" id="printableArea">

    <div class="print-only" style="display:none; text-align:center; margin-bottom:20px;">
        <h2 style="margin:0;">Velasquez Health Center</h2>
        <p style="margin:4px 0; font-size:13px;">Patient Risk Assessment Report</p>
        <p style="margin:0; font-size:11px; color:#92400E;">System-generated risk assessment - not a substitute for professional medical diagnosis</p>
        <p style="margin:4px 0 0; font-size:12px; color:#6B7280;">Generated: {{ date('F d, Y | h:i A') }}</p>
    </div>

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
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Assessment Date</th>
                    <th>Risk Level</th>
                    <th>Assessment Result</th>
                    <th>Recommendation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($risks ?? [] as $risk)
                <tr>
                    <td>{{ $risk->patient_id }}</td>
                    <td>{{ $risk->full_name }}</td>
                    <td>{{ $risk->age }}</td>
                    <td>{{ $risk->assessment_date }}</td>
                    <td>
                        @php
                            $badgeStyle = match($risk->risk_level) {
                                'High' => 'background:#FEE2E2; color:#991B1B;',
                                'Moderate' => 'background:#FEF3C7; color:#92400E;',
                                default => 'background:#DCFCE7; color:#166534;',
                            };
                        @endphp
                        <span style="{{ $badgeStyle }} padding:3px 8px; border-radius:12px; font-size:11px; font-weight:bold;">
                            {{ strtoupper($risk->risk_level) }}
                        </span>
                    </td>
                    <td>{{ $risk->assessment_result }}</td>
                    <td>{{ $risk->recommendation }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:20px; color:#6B7280;">No records available for the selected period.</td></tr>
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
    const header = 'Velasquez Health Center - Patient Risk Assessment Report\nSystem-generated risk assessment - not a substitute for professional medical diagnosis\nGenerated: {{ date("F d, Y | h:i A") }}\n\n';
    const content = header + rows.join('\n');
    const blob = new Blob([content], { type: 'application/vnd.ms-excel;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'patient-risk-assessment-{{ date("Y-m-d") }}.xls';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
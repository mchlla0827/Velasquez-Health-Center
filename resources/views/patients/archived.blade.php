<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Patients</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.css">
    <style>
        :root {
            --primary: #1A73E8; --bg: #F9FAFB; --line: #E5E7EB; --line-strong: #D1D5DB;
            --text: #111827; --text-muted: #6B7280;
        }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background: var(--bg); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13.5px; color: var(--text); }

        .content-wrap { padding: 24px 32px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
        .welcome-text { font-size: 13px; color: var(--text-muted); }
        .welcome-name { font-size: 16px; font-weight: 700; color: var(--text); margin-top: 2px; }
        .role-badge { display: inline-block; background: #10B981; color: #fff; font-size: 10.5px; font-weight: 700; padding: 2px 9px; border-radius: 999px; margin-left: 8px; vertical-align: middle; }
        .clinic-info { text-align: right; font-size: 12.5px; color: var(--text-muted); }
        .clinic-info b { color: var(--text); }
        .header-divider { border-bottom: 1px solid var(--line); margin-bottom: 20px; }

        .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        .page-title { font-size: 20px; font-weight: 700; color: var(--text); margin: 0; }
        .page-subtitle { font-size: 12.5px; color: var(--text-muted); margin-top: 4px; }

        .btn-secondary { padding: 9px 18px; background: #fff; color: var(--text-muted); border: 1px solid var(--line-strong); border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-secondary:hover { background: #F3F4F6; }
        .btn-primary { padding: 9px 18px; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; }
        .btn-primary:hover { background: #1557B0; }

        .table-card { background: #fff; border: 1px solid var(--line-strong); border-radius: 10px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; color: var(--text-muted); padding: 12px; background: #F9FAFB; border-bottom: 1px solid var(--line); text-transform: uppercase; }
        td { padding: 14px 12px; border-bottom: 1px solid #F1F5F9; font-size: 13px; color: #374151; }
        tr:last-child td { border-bottom: none; }

        .archived-badge { display: inline-block; background: #F3F4F6; color: #4B5563; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px; }
        .text-empty { text-align: center; padding: 50px 20px; color: #9CA3AF; }

        /* Reactivation confirmation modal */
        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-backdrop.show { display: flex; }
        .modal-box { background: #fff; width: 95%; max-width: 460px; border-radius: 12px; overflow: hidden; }
        .modal-header { padding: 18px 22px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; }
        .modal-header h3 { margin: 0; font-size: 16px; color: var(--text); }
        .modal-close { background: none; border: none; font-size: 22px; cursor: pointer; color: #9CA3AF; }
        .modal-body { padding: 20px 22px; }
        .modal-info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #F3F4F6; font-size: 13px; }
        .modal-info-row:last-child { border-bottom: none; }
        .modal-info-row .k { color: var(--text-muted); }
        .modal-info-row .v { font-weight: 600; color: var(--text); }
        .modal-footer { padding: 14px 22px; border-top: 1px solid var(--line); display: flex; justify-content: flex-end; gap: 10px; }
    </style>
</head>
<body>

<div class="content-wrap">

    <div class="header">
        <div>
            <div class="welcome-text">Welcome back,</div>
            <div class="welcome-name">{{ auth()->user()->name ?? ucfirst($role) }}<span class="role-badge">{{ strtoupper($role) }}</span></div>
        </div>
        <div class="clinic-info">
            <b>Velasquez Health Center</b><br>
            @php date_default_timezone_set('Asia/Manila'); @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>
    <div class="header-divider"></div>

    @if (session('success'))
        <div style="margin: 0 0 20px; padding: 14px 18px; background: #DCFCE7; border: 1px solid #86EFAC; color: #166534; border-radius: 6px; font-size: 13.5px;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="margin: 0 0 20px; padding: 14px 18px; background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; border-radius: 6px; font-size: 13.5px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="top-actions">
        <div>
            <h1 class="page-title">Archived Patients</h1>
            <div class="page-subtitle">Patients with no recorded activity for 5+ years. Complete history is preserved.</div>
        </div>
        <a href="{{ route($role . '.patient-records') }}" class="btn-secondary">&larr; Back to Patient Records</a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Patient ID</th>
                    <th>Last Activity</th>
                    <th>Archived On</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td style="font-weight:600; color:#111827;">{{ $patient->last_name }}, {{ $patient->first_name }}</td>
                        <td>{{ $patient->patient_id }}</td>
                        <td>{{ optional($patient->latestActivityDate())->format('M d, Y') ?? '-' }}</td>
                        <td>{{ optional($patient->archived_at)->format('M d, Y') ?? '-' }}</td>
                        <td><span class="archived-badge">Archived</span></td>
                        <td>
                            <button type="button" class="btn-secondary" style="padding:6px 14px; font-size:12.5px;"
                                onclick="openReactivateModal({{ $patient->id }}, '{{ addslashes($patient->last_name . ', ' . $patient->first_name) }}', '{{ optional($patient->latestActivityDate())->format('M d, Y') ?? 'Never' }}')">
                                Restore / Reactivate
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-empty">No archived patients.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- ========== REACTIVATION CONFIRMATION MODAL ========== -->
<div id="reactivateModal" class="modal-backdrop">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Reactivate Patient?</h3>
            <button type="button" class="modal-close" onclick="closeReactivateModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-info-row"><span class="k">Patient</span><span class="v" id="reactivatePatientName">-</span></div>
            <div class="modal-info-row"><span class="k">Last Recorded Activity</span><span class="v" id="reactivatePatientActivity">-</span></div>
            <div class="modal-info-row"><span class="k">Current Status</span><span class="v">Archived</span></div>
            <div class="modal-info-row"><span class="k">Reason</span><span class="v">Archived due to 5 years of inactivity</span></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeReactivateModal()">Cancel</button>
            <form id="reactivateForm" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-primary">Reactivate Patient</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openReactivateModal(patientId, patientName, lastActivity) {
        document.getElementById('reactivatePatientName').textContent = patientName;
        document.getElementById('reactivatePatientActivity').textContent = lastActivity;
        document.getElementById('reactivateForm').action = `/patients/${patientId}/reactivate`;
        document.getElementById('reactivateModal').classList.add('show');
    }
    function closeReactivateModal() {
        document.getElementById('reactivateModal').classList.remove('show');
    }
</script>

</body>
</html>
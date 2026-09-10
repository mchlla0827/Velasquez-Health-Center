<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Management</title>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.min.css">
    <style>
        :root { --primary: #1A73E8; --bg: #F9FAFB; --line: #E5E7EB; --line-strong: #D1D5DB; --text: #111827; --text-muted: #6B7280; }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background: var(--bg); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13.5px; color: var(--text); }
        .content-wrap { padding: 24px 32px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; }
        .welcome-text { font-size: 13px; color: var(--text-muted); }
        .welcome-name { font-size: 16px; font-weight: 700; color: var(--text); margin-top: 2px; }
        .role-badge { display: inline-block; background: #9333EA; color: #fff; font-size: 10.5px; font-weight: 700; padding: 2px 9px; border-radius: 999px; margin-left: 8px; vertical-align: middle; }
        .clinic-info { text-align: right; font-size: 12.5px; color: var(--text-muted); }
        .clinic-info b { color: var(--text); }
        .header-divider { border-bottom: 1px solid var(--line); margin-bottom: 20px; }
        .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        .page-title { font-size: 20px; font-weight: 700; color: var(--text); margin: 0; }
        .page-subtitle { font-size: 12.5px; color: var(--text-muted); margin-top: 4px; }
        .btn-primary { padding: 9px 18px; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; }
        .btn-primary:hover { background: #1557B0; }
        .btn-secondary { padding: 7px 14px; background: #fff; color: var(--text-muted); border: 1px solid var(--line-strong); border-radius: 6px; font-weight: 600; font-size: 12.5px; cursor: pointer; }
        .btn-secondary:hover { background: #F3F4F6; }
        .table-card { background: #fff; border: 1px solid var(--line-strong); border-radius: 10px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; color: var(--text-muted); padding: 12px; background: #F9FAFB; border-bottom: 1px solid var(--line); text-transform: uppercase; }
        td { padding: 14px 12px; border-bottom: 1px solid #F1F5F9; font-size: 13px; color: #374151; }
        tr:last-child td { border-bottom: none; }
        .status-pill { display: inline-block; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 999px; }
        .status-active { background: #DCFCE7; color: #166534; }
        .status-inactive { background: #F3F4F6; color: #4B5563; }
        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-backdrop.show { display: flex; }
        .modal-box { background: #fff; width: 95%; max-width: 420px; border-radius: 12px; overflow: hidden; }
        .modal-header { padding: 18px 22px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; }
        .modal-header h3 { margin: 0; font-size: 16px; color: var(--text); }
        .modal-close { background: none; border: none; font-size: 22px; cursor: pointer; color: #9CA3AF; }
        .modal-body { padding: 20px 22px; }
        .modal-body label { font-size: 12px; font-weight: 600; color: #374151; display: block; margin-bottom: 6px; }
        .modal-body input { width: 100%; padding: 10px 12px; border: 1px solid var(--line-strong); border-radius: 6px; font-size: 13.5px; }
        .modal-footer { padding: 14px 22px; border-top: 1px solid var(--line); display: flex; justify-content: flex-end; gap: 10px; }
    </style>
</head>
<body>

<div class="content-wrap">
    <div class="header">
        <div>
            <div class="welcome-text">Welcome back,</div>
            <div class="welcome-name">{{ auth()->user()->name }}<span class="role-badge">ADMIN</span></div>
        </div>
        <div class="clinic-info">
            <b>Velasquez Health Center</b><br>
            @php date_default_timezone_set('Asia/Manila'); @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>
    <div class="header-divider"></div>

    @if (session('success'))
        <div style="margin: 0 0 20px; padding: 14px 18px; background: #DCFCE7; border: 1px solid #86EFAC; color: #166534; border-radius: 6px; font-size: 13.5px;">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div style="margin: 0 0 20px; padding: 14px 18px; background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; border-radius: 6px; font-size: 13.5px;">{{ $errors->first() }}</div>
    @endif

    <div class="top-actions">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary" style="text-decoration:none; display:inline-block; margin-bottom:12px;">&larr; Back to Dashboard</a>
            <h1 class="page-title">Barangay Management</h1>
            <div class="page-subtitle">Manage the barangays available for patient registration. Archived barangays are hidden from new registrations but existing records are unaffected.</div>
        </div>
        <button type="button" class="btn-primary" onclick="openAddModal()">+ Add Barangay</button>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Barangay Name</th>
                    <th>Status</th>
                    <th>Date Added</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangays as $b)
                    <tr>
                        <td style="font-weight:600; color:#111827;">{{ $b->name }}</td>
                        <td>
                            <span class="status-pill {{ $b->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ ucfirst($b->status) }}
                            </span>
                        </td>
                        <td>{{ $b->created_at->format('M d, Y') }}</td>
                        <td style="display:flex; gap:8px;">
                            <button type="button" class="btn-secondary" onclick="openEditModal({{ $b->id }}, '{{ addslashes($b->name) }}')">Edit</button>
                            <form method="POST" action="{{ route('barangays.toggle', $b->id) }}" onsubmit="return confirm('{{ $b->status === 'active' ? 'Archive' : 'Reactivate' }} this barangay?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-secondary">{{ $b->status === 'active' ? 'Archive' : 'Reactivate' }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center; padding:40px; color:#9CA3AF;">No barangays added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ADD MODAL -->
<div id="addModal" class="modal-backdrop">
    <div class="modal-box">
        <form method="POST" action="{{ route('barangays.store') }}">
            @csrf
            <div class="modal-header">
                <h3>Add Barangay</h3>
                <button type="button" class="modal-close" onclick="closeModal('addModal')">&times;</button>
            </div>
            <div class="modal-body">
                <label>Barangay Name</label>
                <input type="text" name="name" placeholder="e.g. Barangay 105" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="btn-primary">Add Barangay</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal-backdrop">
    <div class="modal-box">
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3>Edit Barangay</h3>
                <button type="button" class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <label>Barangay Name</label>
                <input type="text" name="name" id="editNameInput" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() { document.getElementById('addModal').classList.add('show'); }
    function openEditModal(id, name) {
        document.getElementById('editForm').action = `/barangays/${id}`;
        document.getElementById('editNameInput').value = name;
        document.getElementById('editModal').classList.add('show');
    }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }
</script>

</body>
</html>
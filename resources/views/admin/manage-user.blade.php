<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage User — Velasquez Health Center</title>

    <style>
        /* ========== BASE ========== */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            overflow-x: hidden;
        }
        .container { display: flex; min-height: 100vh; }

        /* ========== MAIN LAYOUT ========== */
        .main {
            margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box;
        }

        /* ========== HEADER — STANDARDIZED ========== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .welcome-text { font-size: 14px; color: #374151; margin-bottom: 5px; }
        .welcome-name {
            font-weight: bold;
            color: #1F2937;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ========== ROLE COLORS — YOUR EXACT VALUES ========== */
        .role {
            color: white;
            padding: 3px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-admin  { background: #9333EA; } /* Purple */
        .role-nurse  { background: #10B981; } /* Green */
        .role-doctor { background: #3B82F6; } /* Blue */
        .role-bhw    { background: #6366F1; } /* Indigo */

        .header-right {
            text-align: right;
            font-size: 12px;
            color: #374151;
            line-height: 1.5;
        }
        .header-divider {
            width: 100%;
            height: 1px;
            background: #E5E7EB;
            margin: 16px 0;
        }
        .page-title {
            font-size: 22px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 24px;
        }

        /* ========== KPI CARDS ========== */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }
        .card-link { text-decoration: none; color: inherit; }
        .card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 18px 20px;
            border: 1px solid #E5E7EB;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.06);
        }
        .card.total       { background: #F9FAFB; border-color: #E5E7EB; }
        .card.admin       { background: #F3E8FF; border-color: #D8B4FE; }
        .card.worker      { background: #E0F2FE; border-color: #BAE6FD; }
        .card.active-users { background: #DCFCE7; border-color: #BBF7D0; }
        .card-title { font-size: 13px; color: #4B5563; margin-bottom: 6px; font-weight: 500; }
        .card-value { font-size: 28px; font-weight: 700; line-height: 1; }

        /* ========== SEARCH ========== */
        .search-row { margin: 20px 0 16px; }
        .search-box {
            display: flex;
            align-items: center;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 10px 14px;
            background: white;
            max-width: 360px;
        }
        .search-box input {
            border: none;
            outline: none;
            padding: 0 8px;
            width: 100%;
            font-size: 14px;
        }
        .search-icon { width: 18px; opacity: 0.5; }

        /* ========== TABLE ========== */
        .table-container {
            background: white;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            overflow: hidden;
        }
        .user-table { width: 100%; border-collapse: collapse; }
        .user-table th {
            padding: 14px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #6B7280;
            text-align: left;
            background: #F9FAFB;
            border-bottom: 2px solid #E5E7EB;
        }
        .user-table td {
            padding: 14px 16px;
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #F3F4F6;
        }
        .user-table tr:hover { background: #FAFAFA; }

        /* ========== ROLE BADGES — MATCHING COLORS ========== */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }
        .badge-admin  { background: #9333EA; }
        .badge-doctor { background: #3B82F6; }
        .badge-nurse  { background: #10B981; }
        .badge-bhw    { background: #6366F1; }

        .active-badge  { background: #DCFCE7; color: #166534; }
        .inactive-badge{ background: #FEE2E2; color: #B91C1C; }

        /* ========== ACTIONS ========== */
        .action-group { display: flex; gap: 10px; }
        .action-img {
            width: 20px;
            height: 20px;
            cursor: pointer;
            transition: transform 0.15s;
        }
        .action-img:hover { transform: scale(1.15); }

        /* ========== MODAL ========== */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            width: 90%;
            max-width: 560px;
            max-height: 90vh;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h3 { margin: 0; font-size: 17px; font-weight: 600; }
        .close-btn {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #9CA3AF;
            padding: 0;
            line-height: 1;
        }
        .close-btn:hover { color: #374151; }

        .modal-body { padding: 20px; overflow-y: auto; }
        .modal-section-label {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            display: block;
            margin-bottom: 12px;
            padding-bottom: 4px;
            border-bottom: 1px solid #F3F4F6;
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
        .form-group { margin-bottom: 14px; }
        .form-group label {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            display: block;
            margin-bottom: 6px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border 0.2s;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
        }
        .input-readonly { background: #F9FAFB; color: #6B7280; cursor: not-allowed; }

        .role-info-box {
            margin-top: 8px;
            padding: 12px;
            background: #F9FAFB;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            font-size: 12px;
            color: #6B7280;
            line-height: 1.4;
        }
        .current-role-label { font-weight: 600; display: block; margin-bottom: 3px; }

        .reset-section {
            border-top: 1px solid #E5E7EB;
            padding-top: 18px;
            margin-top: 8px;
        }
        .btn-reset {
            background: #FEF9C3;
            color: #854D0E;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-reset:hover { background: #FEF08A; }
        .reset-help { font-size: 12px; color: #6B7280; margin: 6px 0 0; }

        .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid #E5E7EB;
            background: #F9FAFB;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn-cancel {
            background: white;
            border: 1px solid #D1D5DB;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn-cancel:hover { background: #F3F4F6; }
        .btn-save {
            background: #3B82F6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-save:hover { background: #2563EB; }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 900px) {
            .cards-grid { grid-template-columns: repeat(2, 1fr); }
            .form-row { grid-template-columns: 1fr; }
        }
        @media (max-width: 520px) {
            .cards-grid { grid-template-columns: 1fr; }
            .search-box { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- ✅ STANDARD SIDEBAR COMPONENT -->
    <x-sidebar />

    <div class="main">

        <!-- ✅ STANDARD HEADER WITH DYNAMIC ROLE COLORS -->
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    @php
                        $userName = session('admin_name') ?? session('user_name') ?? Auth::user()->name ?? 'User';
                        $role = strtolower(session('admin_role') ?? session('user_role') ?? Auth::user()->role ?? 'admin');
                        $displayRole = strtoupper($role);
                        $roleClass = match($role) {
                            'admin'  => 'role-admin',
                            'doctor' => 'role-doctor',
                            'nurse'  => 'role-nurse',
                            'bhw'    => 'role-bhw',
                            default  => 'role-bhw',
                        };
                    @endphp
                    {{ $userName }}
                    <span class="role {{ $roleClass }}">{{ $displayRole }}</span>
                </div>
            </div>
            <div class="header-right">
                <b>Velasquez Health Center</b><br>
                @php date_default_timezone_set('Asia/Manila'); @endphp
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="header-divider"></div>
        <div class="page-title">Manage User</div>

        <!-- KPI CARDS -->
        <div class="cards-grid">
            <a href="{{ route('admin.manage-user') }}" class="card-link">
                <div class="card total">
                    <div class="card-title">Total Users</div>
                    <div class="card-value">{{ $totalUsers }}</div>
                </div>
            </a>
            <a href="{{ route('admin.manage-user', ['role' => 'Admin']) }}" class="card-link">
                <div class="card admin">
                    <div class="card-title">Admins</div>
                    <div class="card-value">{{ $adminCount }}</div>
                </div>
            </a>
            <a href="{{ route('admin.manage-user', ['role' => 'BHW']) }}" class="card-link">
                <div class="card worker">
                    <div class="card-title">Health Workers</div>
                    <div class="card-value">{{ $workerCount }}</div>
                </div>
            </a>
            <a href="{{ route('admin.manage-user', ['status' => 'active']) }}" class="card-link">
                <div class="card active-users">
                    <div class="card-title">Active Users</div>
                    <div class="card-value">{{ $activeCount }}</div>
                </div>
            </a>
        </div>

        <!-- SEARCH -->
        <div class="search-row">
            <div class="search-box">
                <img src="/icons/search.png" class="search-icon" alt="Search">
                <input type="text" id="userSearch" placeholder="Search by name or email...">
            </div>
        </div>

        <!-- USER TABLE WITH COLORED ROLE BADGES -->
        <div class="table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_number ?? 'N/A' }}</td>
                        <td>
                            @php
                                $uRole = strtolower($user->role);
                                $badgeClass = match($uRole) {
                                    'admin'  => 'badge-admin',
                                    'doctor' => 'badge-doctor',
                                    'nurse'  => 'badge-nurse',
                                    'bhw'    => 'badge-bhw',
                                    default  => 'badge-bhw'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $user->role }}</span>
                        </td>

                        <td>
                            <div class="action-group">
                                <img src="/icons/edit.png" class="action-img" title="Edit"
                                    onclick="openEditModal({
                                        id: '{{ $user->id }}',
                                        name: '{{ addslashes($user->name) }}',
                                        contact: '{{ $user->contact_number ?? '' }}',
                                        email: '{{ $user->email }}',
                                        role: '{{ $user->role }}'
                                    })">
                                <img src="/icons/Remove.png" class="action-img" title="Delete"
                                    onclick="confirmDelete('{{ $user->id }}')">
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- EDIT USER MODAL -->
<div id="editUserModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit User Account</h3>
            <button onclick="closeEditModal()" class="close-btn">&times;</button>
        </div>

        <form id="editUserForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="modal-body">
                <span class="modal-section-label">Personal Information</span>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" id="editFirstName" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" id="editLastName" name="last_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" id="editContact" name="contact_number">
                </div>

                <span class="modal-section-label">Account Information</span>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="editEmail" name="email" class="input-readonly" readonly>
                </div>

                <div class="form-group">
                    <label>User Role</label>
                    <select id="editRole" name="role">
                        <option value="Admin">Admin</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Nurse">Nurse</option>
                        <option value="BHW">Barangay Health Worker</option>
                    </select>
                    <div class="role-info-box">
                        <span class="current-role-label">Current: <span id="currentRoleText">—</span></span>
                        <span id="rolePermissions">Standard system access</span>
                    </div>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
// ========== SEARCH ==========
document.getElementById('userSearch').addEventListener('keyup', function() {
    const value = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.user-table tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = !value || text.includes(value) ? '' : 'none';
    });
});

// ========== MODAL ==========
function openEditModal(userData) {
    const nameParts = (userData.name || '').trim().split(' ');
    const firstName = nameParts.shift() || '';
    const lastName = nameParts.join(' ');
    document.getElementById('editFirstName').value = firstName;
    document.getElementById('editLastName').value = lastName;
    document.getElementById('editContact').value = userData.contact || '';
    document.getElementById('editEmail').value = userData.email || '';
    document.getElementById('editRole').value = userData.role;
    document.getElementById('currentRoleText').innerText = userData.role;
    updateRoleInfo(userData.role);
    document.getElementById('editUserForm').action = `/admin/users/update/${userData.id}`;
    document.getElementById('editUserModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editUserModal').style.display = 'none';
}

function updateRoleInfo(role) {
    const info = document.getElementById('rolePermissions');
    const roles = {
        'Admin': 'Full access — manage users, approve requests, view all reports',
        'Doctor': 'Medical access — view patient history, manage triage, medical reports',
        'Nurse': 'Clinical access — register patients, update vitals, view records',
        'BHW': 'Basic access — inventory viewing and patient registration assistance'
    };
    info.textContent = roles[role] || 'Standard system access';
}

document.getElementById('editRole').addEventListener('change', function() {
    updateRoleInfo(this.value);
});

// ========== ACTIONS ==========
function sendResetEmail() {
    Swal.fire({
        title: 'Send Reset Link?',
        text: 'A password reset link will be sent to this user\'s email address.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Send It',
        confirmButtonColor: '#3B82F6'
    });
}

function confirmDelete(userId) {
    Swal.fire({
        title: 'Delete This User?',
        text: 'This user will be permanently removed and cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Delete User'
    }).then(result => {
        if (result.isConfirmed) {
            window.location.href = `/admin/users/delete/${userId}`;
        }
    });
}
</script>

</body>
</html>
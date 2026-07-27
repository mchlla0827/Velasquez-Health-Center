<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Manage User</title>

<style>
    /* ================= BASE STYLES ================= */
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #F9FAFB;
        overflow-x: hidden;
    }
    .container { display: flex; }

    /* ================= SIDEBAR ================= */
    .sidebar {
        width: 260px;
        height: 100vh;
        background: white;
        border-right: 1px solid #E5E7EB;
        padding: 24px;
        position: fixed;
        top: 0;
        left: 0;
        box-sizing: border-box;
        overflow-y: auto;
    }
    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #E5E7EB;
        margin-bottom: 10px;
    }
    .logo { width: 40px; height: 40px; border-radius: 50%; }
    .brand-wrapper { display: flex; flex-direction: column; line-height: 1.2; }
    .brand { font-weight: bold; color: #1E3A8A; font-size: 13.3px; }
    .sub { font-size: 11px; color: #6B7280; }
    .group {
        margin-top: 22px;
        font-size: 11px;
        font-weight: bold;
        color: #9CA3AF;
        text-transform: uppercase;
    }
    .nav-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 10px;
        margin-top: 6px;
        text-decoration: none;
        color: #5f6570;
        border-radius: 6px;
        font-size: 14px;
    }
    .nav-icon { width: 22px; height: 22px; object-fit: contain; }
    .nav-item.active {
        background: #EFF6FF;
        color: #1A73E8;
        border-left: 4px solid #1A73E8;
        font-weight: bold;
    }
    .nav-item:hover { background: #F3F4F6; }

    /* ================= MAIN CONTENT ================= */
    .main {
        margin-left: 250px;
        width: calc(100% - 260px);
        padding: 24px;
        box-sizing: border-box;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .welcome-text { font-size: 14px; color: #374151; margin-bottom: 5px; }
    .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }
    .role {
        background: #9333EA;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
        text-transform: uppercase;
    }
    .right { text-align: right; font-size: 12px; color: #374151; }
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
        margin-bottom: 16px;
    }

    /* ================= CARDS ================= */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }
    .card-link { text-decoration: none; color: inherit; }
    .card {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.08);
        min-height: 70px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .card.total { border: 1px solid #D1D5DB; }
    .card.admin { background: #F3E8FF; border: 1px solid #D8B4FE; }
    .card.worker { background: #E0F2FE; border: 1px solid #BAE6FD; }
    .card.active-users-card { background: #DCFCE7; border: 1px solid #BBF7D0; }
    .card-title { font-size: 14px; color: #4B5563; margin-bottom: 8px; font-weight: 500; }
    .card-value { font-size: 30px; font-weight: 700; line-height: 1; }

    /* ================= TABLE & SEARCH ================= */
    .search-row { margin: 16px 0; }
    .search-box {
        display: flex;
        align-items: center;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 7px 20px;
        background: white;
    }
    .search-box input {
        border: none;
        outline: none;
        padding: 10px;
        width: 100%;
        font-size: 14px;
    }
    .search-icon { width: 18px; opacity: 0.6; }

    .table-container {
        background: white;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }
    .user-table { width: 100%; border-collapse: collapse; }
    .user-table th {
        padding: 18px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #6B7280;
        text-align: left;
        background: #F9FAFB;
        border-bottom: 2px solid #E5E7EB;
    }
    .user-table td {
        padding: 18px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #F3F4F6;
    }
    .badge {
        display: inline-flex;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }
    .admin-badge { background: #F3E8FF; color: #4f1981; }
    .worker-badge { background: #E0F2FE; color: #0a3d55; }
    .active-badge { background: #DCFCE7; color: #0b381c; }
    .inactive-badge { background: #FEE2E2; color: #9c1c1c; }

    .action-group { display: flex; gap: 12px; }
    .action-img { width: 20px; height: 20px; cursor: pointer; }

    /* ================= MODAL STYLES ================= */
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
        max-width: 600px;
        max-height: 95vh;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { margin: 0; font-size: 18px; color: #111827; }
    .close-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: #9CA3AF; }
    
    .modal-body { padding: 24px; overflow-y: auto; }
    .modal-section-label { font-size: 14px; color: #111827; font-weight: bold; display: block; margin-bottom: 12px; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { font-size: 13px; color: #374151; font-weight: 500; display: block; margin-bottom: 6px; }
    .form-group input, .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
    }
    .input-readonly { background-color: #F9FAFB; color: #6B7280; }

    .role-info-box {
        margin-top: 8px;
        padding: 12px;
        background: #F9FAFB;
        border-radius: 6px;
        border: 1px solid #E5E7EB;
        font-size: 12px;
        color: #6B7280;
    }
    .current-role-label { font-weight: 600; display: block; margin-bottom: 4px; }

    .reset-section { border-top: 1px solid #E5E7EB; padding-top: 20px; margin-top: 10px; }
    .btn-reset {
        background: #FEF9C3;
        color: #854D0E;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
    }
    .reset-help { font-size: 12px; color: #6B7280; margin: 8px 0 0; }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #E5E7EB;
        background: #F9FAFB;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .btn-cancel { background: white; border: 1px solid #D1D5DB; padding: 10px 20px; border-radius: 8px; cursor: pointer; }
    .btn-save { background: #2563EB; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; }

</style>
</head>

<body>

<div class="container">
<x-sidebar />

    <div class="main">
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ session('admin_name') }}
                    <span class="role">{{ session('admin_role') }}</span>
                </div>
            </div>
            <div class="right">
                <b>Velasquez Health Center</b><br>
                @php date_default_timezone_set('Asia/Manila'); @endphp
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="header-divider"></div>
        <div class="page-title">Manage User</div>

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
                <div class="card active-users-card">
                    <div class="card-title">Active Users</div>
                    <div class="card-value">{{ $activeCount }}</div>
                </div>
            </a>
        </div>

        <div class="search-row">
            <div class="search-box">
                <img src="/icons/search.png" class="search-icon">
                <input type="text" placeholder="Search by name or email...">
            </div>
        </div>

        <div class="table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>CONTACT</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>LAST UPDATED</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_number ?? 'N/A' }}</td>
                        <td><span class="badge {{ strtolower($user->role) == 'admin' ? 'admin-badge' : 'worker-badge' }}">{{ $user->role }}</span></td>
                        <td>
                            @if($user->updated_at >= now()->subMinutes(30))
                                <span class="badge active-badge">Active</span>
                            @else
                                <span class="badge inactive-badge">Offline</span>
                            @endif
                        </td>
                        <td>{{ $user->updated_at->format('M d, Y | h:i A') }}</td>
                        <td>
                            <div class="action-group">
                                <img src="/icons/edit.png" class="action-img" title="Edit" 
                                     onclick="openEditModal({
                                        id: '{{ $user->id }}',
                                        first: '{{ $user->first_name }}',
                                        last: '{{ $user->last_name }}',
                                        contact: '{{ $user->contact_number }}',
                                        email: '{{ $user->email }}',
                                        role: '{{ $user->role }}'
                                     })">
                                <img src="/icons/Remove.png" class="action-img" title="Delete" onclick="confirmDelete('{{ $user->id }}')">
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                        <input type="text" id="editFirstName" name="first_name">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" id="editLastName" name="last_name">
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
                        <option value="BHW">Health Worker</option>
                    </select>
                    <div class="role-info-box">
                        <span class="current-role-label">Current: <span id="currentRoleText">Admin</span></span>
                        <span id="rolePermissions">Full access - manage users, approve requests, view all reports</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Account Status</label>
                    <select id="editStatus" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="reset-section">
                    <span class="modal-section-label">Password Reset</span>
                    <button type="button" onclick="sendResetEmail()" class="btn-reset">Send Password Reset Email</button>
                    <p class="reset-help">User will receive an email with instructions to reset their password</p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Search Functionality
    document.querySelector('.search-box input').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('.user-table tbody tr');
        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
        });
    });

    // Modal Logic
    function openEditModal(userData) {
        document.getElementById('editFirstName').value = userData.first || '';
        document.getElementById('editLastName').value = userData.last || '';
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
            'Admin': 'Full access - manage users, approve requests, view all reports',
            'Doctor': 'Medical access - view patient history, manage triage, and medical reports',
            'Nurse': 'Clinical access - register patients, update vitals, and view records',
            'BHW': 'Basic access - inventory viewing and patient registration assistance'
        };
        info.innerText = roles[role] || 'Standard system access';
    }

    document.getElementById('editRole').addEventListener('change', function() {
        updateRoleInfo(this.value);
    });

    function sendResetEmail() {
        Swal.fire({
            title: 'Send Reset Link?',
            text: 'A password reset link will be sent to the user\'s email address.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Send it'
        });
    }

    function confirmDelete(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will be permanently removed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete user'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/admin/users/delete/${userId}`;
            }
        });
    }
</script>
</body>
</html>
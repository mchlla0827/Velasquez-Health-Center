<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create User — Velasquez Health Center</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            overflow-x: hidden;
        }

        .container { display: flex; min-height: 100vh; }

        /* ================= MAIN LAYOUT ================= */
        .main {
           margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box;
        }

        /* ================= HEADER — STANDARDIZED ================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .welcome-text {
            font-size: 14px;
            color: #374151;
            margin-bottom: 5px;
        }

        .welcome-name {
            font-weight: bold;
            color: #1F2937;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .role {
            color: white;
            padding: 3px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-admin  { background: #9333EA; }
        .role-nurse  { background: #10B981; }
        .role-bhw    { background: #6366F1; }
        .role-doctor { background: #3B82F6; }

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

        /* ================= FORM CARD ================= */
        /* ===== FORM CARD ===== */
.form-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 28px 32px;
  max-width: 100%;
  width: 100%;
  box-sizing: border-box;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

        .form-section {
            margin-bottom: 28px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            display: block;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 8px 0 16px 0;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px 24px;
        }

        .form-full-width {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
            display: inline-block;
        }

        input, select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background-color: #ffffff;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.08);
        }

        input::placeholder {
            color: #9ca3af;
        }

        /* ===== PIC OPTION BOX ===== */
        .pic-option {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 14px 16px;
            margin-top: 12px;
            display: none;
        }

        .pic-label {
            font-weight: 500;
            color: #1e40af;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            margin: 0;
        }

        .pic-label input[type="checkbox"] {
            width: auto;
            margin: 0;
            accent-color: #1A73E8;
        }

        .bold-text {
            font-weight: 600;
            color: #111827;
        }

        .helper-text {
            margin: 6px 0 0 0;
            font-style: italic;
            font-size: 12.5px;
            color: #6B7280;
            line-height: 1.4;
        }

        /* ===== BUTTONS ===== */
        .footer-buttons {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }

        .register-btn {
            background-color: #1a73e8;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .register-btn:hover {
            background-color: #1557b0;
        }

        .clear-btn {
            background-color: #ffffff;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .clear-btn:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        /* ===== ERROR BOX ===== */
        .error-box {
            background: #FEE2E2;
            color: #B91C1C;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            border: 1px solid #fecdd3;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .form-card { max-width: 100%; }
            .form-grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- ✅ STANDARD SIDEBAR COMPONENT -->
    <x-sidebar />

    <div class="main">

        <!-- ✅ STANDARD HEADER (matches all pages) -->
        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    @php
                        $userName = session('admin_name') ?? session('user_name') ?? Auth::user()->name ?? 'User';
                        $role = strtolower(session('admin_role') ?? session('user_role') ?? Auth::user()->role ?? 'admin');
                        $displayRole = strtoupper($role);
                        $roleClass = match($role) {
                            'admin' => 'role-admin',
                            'nurse' => 'role-nurse',
                            'doctor' => 'role-doctor',
                            default => 'role-bhw',
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

        <div class="page-title">Create User</div>

        <!-- FORM CARD -->
        <div class="form-card">

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                @if($errors->any())
                    <div class="error-box">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-section">
                    <b class="section-title">Personal Information</b>
                    <div class="divider"></div>

                    <div class="form-grid-2">
                        <div>
                            <label>First Name</label>
                            <input name="first_name" value="{{ old('first_name') }}" placeholder="Juan" required>
                        </div>
                        <div>
                            <label>Last Name</label>
                            <input name="last_name" value="{{ old('last_name') }}" placeholder="Dela Cruz" required>
                        </div>
                        <div class="form-full-width">
                            <label>Contact Number</label>
                            <input name="contact_number" value="{{ old('contact_number') }}" placeholder="09XX-XXX-XXXX" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <b class="section-title">Account Information</b>
                    <div class="divider"></div>

                    <div class="form-grid-2">
                        <div class="form-full-width">
                            <label>Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="juan.delacruz@example.com" required>
                        </div>
                        <div>
                            <label>Password</label>
                            <input type="password" name="password" required>
                        </div>
                        <div>
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <b class="section-title">Role & Permissions</b>
                    <div class="divider"></div>

                    <div class="form-grid-2">
                        <div>
                            <label>User Role</label>
                            <select name="role" id="user_role" required>
                                <option value="">Select Role</option>
                                <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Doctor" {{ old('role') === 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                <option value="Nurse" {{ old('role') === 'Nurse' ? 'selected' : '' }}>Nurse</option>
                                <option value="BHW" {{ old('role') === 'BHW' ? 'selected' : '' }}>Barangay Health Worker</option>
                            </select>
                        </div>

                        <div class="form-full-width pic-option" id="pic_checkbox_container">
                            <label class="pic-label">
                                <input type="checkbox" name="is_physician_in_charge" id="is_physician_in_charge" value="1"
                                    {{ old('is_physician_in_charge') ? 'checked' : '' }}>
                                Set as <span class="bold-text">Physician In Charge</span> (Can Approve Requests)
                            </label>
                            <p class="helper-text">
                                * I-check lamang kung ito ang Doktor na taga-apruba. Huwag i-check sa ibang doktor.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="footer-buttons">
                    <button type="submit" class="register-btn">Create User Account</button>
                    <a href="/admin/manage-user" class="clear-btn">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ Success Message
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#1A73E8'
        });
    @endif

    // ✅ Validation Errors
    @if($errors->any())
        Swal.fire({
            title: 'Registration Failed',
            text: "{{ $errors->first() }}",
            icon: 'error',
            confirmButtonText: 'Check Form',
            confirmButtonColor: '#EF4444'
        });
    @endif

    // ✅ Show PIC checkbox ONLY when Doctor is selected
    const roleSelect = document.getElementById('user_role');
    const picContainer = document.getElementById('pic_checkbox_container');
    const picCheckbox = document.getElementById('is_physician_in_charge');

    function togglePicOption() {
        if (roleSelect.value === 'Doctor') {
            picContainer.style.display = 'block';
        } else {
            picContainer.style.display = 'none';
            picCheckbox.checked = false;
        }
    }

    togglePicOption();
    roleSelect.addEventListener('change', togglePicOption);
});
</script>

</body>
</html>
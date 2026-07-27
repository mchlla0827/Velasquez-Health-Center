<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Create User</title>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #F9FAFB;
        overflow-x: hidden;
    }

    .container { 
        display: flex; 
    }

    /* ================= SIDEBAR (EXACT COPY FROM DASHBOARD) ================= */
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

    .logo { 
        width: 40px; 
        height: 40px; 
        border-radius: 50%; 
    }

    .brand-wrapper { 
        display: flex; 
        flex-direction: column; 
        line-height: 1.2; 
    }

    .brand { 
        font-weight: 
        bold; color: #1E3A8A; 
        font-size: 13.3px; 
    }

    .sub { 
        font-size: 11px; 
        color: #6B7280; 
    }

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

    .nav-icon { 
        width: 22px; 
        height: 22px; 
        object-fit: contain; 
    }

    .nav-item.active {
        background: #EFF6FF;
        color: #1A73E8;
        border-left: 4px solid #1A73E8;
        font-weight: bold;
    }

    .nav-item:hover { background: #F3F4F6; }

    /* ================= MAIN ================= */
    .main {
        margin-left: 250px;
        width: calc(100% - 250px);
        padding: 24px;
        box-sizing: border-box;
    }

    /* ================= HEADER (EXACT COPY FROM DASHBOARD) ================= */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .welcome-text{ 
        font-size: 14px; 
        color: #374151; 
        margin-bottom: 5px; 
    }

    .welcome-name { 
        font-weight: bold; 
        color: #1F2937; 
        font-size: 18px; 
    }

    .role {
        background: #9333EA;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
    }

    .right { 
        text-align: right; 
        font-size: 12px; 
        color: #374151; 
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
        margin-bottom: 16px;
        margin-left: 2.2%
    }
    /* ================= CREATE USER FORM ================= */

    /* ===== FORM CARD BASE ===== */
.form-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 28px 32px;
  max-width: 75%;
  margin: 20px 2%;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

/* ===== SECTIONS ===== */
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

/* ===== GRID LAYOUT ===== */
.form-grid-2 {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 18px 24px;
}

.form-full-width {
  grid-column: 1 / -1; /* takes full width of grid */
}

/* ===== LABELS & INPUTS ===== */
label {
  font-size: 13px;
  font-weight: 500;
  color: #374151;
  margin-bottom: 6px;
  display: inline-block;
}

input,
select {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  background-color: #ffffff;
  transition: all 0.2s ease;
  box-sizing: border-box;
}

input:focus,
select:focus {
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
  margin-top: 6px;
  display: none; /* hidden by default — shown via JS */
}

.pic-option label {
  color: #1e40af;
  font-weight: 500;
}

.helper-text {
  margin: 4px 0 0 0;
  font-style: italic;
  font-size: 12.5px;
  color: #6b7280;
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
}

.clear-btn:hover {
  background-color: #f3f4f6;
  border-color: #9ca3af;
}

/* ===== ERROR BOX ===== */
.form-card > div[style*="background: #FEE2E2"] {
  border-radius: 8px;
  border: 1px solid #fecdd3;
}

.form-card ul {
  margin: 0;
  padding-left: 16px;
}

/* ===== RESPONSIVE ADJUSTMENTS ===== */
@media (max-width: 768px) {
  .form-card {
    max-width: 96%;
    margin: 10px auto;
    padding: 20px;
  }
  .form-grid-2 {
    grid-template-columns: 1fr;
  }
}

    .register-btn {
        background: #1A73E8;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
    }

    .clear-btn {
        background: white;
        border: 1px solid #D1D5DB;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
    }

    /* ================= TABLE ================= */

    /* ✅ STYLE FOR PIC OPTION */
    .pic-option {
    display: none; /* Hidden by default — shown only when Doctor is selected */
    margin-top: 12px;
    padding: 14px 16px;
    background-color: #EFF6FF;
    border-radius: 8px;
    border: 1px solid #BFDBFE;
}

.pic-label {
    font-weight: 500;
    color: #1E40AF;
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
    accent-color: #1A73E8; /* blue check mark */
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

</style>
</head>

<body>

<div class="container">

<!-- SIDEBAR (FULL EXACT COPY) -->
<x-sidebar />

<!-- MAIN -->
<div class="main">

    <!-- HEADER (UNCHANGED) -->
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
            @php
                date_default_timezone_set('Asia/Manila');
            @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>

    <div class="header-divider"></div>

    <div class="page-title">Create User</div>
    
<div class="form-card">

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        @if($errors->any())
            <div style="background: #FEE2E2; color: #B91C1C; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
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
                    <input name="contact_number" value="{{ old('contact_number') }}" placeholder="09XX-XXX-XXXX">
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
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="input-group">
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
                        <option value="Admin">Admin</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Nurse">Nurse</option>
                        <option value="BHW">Barangay Health Worker</option>
                    </select>
                </div>

                <div class="form-full-width pic-option" id="pic_checkbox_container">
                    <label class="pic-label">
                        <input type="checkbox" name="is_physician_in_charge" id="is_physician_in_charge" value="1">
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
            <a href="/admin/manage-user" class="clear-btn" style="text-decoration:none; display:inline-block;">Cancel</a>
        </div>
    </form>
</div>

</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Show Success Message
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#1A73E8'
            });
        @endif

        // 2. Show Validation Errors (e.g., Email already taken)
        @if($errors->any())
            Swal.fire({
                title: 'Registration Failed',
                text: "{{ $errors->first() }}",
                icon: 'error',
                confirmButtonText: 'Check Form',
                confirmButtonColor: '#EF4444'
            });
        @endif

        // ✅ FIXED SCRIPT
            const roleSelect = document.getElementById('user_role');
            const picContainer = document.getElementById('pic_checkbox_container');
            const picCheckbox = document.getElementById('is_physician_in_charge'); // ✅ MATCHES ID

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
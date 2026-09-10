<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Patient Registration</title>

<style>
    body { margin: 0; font-family: Arial, sans-serif; background: #F9FAFB; overflow-x: hidden; }
    .container { display: flex; }

    /* Sidebar logic handled by component */
    .main { margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; }

    /* ================= HEADER ================= */
    .header { display: flex; justify-content: space-between; align-items: flex-start; }
    .welcome-text{ font-size: 14px; color: #374151; margin-bottom: 5px; }
    .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }

    /* Dynamic Role Colors */
    .role { color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-left: 6px; }
    .role-admin { background: #9333EA; }
    .role-nurse { background: #10B981; }
    .role-bhw { background: #6366F1; }

    .right { text-align: right; font-size: 12px; color: #374151; }
    .header-divider { width: 100%; height: 1px; background: #E5E7EB; margin: 16px 0; }
    .page-title { font-size: 22px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px; }

    /* ================= PATIENT FORM ================= */
.form-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 25px;
    width: 95%;          /* Takes up most of the screen on standard displays */
    max-width: 1200px;   /* Prevents it from getting absurdly wide on huge monitors */
    margin: 20px auto;   /* Centers the card and gives it top/bottom breathing room */
}    

.section-banner { background: #EFF6FF; border: 1px solid #BFDBFE; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
    .section-banner b { display: block; color: #111827; }
    .section-banner span { font-size: 12px; color: #6B7280; font-style: italic;}
    
    .form-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px; }
    .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px; }
    .form-full { margin-bottom: 16px; }
    
    label { font-size: 13px; color: #374151; font-weight: bold; margin-bottom: 10px; display: block; }
    
    /* ========================================= */
    /* == MODIFIED: INPUT & FLOATING LABEL STYLE == */
    /* ========================================= */
    
    /* Input Container - Relative Position for Label */
    .form-grid-3 > div, 
    .form-grid-2 > div, 
    .form-full > div,
    .form-grid-3 > div > div {
        position: relative;
    }

    /* Input Base Style */
    input, select { 
        width: 100%; 
        padding: 16px 12px 8px 12px; /* Extra padding top for floating label space */
        border: 1px solid #D1D5DB; 
        border-radius: 8px; 
        font-size: 13px; 
        outline: none; 
        box-sizing: border-box; 
        display: block; 
        transition: border-color 0.2s; 
        background: transparent; /* Make background transparent so floating label sits behind nicely */
        height: 48px; /* Fixed height for consistency */
    }

    /* Remove native placeholder */
    input::placeholder {
        color: transparent;
    }

    /* Floating Label Base */
    .floating-label {
        position: absolute;
        left: 12px;
        top: 14px;
        font-size: 13px;
        color: #6B7280;
        pointer-events: none;
        transition: all 0.2s ease;
        background: #ffffff;
        padding: 0 4px;
        display: flex;
        gap: 3px;
    }

    /* --- COLOR LOGIC --- */
    /* Required Fields: Blue text + Red Asterisk */
    .req-field .floating-label { color: #6B7280 !important; }
    .req-star { color: #DC2626; font-weight: bold; }

    /* Optional Fields: Gray text + Gray (Optional) */
    .opt-field .floating-label { color: #6B7280 !important; }
    .opt-text { font-style: italic; font-size: 11px; opacity: 0.8; }

    /* --- ANIMATION: FLOAT UP --- */
    input:focus ~ .floating-label,
    select:focus ~ .floating-label,
    input:not(:placeholder-shown):valid ~ .floating-label,
    select:not([value=""]):required ~ .floating-label,
    input[readonly] ~ .floating-label {
        top: -8px;
        left: 10px;
        font-size: 11px;
    }

    /* Focus Border Color */
    input:focus, select:focus {
        border-color: #1A73E8;
        box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.1);
    }

    /* Validation Classes applied via JS */
    .border-red { border-color: #EF4444 !important; }
    .border-green { border-color: #10B981 !important; }
    .border-gray { border-color: #D1D5DB !important; } /* For resetting */
    
    .required { color: red; }
    .tracking-box { background: #F9FAFB; border-radius: 8px; padding: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #E5E7EB; }
    
    .footer-buttons { margin-top: 30px; display: flex; gap: 10px; padding-bottom: 10px; }  
    .register-btn { background: #1A73E8; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; }
    .clear-btn { background: white; border: 1px solid #D1D5DB; padding: 12px 24px; border-radius: 8px; cursor: pointer; }
    
    .radio-group { display: flex; gap: 20px; margin-top: 6px; }
    .radio-group label { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: normal; }
    
    .immun-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .immun-table th, .immun-table td { border: 1px solid #E5E7EB; padding: 8px; font-size: 12px; }

    .error-msg { color: #EF4444; font-size: 11px; margin-top: 4px; display: none; font-weight: normal; }
    /* Custom File Upload Styling */
input[type="file"].custom-file-input {
    height: 48px;
    padding: 6px 10px;
    background: #ffffff;
    cursor: pointer;
    line-height: 34px;
    color: #4B5563;
}

input[type="file"].custom-file-input::file-selector-button {
    margin-right: 12px;
    border: none;
    background: #EFF6FF;
    color: #1A73E8;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
}

input[type="file"].custom-file-input::file-selector-button:hover {
    background: #DBEAFE;
    color: #1D4ED8;
}
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
                {{ Auth::user()->name ?? session('admin_name') ?? session('user_name') }}
                @php 
                    $role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw'); 
                @endphp
                <span class="role {{ $role === 'admin' ? 'role-admin' : ($role === 'nurse' ? 'role-nurse' : 'role-bhw') }}">
                    {{ strtoupper($role) }}
                </span>
            </div>
        </div>
        <div class="right">
            <b>Velasquez Health Center</b><br>
            @php date_default_timezone_set('Asia/Manila'); @endphp
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>
    <div class="header-divider"></div>

    <div class="page-title">Patient Registration</div>

    <form id="registrationForm" 
      action="{{ isset($patient) ? route('patients.update', $patient->id) : route('patients.store') }}" 
      method="POST" enctype="multipart/form-data">
    @csrf


    @if(isset($patient))
        @method('PUT')
    @endif

        <div class="form-card">
            <div class="section-banner">
                <b>Basic Information</b>
                <span>Required fields marked with *</span>
            </div>

            <label>Patient Information</label>
            <div class="form-grid-3">

                <div class="req-field">
                    <input name="last_name" placeholder=" " required pattern="[A-Za-z\s]+" title="Letters only" 
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    <span class="floating-label">Last Name <span class="req-star">*</span></span>
                    <span class="error-msg">Please enter a valid name (letters only).</span>
                </div>
                <div class="req-field">
                    <input name="first_name" placeholder=" " required pattern="[A-Za-z\s]+" title="Letters only" 
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    <span class="floating-label">First Name <span class="req-star">*</span></span>
                    <span class="error-msg">Please enter a valid name (letters only).</span>
                </div>
                <div class="opt-field">
                    <input name="middle_initial" placeholder=" " pattern="[A-Za-z]?" title="Single letter only" 
                        oninput="this.value = this.value.replace(/[^A-Za-z]/g, '').slice(0,1)">
                    <span class="floating-label">Middle Initial <span class="opt-text">(Optional)</span></span>
                    <span class="error-msg">Please enter a valid initial (one letter only).</span>
                </div>
            </div>

            <label>Mother's Name</label>
            <div class="form-grid-3">
                <div class="opt-field">
                    <input name="mother_last" placeholder=" " pattern="[A-Za-z\s]*" 
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    <span class="floating-label">Last Name <span class="opt-text">(Optional)</span></span>
                    <span class="error-msg">Please enter a valid name (letters only).</span>
                </div>
                <div class="opt-field">
                    <input name="mother_first" placeholder=" " pattern="[A-Za-z\s]*" 
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    <span class="floating-label">First Name <span class="opt-text">(Optional)</span></span>
                    <span class="error-msg">Please enter a valid name (letters only).</span>
                </div>
                <div class="opt-field">
                    <input name="mother_middle" placeholder=" " pattern="[A-Za-z\s]*" 
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    <span class="floating-label">Middle Name <span class="opt-text">(Optional)</span></span>
                    <span class="error-msg">Please enter a valid name (letters only).</span>
                </div>
            </div>

            <label>Father's Name</label>
            <div class="form-grid-3">
                <div class="opt-field">
                    <input name="father_last" placeholder=" " pattern="[A-Za-z\s]*" 
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    <span class="floating-label">Last Name <span class="opt-text">(Optional)</span></span>
                    <span class="error-msg">Please enter a valid name (letters only).</span>
                </div>
                <div class="opt-field">
                    <input name="father_first" placeholder=" " pattern="[A-Za-z\s]*" 
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    <span class="floating-label">First Name <span class="opt-text">(Optional)</span></span>
                    <span class="error-msg">Please enter a valid name (letters only).</span>
                </div>
                <div class="opt-field">
                    <input name="father_middle" placeholder=" " pattern="[A-Za-z\s]*" 
                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                    <span class="floating-label">Middle Name <span class="opt-text">(Optional)</span></span>
                    <span class="error-msg">Please enter a valid name (letters only).</span>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="req-field">
                    <label>Address <span class="required">*</span></label>
                    <input name="address" placeholder="Address" required>
                    <span class="error-msg">Address is required.</span>
                </div>
            <div class="req-field">
                <label>Proof of Residency / Residency ID <span class="required">*</span></label>
                <input type="file" name="residency_proof" id="residency_proof" class="custom-file-input" accept="image/jpeg,image/jpg,image/png" required>
                <span style="display:block; font-size: 11px; color: #9CA3AF; margin-top: 5px;">Accepted formats: JPG, JPEG, PNG</span>
                @error('residency_proof')
                    <span class="error-msg" style="display:block;">{{ $message }}</span>
                @enderror
            </div>
            </div>

            <div class="form-grid-3">
                <div class="req-field">
                    <label>Barangay <span class="required">*</span></label>
                    <select name="barangay" required>
                        <option value="" disabled selected></option>
                        @foreach($activeBarangays ?? [] as $barangayName)
                            <option value="{{ $barangayName }}" {{ old('barangay') === $barangayName ? 'selected' : '' }}>{{ $barangayName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="opt-field">
                    <label>Family #</label>
                    <input type="text" name="family_number" value="{{ $nextFamilyId }}" readonly style="background: #f4f6f9; color: #2b2d42; font-weight: bold; border: 1px solid #ced4da;">
                </div>
                <div class="req-field">
                    <label>Contact No <span class="required">*</span></label>
                        <input name="contact_number" 
                            id="contact_number"
                            placeholder=" " 
                            required 
                            maxlength="11"> 
                        <span id="contact-type-error" class="error-msg">Numbers only, please.</span>
                        <span id="contact-length-error" class="error-msg">Please enter exactly 11 digits.</span>
                </div>
            </div>

            <div class="form-full opt-field">
                <label>Email Address</label>
                <input name="email" type="email" placeholder=" ">
                <span class="floating-label">Email Address <span class="opt-text">(Optional)</span></span>
                <span class="error-msg">Invalid email format.</span>
            </div>

            <div class="form-grid-2">
                <div class="req-field">
                    <label>Date of Birth <span class="required">*</span></label>
                    <input type="date" name="dob" id="dob" value="{{ old('dob') }}" required>                    
                    <!-- HIDDEN FIELD FOR AUTOMATIC AGE SUBMISSION -->
                    <input type="hidden" name="age" id="age_input">
                    <span class="error-msg" id="dob-error">Date cannot be in the future.</span>
                </div>
                <div class="opt-field">
                    <label>Place of Birth</label>
                    <input name="pob" placeholder=" " pattern="[A-Za-z\s]*">
                    <span class="error-msg">Please enter a valid place of birth (letters only)</span>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="req-field">
                    <label>Sex <span class="required">*</span></label>
                    <select name="gender" required>
                        <option value="" disabled selected></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="opt-field">
                    <label>Civil Status</label>
                    <select name="civil_status">
                        <option value="" disabled selected></option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                    </select>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="opt-field">
                    <label>OSCA/PWD No</label>
                    <input name="osca_pwd_no" placeholder=" " pattern="\d*">
                    <span class="error-msg">Numbers only, please.</span>
                </div>
                <div class="opt-field">
                    <label>4Ps No</label>
                    <input name="four_ps_no" placeholder=" " pattern="\d*">
                    <span class="error-msg">Please enter numbers only.</span>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="opt-field">
                    <label>Religion</label>
                    <input name="religion" placeholder=" " pattern="[A-Za-z\s]*">
                    <span class="error-msg">Please enter a valid Religion (letters only)</span>
                </div>
                <div class="opt-field">
                    <label>Educational Attainment</label>
                    <select name="educational_attainment">
                        <option value="" disabled selected></option>
                        <option value="None">None</option>
                        <option value="Elementary Graduate">Elementary Graduate</option>
                        <option value="High School Graduate">High School Graduate</option>
                        <option value="College Graduate">College Graduate</option>
                        <option value="Vocational">Vocational</option>
                    </select>
                </div>
            </div>

            <div class="form-full">
                <label>PhilHealth Membership</label>
                <div class="radio-group">
                    <!-- CHANGED FIELD NAME FROM philhealth TO philhealth_type -->
                    <label><input type="radio" name="philhealth_type" value="None" checked> None</label>
                    <label><input type="radio" name="philhealth_type" value="Member"> Member</label>
                    <label><input type="radio" name="philhealth_type" value="Dependent"> Dependent</label>
                </div>
            </div>

            <div id="memberFields" style="display:none;" class="form-full opt-field">
                <label>PhilHealth No</label>
                <input name="philhealth_no_member" placeholder=" " maxlength="12">
                <span id="philhealth-member-type-error" class="error-msg">Numbers only, please.</span>
                <span id="philhealth-member-length-error" class="error-msg">Please enter exactly 12 digits.</span>
            </div>

            <div id="dependentFields" style="display:none;">
                <div class="form-grid-2">
                    <div class="opt-field">
                        <label>PhilHealth No</label>
                        <input name="philhealth_no_dep" placeholder=" " maxlength="12">
                        <span id="philhealth-dep-type-error" class="error-msg">Numbers only, please.</span>
                        <span id="philhealth-dep-length-error" class="error-msg">Please enter exactly 12 digits.</span>
                    </div>
                    <div class="opt-field">
                        <label>Name of Member</label>
                        <input name="philhealth_member_name" placeholder=" " pattern="[A-Za-z\s]+" title="Letters only">
                        <span class="error-msg">Please enter a valid name (letters only).</span>
                    </div>
                </div>
                <div class="form-full opt-field">
                    <label>Birthdate</label>
                    <input type="date" name="philhealth_member_dob">
                </div>
            </div>

            <div class="tracking-box">
                <span>Does this patient need immunization tracking?</span>
                <select name="tracking_immunization" style="width: 100px;">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>

            <div id="immunizationSection" style="display:none; margin-bottom: 20px;">
                <b>Immunization History</b>
                <table class="immun-table">
                    <tr><th>Vaccine</th><th>Date Given</th><th>Vaccine</th><th>Date Given</th><th>Vaccine</th><th>Date Given</th></tr>
                    <tr><td>BCG</td><td><input type="date" name="vac_bcg" class="past-date-only" max="{{ date('Y-m-d') }}"></td><td>PENTA 2</td><td><input type="date" name="vac_penta2" class="past-date-only" max="{{ date('Y-m-d') }}"></td><td>MMR 1</td><td><input type="date" name="vac_mmr1" class="past-date-only" max="{{ date('Y-m-d') }}"></td></tr>
                    <tr><td>HEPA B</td><td><input type="date" name="vac_hepa" class="past-date-only" max="{{ date('Y-m-d') }}"></td><td>OPV 2</td><td><input type="date" name="vac_opv2"></td><td>MMR 2</td><td><input type="date" name="vac_mmr2"></td></tr>
                    <tr><td>PENTA 1</td><td><input type="date" name="vac_penta1" class="past-date-only" max="{{ date('Y-m-d') }}"></td><td>PCV 2</td><td><input type="date" name="vac_pcv2"></td><td>HPV 1</td><td><input type="date" name="vac_hpv1"></td></tr>
                    <tr><td>OPV 1</td><td><input type="date" name="vac_opv1" class="past-date-only" max="{{ date('Y-m-d') }}"></td><td>PENTA 3</td><td><input type="date" name="vac_penta3"></td><td>HPV 2</td><td><input type="date" name="vac_hpv2"></td></tr>
                    <tr><td>PCV 1</td><td><input type="date" name="vac_pcv1" class="past-date-only" max="{{ date('Y-m-d') }}"></td><td>OPV 3</td><td><input type="date" name="vac_opv3"></td><td>FLU</td><td><input type="date" name="vac_flu"></td></tr>
                    <tr><td>IPV 1</td><td><input type="date" name="vac_ipv1" class="past-date-only" max="{{ date('Y-m-d') }}"></td><td>PCV 3</td><td><input type="date" name="vac_pcv3"></td><td>PNEUMONIA</td><td><input type="date" name="vac_pneumo"></td></tr>
                    <tr><td>IPV 2</td><td><input type="date" name="vac_ipv2" class="past-date-only" max="{{ date('Y-m-d') }}"></td><td>MR 1</td><td><input type="date" name="vac_mr1"></td><td>TD</td><td><input type="date" name="vac_td"></td></tr>
                </table>
            </div>

            <div class="tracking-box">
                <span>Does this patient need maternal/child health tracking?</span>
                <select name="tracking_maternal" style="width: 100px;">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
            
            <div id="maternalSection" style="display:none;">
                <div style="width: 100%; height: 1px; background: #E5E7EB; margin: 30px 0;"></div>

                <div class="form-full">
                    <label style="font-size: 14px; color: #111827; border-bottom: 2px solid #E5E7EB; padding-bottom: 5px; margin-bottom: 20px;">NEWBORN & BIRTH DETAILS</label>
                    
                    <div style="display: grid; grid-template-columns: 180px 150px 1fr 1fr; gap: 16px; align-items: center; margin-bottom: 20px;">
                        <label style="margin-bottom: 0;">Newborn Screening</label>
                        <div style="display: flex; gap: 15px; white-space: nowrap;">
                            <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;"><input type="radio" name="mat_nbs" value="Yes" style="width: auto;"> Yes</label>
                            <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;"><input type="radio" name="mat_nbs" value="No" checked style="width: auto;"> No</label>
                        </div>
                        <input type="date" name="mat_nbs_date" class="past-date-only" max="{{ date('Y-m-d') }}">
                        <input name="mat_nbs_result" placeholder="NBS Result" pattern="[A-Za-z\s]+">
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 150px 1fr 1fr; gap: 16px; align-items: center; margin-bottom: 20px;">
                        <label style="margin-bottom: 0;">Hearing Test</label>
                        <div style="display: flex; gap: 15px; white-space: nowrap;">
                            <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;"><input type="radio" name="mat_hearing" value="Yes" style="width: auto;"> Yes</label>
                            <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;"><input type="radio" name="mat_hearing" value="No" checked style="width: auto;"> No</label>
                        </div>
                        <input type="date" name="mat_hearing_date" class="past-date-only" max="{{ date('Y-m-d') }}">
                        <input name="mat_hearing_result" placeholder="Hearing Result" pattern="[A-Za-z\s]+">
                    </div>

                    <div class="form-grid-3" style="margin-top: 20px;">
                        <div class="opt-field">
                            <label>Birth Order</label>
                            <input type="number" name="mat_birth_order" placeholder=" ">
                        </div>
                        <div class="opt-field">
                            <label>Birth Length (cm)</label>
                            <input type="number" step="0.1" name="mat_birth_length" placeholder=" ">
                        </div>
                        <div class="opt-field">
                            <label>Birth Weight (kg)</label>
                            <input type="number" step="0.1" name="mat_birth_weight" placeholder=" ">
                        </div>
                    </div>
                </div>

                <div class="form-full" style="margin-top: 30px;">
                    <label style="font-size: 14px; color: #111827; border-bottom: 2px solid #E5E7EB; padding-bottom: 5px; margin-bottom: 20px;">DELIVERY & FEEDING</label>
                    
                    <div class="form-grid-3">
                        <div class="opt-field">
                            <label>Type of Delivery</label>
                            <select name="mat_delivery_type">
                                <option value="" selected disabled></option>
                                <option value="NSVD">NSVD</option>
                                <option value="Cesarean Section">Cesarean Section</option>
                                <option value="Assisted Delivery">Assisted Delivery</option>
                                <option value="Breech">Breech</option>
                            </select>
                        </div>
                        <div class="opt-field">
                            <label>Type of Feeding</label>
                            <select name="mat_feeding_type">
                                <option value="" selected disabled></option>
                                <option value="Breastfeeding">Breastfeeding</option>
                                <option value="Formula Feeding">Formula Feeding</option>
                                <option value="Mixed Feeding">Mixed Feeding</option>
                            </select>
                        </div>
                        <div>
                            <label>Birth Attendant</label>
                            <div style="display: flex; gap: 20px; padding-top: 10px;">
                                <label style="display: flex; align-items: center; gap: 6px; font-weight: normal;"><input type="radio" name="mat_attendant" value="SBA" style="width: auto;"> SBA</label>
                                <label style="display: flex; align-items: center; gap: 6px; font-weight: normal;"><input type="radio" name="mat_attendant" value="NON-SBA" style="width: auto;"> NON-SBA</label>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 40px; margin-top: 20px;">
                        <label style="margin-bottom: 0; min-width: 140px;">Place of Delivery</label>
                        <div style="display: flex; gap: 30px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="radio" name="mat_delivery_place" value="FB" style="width: auto;"> FB (Facility-Based)</label>
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;"><input type="radio" name="mat_delivery_place" value="NFB" style="width: auto;"> NFB (Non-Facility-Based)</label>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 40px; margin-top: 35px;">
                    <div>
                        <label style="font-size: 14px; color: #111827; border-bottom: 2px solid #E5E7EB; padding-bottom: 5px; margin-bottom: 15px;">VITAMIN A</label>
                        <div style="display: flex; align-items: center; gap: 15px; margin-top:35px;">
                            <div style="display: flex; gap: 15px; min-width: 160px;">
                                <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;"><input type="radio" name="mat_vit_a_dose" value="100k" style="width: auto;"> 100k IU</label>
                                <label style="display: flex; align-items: center; gap: 5px; font-weight: normal;"><input type="radio" name="mat_vit_a_dose" value="200k" style="width: auto;"> 200k IU</label>
                            </div>
                            <input type="date" name="mat_vit_a_date" class="past-date-only" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 14px; color: #111827; border-bottom: 2px solid #E5E7EB; padding-bottom: 5px; margin-bottom: 15px;">DEWORMING</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="opt-field">
                                <label style="font-size: 11px; color: #6B7280;">1st Dose</label>
                                <input type="date" name="mat_deworming_1" class="past-date-only" max="{{ date('Y-m-d') }}">
                                <span class="floating-label" style="font-size:10px;">1st Dose <span class="opt-text">(Optional)</span></span>
                            </div>
                            <div class="opt-field">
                                <label style="font-size: 11px; color: #6B7280;">2nd Dose</label>
                                <input type="date" name="mat_deworming_2" class="past-date-only" max="{{ date('Y-m-d') }}">
                                <span class="floating-label" style="font-size:10px;">2nd Dose <span class="opt-text">(Optional)</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-full" style="margin-top: 30px;">
                    <label style="font-size: 14px; color: #111827; border-bottom: 2px solid #E5E7EB; padding-bottom: 5px; margin-bottom: 20px;">OBSTETRIC HISTORY (OB HISTORY)</label>

                    <div class="form-grid-3">
                        <div class="opt-field">
                            <label>Gravida (G)</label>
                            <input type="number" name="ob_g" min="0" placeholder=" ">
                        </div>
                        <div class="opt-field">
                            <label>Para (P) - TPAL</label>
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px;">
                                <input type="number" name="ob_p_t" placeholder="T">
                                <input type="number" name="ob_p_p" placeholder="P">
                                <input type="number" name="ob_p_a" placeholder="A">
                                <input type="number" name="ob_p_l" placeholder="L">
                            </div>
                        </div>
                        <div class="opt-field">
                            <label>Menarche (Age)</label>
                            <input type="number" name="ob_menarche" min="8" max="20" placeholder=" ">
                        </div>
                    </div>

                    <div class="form-grid-3" style="margin-top: 15px;">
                        <div class="opt-field">
                            <label>PMP</label>
                            <input type="date" name="ob_pmp">
                        </div>
                        <div class="opt-field">
                            <label>LMP</label>
                            <input type="date" name="ob_lmp">
                        </div>
                        <div class="opt-field">
                            <label>EDC (Due Date)</label>
                            <input type="date" name="ob_edc">
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
    <label>TT/TD Immunization Status</label>
    <div class="form-grid-2" style="align-items: flex-start; gap: 16px; grid-template-columns: 220px 1fr;">
        <div>
            <select name="ob_tt_status" style="width: 220px; height: 48px; padding: 0 12px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 13px; background: white;">
                <option value="Incomplete">Incomplete</option>
                <option value="Complete">Complete</option>
            </select>
        </div>

        <div>
            <table class="immun-table" style="width: 100%; margin: 0; table-layout: fixed; border-collapse: collapse;">
                <tr style="background: #F9FAFB;">
                    <th style="padding: 8px; font-size: 12px; text-align: center; border: 1px solid #E5E7EB;">TD1</th>
                    <th style="padding: 8px; font-size: 12px; text-align: center; border: 1px solid #E5E7EB;">TD2</th>
                    <th style="padding: 8px; font-size: 12px; text-align: center; border: 1px solid #E5E7EB;">TD3</th>
                    <th style="padding: 8px; font-size: 12px; text-align: center; border: 1px solid #E5E7EB;">TD4</th>
                    <th style="padding: 8px; font-size: 12px; text-align: center; border: 1px solid #E5E7EB;">TD5</th>
                </tr>
                <tr>
                    <td style="padding: 4px; border: 1px solid #E5E7EB;">
                        <input type="date" name="ob_td1" class="past-date-only" max="{{ date('Y-m-d') }}" style="width: 100%; height: 38px; padding: 4px 6px; font-size: 12px; box-sizing: border-box; border: 1px solid #D1D5DB; border-radius: 6px;">
                    </td>
                    <td style="padding: 4px; border: 1px solid #E5E7EB;">
                        <input type="date" name="ob_td2" class="past-date-only" max="{{ date('Y-m-d') }}" style="width: 100%; height: 38px; padding: 4px 6px; font-size: 12px; box-sizing: border-box; border: 1px solid #D1D5DB; border-radius: 6px;">
                    </td>
                    <td style="padding: 4px; border: 1px solid #E5E7EB;">
                        <input type="date" name="ob_td3" class="past-date-only" max="{{ date('Y-m-d') }}" style="width: 100%; height: 38px; padding: 4px 6px; font-size: 12px; box-sizing: border-box; border: 1px solid #D1D5DB; border-radius: 6px;">
                    </td>
                    <td style="padding: 4px; border: 1px solid #E5E7EB;">
                        <input type="date" name="ob_td4" class="past-date-only" max="{{ date('Y-m-d') }}" style="width: 100%; height: 38px; padding: 4px 6px; font-size: 12px; box-sizing: border-box; border: 1px solid #D1D5DB; border-radius: 6px;">
                    </td>
                    <td style="padding: 4px; border: 1px solid #E5E7EB;">
                        <input type="date" name="ob_td5" class="past-date-only" max="{{ date('Y-m-d') }}" style="width: 100%; height: 38px; padding: 4px 6px; font-size: 12px; box-sizing: border-box; border: 1px solid #D1D5DB; border-radius: 6px;">
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
                </div>
            </div> 

            <div class="footer-buttons">
                <button type="submit" class="register-btn">Register Patient</button>
                <button type="reset" class="clear-btn">Clear Form</button>
            </div>
        </div>
    </form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('registrationForm');
        const inputs = document.querySelectorAll('input, select');
        const clearBtn = document.querySelector('.clear-btn');
        const today = new Date().toISOString().split('T')[0];

        // 1. Clear Form Listener
        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault(); 
                Swal.fire({
                    title: 'Clear Form?',
                    text: "All progress will be lost. Are you sure?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, clear it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.reset();
                        inputs.forEach(input => {
                            input.classList.remove('border-green', 'border-red');
                            input.classList.add('border-gray');
                        });
                    }
                });
            });
        }

        // 2. Date of Birth restriction & Age Auto-calculation
            const dobInput = document.getElementById('dob');        
            const ageInput = document.getElementById('age_input');

        function calculateAge(dobValue) {
            if (!dobValue) return '';
            const birthDate = new Date(dobValue);
            const todayDate = new Date();
            let age = todayDate.getFullYear() - birthDate.getFullYear();
            const m = todayDate.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && todayDate.getDate() < birthDate.getDate())) {
                age--;
            }
            return age >= 0 ? age : 0;
        }

        if (dobInput) {
            dobInput.setAttribute('max', today);
            dobInput.addEventListener('change', function() {
                if (ageInput) {
                    ageInput.value = calculateAge(this.value);
                }
            });
        }

        // 3. Section Toggles
        document.querySelectorAll('input[name="philhealth_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const memberFields = document.getElementById('memberFields');
                const dependentFields = document.getElementById('dependentFields');
                if (memberFields) memberFields.style.display = (this.value === 'Member') ? 'block' : 'none';
                if (dependentFields) dependentFields.style.display = (this.value === 'Dependent') ? 'block' : 'none';
            });
        });

        const trackingImm = document.querySelector('select[name="tracking_immunization"]');
        if (trackingImm) {
            trackingImm.addEventListener('change', function() {
                const section = document.getElementById('immunizationSection');
                if (section) section.style.display = this.value === 'Yes' ? 'block' : 'none';
            });
        }

        const trackingMat = document.querySelector('select[name="tracking_maternal"]');
        if (trackingMat) {
            trackingMat.addEventListener('change', function() {
                const section = document.getElementById('maternalSection');
                if (section) section.style.display = this.value === 'Yes' ? 'block' : 'none';
            });
        }

        // 4. Validation Helpers
        function hideCustomErrors(input) {
            const errIds = {
                'contact_number': ['contact-type-error', 'contact-length-error'],
                'philhealth_no_member': ['philhealth-member-type-error', 'philhealth-member-length-error'],
                'philhealth_no_dep': ['philhealth-dep-type-error', 'philhealth-dep-length-error']
            };

            if (errIds[input.name]) {
                errIds[input.name].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.style.display = 'none';
                });
            }
        }

        function showValidationUI(input, isValid, specificErrorId = null) {
            if (isValid) {
                input.classList.remove('border-red', 'border-gray');
                input.classList.add('border-green');
                hideCustomErrors(input);
                if (input.nextElementSibling && input.nextElementSibling.classList.contains('error-msg')) {
                    input.nextElementSibling.style.display = 'none';
                }
            } else {
                input.classList.remove('border-green', 'border-gray');
                input.classList.add('border-red');
                
                if (specificErrorId) {
                    hideCustomErrors(input);
                    const errEl = document.getElementById(specificErrorId);
                    if (errEl) errEl.style.display = 'block';
                } else if (input.nextElementSibling && input.nextElementSibling.classList.contains('error-msg')) {
                    input.nextElementSibling.style.display = 'block';
                }
            }
        }

        function validateInput(input, isBlur = false) {
            const value = input.value.trim();

            if (value === "") {
                input.classList.remove('border-green', 'border-red');
                input.classList.add('border-gray');
                hideCustomErrors(input);
                const dobErr = document.getElementById('dob-error');
                if (input.id === 'dob' && dobErr) dobErr.style.display = 'none';
                if (input.nextElementSibling && input.nextElementSibling.classList.contains('error-msg')) {
                    input.nextElementSibling.style.display = 'none';
                }
                return;
            }

            const isContact = input.name === 'contact_number';
            const isPHMember = input.name === 'philhealth_no_member';
            const isPHDep = input.name === 'philhealth_no_dep';

            if (isContact || isPHMember || isPHDep) {
                const isNumeric = /^\d+$/.test(value);
                const targetLength = isContact ? 11 : 12;
                const isCorrectLength = value.length === targetLength;

                let typeErr = isContact ? 'contact-type-error' : (isPHMember ? 'philhealth-member-type-error' : 'philhealth-dep-type-error');
                let lenErr = isContact ? 'contact-length-error' : (isPHMember ? 'philhealth-member-length-error' : 'philhealth-dep-length-error');

                if (isBlur) {
                    if (!isNumeric) showValidationUI(input, false, typeErr);
                    else if (!isCorrectLength) showValidationUI(input, false, lenErr);
                    else showValidationUI(input, true);
                } else if (isNumeric && isCorrectLength) {
                    showValidationUI(input, true);
                }
                return;
            }

            if (input.id === 'dob') {
                const errorSpan = document.getElementById('dob-error');
                if (isBlur) {
                    if (value > today) {
                        showValidationUI(input, false);
                        if (errorSpan) errorSpan.style.display = 'block';
                    } else {
                        showValidationUI(input, true);
                        if (errorSpan) errorSpan.style.display = 'none';
                    }
                } else if (value <= today) {
                    showValidationUI(input, true);
                    if (errorSpan) errorSpan.style.display = 'none';
                }
                return;
            }

            if (isBlur) showValidationUI(input, input.checkValidity());
            else if (input.checkValidity()) showValidationUI(input, true);
        }

        inputs.forEach(input => {
            input.addEventListener('blur', () => validateInput(input, true));
            input.addEventListener('input', function() {
                validateInput(this, this.value !== ""); 
            });
        });

        // 5. Past Date Inputs
        document.querySelectorAll('.past-date-only').forEach(input => {
            input.setAttribute('max', today);

            input.addEventListener('change', function() {
                const selectedDate = this.value;
                if (selectedDate > today) {
                    Swal.fire({
                        title: 'Invalid Date',
                        text: 'Date cannot be in the future.',
                        icon: 'error',
                        confirmButtonColor: '#1A73E8'
                    });
                    this.value = ''; 
                    this.classList.add('border-red');
                } else if (selectedDate !== "") {
                    this.classList.remove('border-red');
                    this.classList.add('border-green');
                }
            });
        });

        // 6. Form Submission
        if (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); 
                
                // Ensure age is updated right before validation and submit
                if (dobInput && ageInput) {
                    ageInput.value = calculateAge(dobInput.value);
                }

                let firstInvalid = null;

                inputs.forEach(input => {
                    validateInput(input, true); 
                    
                    const value = input.value.trim();
                    const isRequiredBlank = input.hasAttribute('required') && value === "";
                    const isPatternInvalid = !input.checkValidity();
                    
                    let isNumericInvalid = false;
                    if (['contact_number', 'philhealth_no_member', 'philhealth_no_dep'].includes(input.name) && value !== "") {
                        const target = input.name === 'contact_number' ? 11 : 12;
                        isNumericInvalid = !(/^\d+$/.test(value)) || value.length !== target;
                    }

                    const isFutureDOB = (input.id === 'dob' && value > today);

                    if (isRequiredBlank || isPatternInvalid || isNumericInvalid || isFutureDOB) {
                        if (!firstInvalid) firstInvalid = input;
                    }
                });

                if (firstInvalid) {
                    firstInvalid.focus();
                    Swal.fire({
                        title: 'Incomplete Form',
                        text: 'Please fix the highlighted errors before submitting.',
                        icon: 'warning',
                        confirmButtonColor: '#1A73E8'
                    });
                } else {
                    openReviewModal();
                }
            });
        }
    });
</script>

{{-- Blade Notifications --}}
@if(session('success'))
    <script>
        Swal.fire({ title: 'Success!', text: "{{ session('success') }}", icon: 'success', confirmButtonColor: '#1A73E8' });
    </script>
@endif

@if($errors->any())
    <script>
        Swal.fire({ 
            title: 'Validation Error!', 
            html: '{!! implode("<br>", $errors->all()) !!}', 
            icon: 'error', 
            confirmButtonColor: '#1A73E8' 
        });
    </script>
@endif
<script>
    // ========== AUTO-CAPITALIZATION for name/place/text fields ==========
    // Excludes: email, passwords, numeric fields, dates, dropdowns (already
    // properly-cased options) - only applied to free-text name/place fields.
    document.addEventListener('DOMContentLoaded', function () {
        const capitalizeFields = [
            'last_name', 'first_name', 'mother_last', 'mother_first', 'mother_middle',
            'father_last', 'father_first', 'father_middle', 'address', 'pob',
            'religion', 'philhealth_member_name', 'mat_nbs_result', 'mat_hearing_result'
        ];
        capitalizeFields.forEach(function (name) {
            const el = document.querySelector('[name="' + name + '"]');
            if (!el) return;
            el.addEventListener('input', function () {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.replace(/\b\w/g, function (c) { return c.toUpperCase(); });
                this.setSelectionRange(start, end);
            });
        });
    });
</script>
<style>
    .review-modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 3000; align-items: center; justify-content: center; padding: 20px; }
    .review-modal-backdrop.show { display: flex; }
    .review-modal-box { background: #fff; width: 100%; max-width: 780px; max-height: 90vh; border-radius: 14px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 24px 48px rgba(0,0,0,0.22); }
    .review-modal-header { padding: 24px 32px 18px; border-bottom: 1px solid #E5E7EB; }
    .review-modal-title { font-size: 19px; font-weight: 700; color: #111827; margin: 0; }
    .review-modal-subtitle { font-size: 12.5px; color: #6B7280; margin-top: 5px; line-height: 1.5; }
    .review-modal-body { overflow-y: auto; flex: 1; padding: 26px 32px; background: #F9FAFB; }
    .review-section { background: #fff; border: 1px solid #E5E7EB; border-radius: 10px; padding: 20px 22px; margin-bottom: 18px; }
    .review-section:last-child { margin-bottom: 0; }
    .review-section-title { font-size: 12px; font-weight: 700; color: #1A73E8; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 16px; padding-bottom: 10px; border-bottom: 1px solid #EEF2F6; }
    .review-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 28px; }
    .review-item { display: flex; flex-direction: column; }
    .review-item.full-width { grid-column: 1 / -1; }
    .review-label { font-size: 10.5px; color: #9CA3AF; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 4px; }
    .review-value { font-size: 14px; color: #111827; font-weight: 500; word-break: break-word; line-height: 1.4; }
    .review-value.empty { color: #C4C9D1; font-style: italic; font-weight: 400; }
    .review-modal-footer { padding: 16px 26px; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap; }
    .review-btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 13.5px; cursor: pointer; border: none; }
    .review-btn-cancel { background: #fff; color: #6B7280; border: 1px solid #D1D5DB; }
    .review-btn-cancel:hover { background: #F3F4F6; }
    .review-btn-edit { background: #F3F4F6; color: #374151; }
    .review-btn-edit:hover { background: #E5E7EB; }
    .review-btn-confirm { background: #1A73E8; color: #fff; }
    .review-btn-confirm:hover { background: #1557B0; }
    @media (max-width: 600px) { .review-grid { grid-template-columns: 1fr; } }
</style>

<div id="reviewConfirmModal" class="review-modal-backdrop">
    <div class="review-modal-box">
        <div class="review-modal-header">
            <h3 class="review-modal-title">Review &amp; Confirm Patient Information</h3>
            <div class="review-modal-subtitle">Please review all details below before saving. The patient will only be registered after you click Confirm &amp; Register.</div>
        </div>
        <div class="review-modal-body" id="reviewModalBody"></div>
        <div class="review-modal-footer">
            <button type="button" class="review-btn review-btn-cancel" onclick="closeReviewModal()">Back / Cancel</button>
            <button type="button" class="review-btn review-btn-edit" onclick="closeReviewModal()">Edit</button>
            <button type="button" class="review-btn review-btn-confirm" onclick="confirmAndRegister()">Confirm & Register</button>
        </div>
    </div>
</div>

<script>
    const REVIEW_SECTIONS = [
        {
            title: 'Personal Information',
            fields: [
                ['last_name', 'Last Name'], ['first_name', 'First Name'], ['middle_initial', 'Middle Initial'],
                ['dob', 'Date of Birth'], ['gender', 'Gender'], ['civil_status', 'Civil Status'],
                ['pob', 'Place of Birth'], ['religion', 'Religion'], ['educational_attainment', 'Educational Attainment'],
                ['osca_pwd_no', 'OSCA/PWD No.'], ['four_ps_no', '4Ps No.'],
            ]
        },
        {
            title: 'Contact & Address',
            fields: [
                ['address', 'Address'], ['barangay', 'Barangay'], ['contact_number', 'Contact Number'], ['email', 'Email'],
            ]
        },
        {
            title: 'Family Information',
            fields: [
                ['family_number', 'Family No.'],
                ['mother_first', "Mother's First Name"], ['mother_middle', "Mother's Middle Name"], ['mother_last', "Mother's Last Name"],
                ['father_first', "Father's First Name"], ['father_middle', "Father's Middle Name"], ['father_last', "Father's Last Name"],
            ]
        },
        {
            title: 'PhilHealth Information',
            fields: [
                ['philhealth_type', 'PhilHealth Type'], ['philhealth_no_member', 'PhilHealth No. (Member)'],
                ['philhealth_no_dep', 'PhilHealth No. (Dependent)'], ['philhealth_member_name', 'PhilHealth Member Name'],
            ]
        },
    ];

    function getFieldValue(name) {
        const el = document.querySelector(`[name="${name}"]`);
        if (!el) return null;
        if (el.tagName === 'SELECT') {
            const opt = el.options[el.selectedIndex];
            return opt ? opt.textContent.trim() : el.value;
        }
        return el.value ? el.value.trim() : '';
    }

    function openReviewModal() {
        const body = document.getElementById('reviewModalBody');
        let html = '';

        REVIEW_SECTIONS.forEach(section => {
            const items = section.fields.map(([name, label]) => {
                const value = getFieldValue(name);
                if (value === null) return '';
                const display = value ? value : '<span class="review-value empty">Not provided</span>';
                return `
                    <div class="review-item">
                        <span class="review-label">${label}</span>
                        <span class="review-value">${value ? value : ''}${value ? '' : display}</span>
                    </div>`;
            }).join('');

            html += `
                <div class="review-section">
                    <div class="review-section-title">${section.title}</div>
                    <div class="review-grid">${items}</div>
                </div>`;
        });

        body.innerHTML = html;
        document.getElementById('reviewConfirmModal').classList.add('show');
    }

    function closeReviewModal() {
        document.getElementById('reviewConfirmModal').classList.remove('show');
    }

    function confirmAndRegister() {
        closeReviewModal();
        document.getElementById('registrationForm').submit();
    }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Patient Records</title>

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

    .welcome-text {
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
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
    }

    .role-admin { background: #9333EA; }
    .role-nurse { background: #10B981; }
    .role-bhw { background: #6366F1; }
    .role-doctor { background: #3B82F6; }

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

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .page-title {
        font-size: 22px;
        font-weight: bold;
        color: #111827;
        margin-bottom: 4px;
    }

    .page-subtitle {
        font-size: 13px;
        color: #6B7280;
    }

    .export-btn {
        background: #1D4ED8;
        color: white;
        padding: 10px 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 8px;
    }

    /* KPI Grid Wrapper */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-top: 20px;
        margin-bottom: 24px;
        width: 100%;
    }

    /* Base Card Styling — glass style */
    .kpi {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 22px 24px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.6);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .kpi::before {
        display: none;
    }

    /* Typography Styling */
    .kpi-title {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-number {
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
        margin-top: 0;
    }

    /* Interactive Feedback */
    .kpi:active {
        transform: translateY(-2px);
    }

    /* --- Unified Color Theme Assignments (Soft Glass Tints) --- */
    .kpi-grey { 
        background: rgba(243, 244, 246, 0.4); 
        border-color: rgba(209, 213, 219, 0.5);
    }
    .kpi-grey .kpi-title { color: #6B7280; }
    .kpi-grey .kpi-number { color: #111827; }

    .kpi-blue { 
        background: rgba(37, 99, 235, 0.06); 
        border-color: rgba(37, 99, 235, 0.15);
    }
    .kpi-blue .kpi-title { color: #2563EB; }
    .kpi-blue .kpi-number { color: #1E40AF; }

    .kpi-green { 
        background: rgba(16, 185, 129, 0.06); 
        border-color: rgba(16, 185, 129, 0.15);
    }
    .kpi-green .kpi-title { color: #059669; }
    .kpi-green .kpi-number { color: #065F46; }

    .kpi-purple { 
        background: rgba(139, 92, 246, 0.06);
        border-color: rgba(139, 92, 246, 0.15);
    }
    .kpi-purple .kpi-title { color: #7C3AED; }
    .kpi-purple .kpi-number { color: #5B21B6; }

    .search-row {
        display: flex;
        gap: 10px;
        margin-top: 16px;
    }

    .search-box {
        position: relative;
        width: 77%;
        margin-right: 50px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 10px 10px 38px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
    }

    .filter-box {
        position: relative;
        width: 16%;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        opacity: 0.5;
    }

    .table-card {
        background: white;
        margin-top: 16px;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        padding: 12px;
    }

    .table-header {
        font-weight: bold;
        padding-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 15px;
        font-size: 13px;
        border-top: 1px solid #E5E7EB;
        text-align: left;
    }

    .id-cell {
        color: #1D4ED8;
        font-weight: bold;
    }

    .muted {
        color: #6B7280;
    }

    .status-active {
        background: #DCFCE7;
        color: #166534;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
    }

    .view-link {
        color: #1D4ED8;
        font-weight: bold;
        text-decoration: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
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
            {{ session('admin_name') ?? session('user_name') ?? Auth::user()->name }}

            @php
                $user = Auth::user();

                $role = strtolower(session('admin_role') ?? $user->role ?? 'nurse');

                // Display badge text
                $displayRole = ($role === 'doctor' && $user->is_physician_in_charge == 1)
                    ? 'PIC'
                    : strtoupper($role);

                // Badge CSS class
                $roleClass = match($role) {
                    'admin' => 'role-admin',
                    'nurse' => 'role-nurse',
                    'doctor' => 'role-doctor',
                    default => 'role-bhw',
                };
            @endphp

            <span class="role {{ $roleClass }}">
                {{ $displayRole }}
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

    <div class="top-bar">
        <div>
            <div class="page-title">Patient Records</div>
            <div class="page-subtitle">View and manage registered patient information</div>
        </div>
        <button class="export-btn" onclick="window.print()">Export List</button>
    </div>

    <div class="kpi-row">

        <div class="kpi kpi-grey" onclick="filterPatients('all')">
            <div class="kpi-title">Total Registered Patients</div>
            <div class="kpi-number">{{ $totalPatientsCount }}</div>
        </div>

        <div class="kpi kpi-blue" onclick="filterPatients('active')">
            <div class="kpi-title">Active This Month</div>
            <div class="kpi-number">{{ $activePatientsCount }}</div>
        </div>

        <div class="kpi kpi-green" onclick="filterPatients('new')">
            <div class="kpi-title">New This Month</div>
            <div class="kpi-number">{{ $newPatientsCount }}</div>
        </div>

        <div class="kpi kpi-purple" onclick="filterPatients('senior')">
            <div class="kpi-title">Senior Citizens (60+)</div>
            <div class="kpi-number">{{ $seniorCount }}</div>
        </div>
    </div>
    
    <div class="search-row">
        <div class="search-box">
            <img src="/icons/search.png" class="search-icon">
            <input type="text" id="patientSearch" placeholder="Search by Patient Name or Family Number..." onkeyup="filterTable()">
        </div>
        <div class="filter-box">
            <img src="/icons/filter.png" class="search-icon">
            <select id="barangayFilter" onchange="filterTable()" style="width: 100%; padding: 10px 10px 10px 38px; border: 1px solid #E5E7EB; border-radius: 8px; appearance: none; background: white; font-size: 13px; cursor: pointer;">
                <option value="">All Barangays</option>
                <option value="Barangay 91">Barangay 91</option>
                <option value="Barangay 92">Barangay 92</option>
                <option value="Barangay 93">Barangay 93</option>
                <option value="Barangay 94">Barangay 94</option>
                <option value="Barangay 95">Barangay 95</option>
                <option value="Barangay 97">Barangay 97</option>
                <option value="Barangay 103">Barangay 103</option>
                <option value="Barangay 104">Barangay 104</option>
            </select>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header">All Patients</div>
        <table id="patientTable">
            <thead>
                <tr>
                    <th>PATIENT ID</th>
                    <th>PATIENT NAME</th>
                    <th>BARANGAY</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="patients-table-body">
                @php
                    // Fetch arrays of IDs of patients who fit these categories for fast JS processing
                    $activePatientIds = \App\Models\TriageRecord::where('created_at', '>=', now()->startOfMonth())
                                        ->pluck('patient_id')
                                        ->toArray();
                @endphp

                @forelse($patients as $patient)
                <tr data-age="{{ $patient->age }}" 
                    data-created="{{ $patient->created_at }}" 
                    data-active-month="{{ $patient->triageRecords->where('created_at', '>=', now()->startOfMonth())->count() > 0 ? 'true' : 'false' }}">
                    
                    <td class="id-cell">{{ $patient->patient_id }}</td>
                    <td><b>{{ $patient->last_name }}, {{ $patient->first_name }}</b></td>
                    <td class="muted">{{ $patient->barangay }}</td>
                    <td><span class="status-active">Registered</span></td>
                    <td>
                        <a href="javascript:void(0)" class="view-link" onclick="openPatientModal({{ $patient->id }})">
                            <img src="/icons/view-details.png" width="16"> View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding: 40px; color: #6B7280;">No patient records found in the system.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</div>

<x-patient-details-modal />

<script>
    // Global search and drop-down filter functionality
function filterTable() {
    let searchInput = document.getElementById("patientSearch").value.toUpperCase().trim();
    let barangayFilter = document.getElementById("barangayFilter").value.toUpperCase();
    let table = document.getElementById("patientTable");
    let tr = table.getElementsByTagName("tr");

    let isCompositeSearch = searchInput.indexOf('/') > -1;
    let targetFamilyNum = '';
    let targetBarangayNum = '';

    if (isCompositeSearch) {
        let parts = searchInput.split('/');
        targetFamilyNum = parseInt(parts[0].trim(), 10).toString(); // Ginagawang purong numero (e.g., "01" -> "1")
        targetBarangayNum = parts[1].trim();                        // Kinukuha ang Barangay text component
    }

    for (let i = 1; i < tr.length; i++) {
        let idTd = tr[i].getElementsByTagName("td")[0];   // Column element holding "PAT-2026-0009"
        let nameTd = tr[i].getElementsByTagName("td")[1]; // Column holding Patient Name
        let brgyTd = tr[i].getElementsByTagName("td")[2]; // Column holding Barangay name text description

        if (idTd && nameTd && brgyTd) {
            let idText = (idTd.textContent || idTd.innerText).toUpperCase().trim();
            let nameText = (nameTd.textContent || nameTd.innerText).toUpperCase();
            let brgyText = (brgyTd.textContent || brgyTd.innerText).toUpperCase();

            let matchesSearch = false;

            if (isCompositeSearch) {
                // Kukunin lang natin ang mga numero mula sa custom ID text (e.g., "PAT-2026-0009" -> "20260009")
                let onlyNumbers = idText.replace(/[^0-9]/g, '');
                
                // Kukunin ang huling 4 digits na siyang counter index increment (e.g., "20260009" -> "0009" -> "9")
                let cleanIdNum = onlyNumbers.length >= 4 ? parseInt(onlyNumbers.substring(onlyNumbers.length - 4), 10).toString() : '';

                // I-verify kung ang nakuha nating increment sequence ay tugma sa tina-type bago ang slash
                let matchesFamilyId = (cleanIdNum === targetFamilyNum);
                let matchesBarangayCode = brgyText.indexOf(targetBarangayNum) > -1;

                if (targetFamilyNum !== '' && targetBarangayNum !== '') {
                    matchesSearch = matchesFamilyId && matchesBarangayCode;
                } else if (targetFamilyNum !== '') {
                    matchesSearch = matchesFamilyId;
                }
            } else {
                // Orihinal na search parameter validation para sa pangalan o buong string length lookup
                matchesSearch = idText.indexOf(searchInput) > -1 || nameText.indexOf(searchInput) > -1;
            }
            
            // Cross-reference checking kasama ang iyong filter dropdown menu configuration
            let matchesBarangay = barangayFilter === "" || brgyText.indexOf(barangayFilter) > -1;

            if (matchesSearch && matchesBarangay) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

    function filterPatients(type) {
    const rows = document.querySelectorAll("#patients-table-body tr"); 
    
    rows.forEach(row => {
        if(row.cells.length === 1) return; // Laktawan ang empty layout warnings row

        const age = parseInt(row.getAttribute("data-age")) || 0;
        const createdDateStr = row.getAttribute("data-created");
        // Kukunin natin kung nag-triage ba sila ngayong buwan ("true" o "false")
        const isActiveThisMonth = row.getAttribute("data-active-month") === "true";
        
        let show = true;

        if (type === "all") {
            show = true;
        } else if (type === "senior") {
            show = age >= 60;
        } else if (type === "active") {
            // FIX: Ipapakita lang ang pasyente kung dumaan sila sa queue ng triage ngayong buwan
            show = isActiveThisMonth;
        } else if (type === "new") {
            if (!createdDateStr) {
                show = false;
            } else {
                const today = new Date();
                const createdDate = new Date(createdDateStr);
                
                // Match current calendar month and year (Para sa mga bagong rehistro lang)
                show = createdDate.getMonth() === today.getMonth() && 
                       createdDate.getFullYear() === today.getFullYear();
            }
        }

        row.style.display = show ? "" : "none";
    });
}
</script>

</body>
</html>
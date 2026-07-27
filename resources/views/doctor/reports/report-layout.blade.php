@php date_default_timezone_set('Asia/Manila'); @endphp


<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>{{ $title ?? 'Doctor Reports' }}</title>


<style>
*{box-sizing:border-box;}


body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#F9FAFB;
    overflow-x:hidden;
}


.container{display:flex;}


.sidebar{
    width:260px;
    height:100vh;
    background:white;
    border-right:1px solid #E5E7EB;
    padding:24px;
    position:fixed;
    top:0;
    left:0;
    overflow-y:auto;
}


.sidebar-header{
    display:flex;
    align-items:center;
    gap:14px;
    padding-bottom:14px;
    border-bottom:1px solid #E5E7EB;
    margin-bottom:10px;
}


.logo{width:40px;height:40px;border-radius:50%;}
        .brand-wrapper{ 
            display: flex; 
            flex-direction: column; 
            line-height: 1.2; 
        }
        .brand{ 
            font-weight: bold; 
            color: #1E3A8A; 
            font-size: 13.3px; 
        }
.sub{font-size:11px;color:#6B7280;}


.group{
    margin-top:22px;
    font-size:12px;
    font-weight:bold;
    color:#9CA3AF;
    text-transform:uppercase;
}


.nav-item{
    display:flex;
    align-items:center;
    gap:14px;
    padding:12px 10px;
    margin-top:6px;
    text-decoration:none;
    color:#4B5563;
    border-radius:6px;
    font-size:15px;
}


.nav-icon{width:24px;height:24px;object-fit:contain;}


.nav-item.active{
    background:#EFF6FF;
    color:#1A73E8;
    border-left:4px solid #1A73E8;
    font-weight:bold;
}


.nav-item:hover{background:#F3F4F6;}


.main{
    margin-left:250px;
    width:calc(100% - 285px);
    min-height:100vh;
    padding:24px;
}


.header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}


.welcome-text{
    font-size:14px;
    color:#374151;
    margin-bottom:5px;
}


.welcome-name{
    font-size:18px;
    font-weight:bold;
    color:#111827;
}


.role{
    background:#3B82F6;
    color:white;
    padding:3px 10px;
    border-radius:20px;
    font-size:12px;
    margin-left:6px;
}


.right{
    text-align:right;
    font-size:12px;
    color:#111827;
}


.header-divider{
    height:1px;
    background:#E5E7EB;
    margin:16px 0;
}


.page-title{
    font-size:22px;
    font-weight:bold;
    color:#111827;
    margin-bottom:16px;
}


.report-controls{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    flex-wrap:wrap;
    gap:16px;
    margin-bottom:24px;
}


.filters{
    display:flex;
    gap:14px;
    flex-wrap:wrap;
    align-items:flex-end;
}


.filter-group{
    display:flex;
    flex-direction:column;
    font-size:12px;
    color:#374151;
    gap:4px;
}


.filter-group label{font-weight:500;}


.filter-group input,
.filter-group select{
    padding:0 10px;
    height:38px;
    border:1px solid #D1D5DB;
    border-radius:8px;
    font-size:13px;
    background:white;
}


.btn-primary{
    background:#1A73E8;
    color:white;
    border:none;
    padding:9px 16px;
    border-radius:8px;
    font-size:13px;
    cursor:pointer;
}


.btn-outline{
    border:1px solid #D1D5DB;
    background:white;
    padding:9px 14px;
    border-radius:8px;
    font-size:13px;
    cursor:pointer;
}


.content-card{
    background:white;
    border-radius:14px;
    border:1px solid #E5E7EB;
    padding:28px;
}


.summary-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:14px;
    margin-bottom:20px;
}


.summary-box{
    border:1px solid #E5E7EB;
    border-radius:12px;
    padding:16px;
    background:#FAFAFA;
}


.summary-box span{
    font-size:12px;
    color:#6B7280;
}


.summary-box h3{
    font-size:20px;
    margin-top:6px;
}


.table-container{
    overflow-x:auto;
    border:1px solid #E5E7EB;
    border-radius:10px;
}


table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
    background:white;
}


thead th{
    background:#F9FAFB;
    font-weight:600;
    text-align:left;
    padding:14px 12px;
    border-bottom:1px solid #E5E7EB;
    font-size:13px;
    color:#374151;
}


tbody td{
    padding:12px;
    border-bottom:1px solid #F1F5F9;
    color:#111827;
}


.system-insight{
    margin-top:18px;
    padding:16px 18px;
    border-radius:12px;
    background:#F0F9FF;
    border:1px solid #BAE6FD;
}


.system-insight strong{
    display:block;
    font-size:13px;
    color:#0369A1;
    margin-bottom:6px;
}


.system-insight p{
    font-size:13px;
    margin:0;
    color:#1F2937;
    line-height:1.5;
}


.badge,.risk-badge{
    padding:4px 10px;
    border-radius:8px;
    font-size:12px;
    border:1px solid #E5E7EB;
    background:white;
}


.logout-btn{
    width:100%;
    text-align:left;
    border:none;
    background:none;
    cursor:pointer;
}
</style>
</head>


<body>


<div class="container">


<div class="sidebar">
    <div class="sidebar-header">
        <img src="/bhclogo.jpg" class="logo">
        <div class="brand-wrapper">
            <div class="brand">Barangay Health System</div>
            <div class="sub">Health Information System</div>
        </div>
    </div>


    <div class="group">MAIN</div>
    <a class="nav-item" href="/doctor/dashboard">
        <img src="/icons/dashboard2.png" class="nav-icon">Dashboard
    </a>


    <div class="group">PATIENT</div>
    <a class="nav-item" href="/doctor/patient-records">
        <img src="/icons/patient-records.png" class="nav-icon">Patient Records
    </a>


    <a class="nav-item" href="/doctor/triage">
        <img src="/icons/patient-triage.png" class="nav-icon">Patient Triage
    </a>


    <div class="group">REPORTS</div>
    <a class="nav-item active" href="/doctor/reports">
        <img src="/icons/reports.png" class="nav-icon">Reports
    </a>


    <form method="POST" action="/logout" style="margin-top:20px;">
        @csrf
        <button class="nav-item logout-btn">
            <img src="/icons/logout.png" class="nav-icon">Log Out
        </button>
    </form>
</div>


<div class="main">


    <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ session('user_name') }}
                    <span class="role">DOCTOR</span>
                </div>
            </div>

        <div class="right">
            <b>Velasquez Health Center</b><br>
            {{ date('F d, Y | h:i A') }}
        </div>
    </div>


    <div class="header-divider"></div>


    @yield('content')


</div>
</div>


</body>
</html>

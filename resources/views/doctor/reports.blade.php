@php
    date_default_timezone_set('Asia/Manila');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Reports</title>

<style>
body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#F9FAFB;
    overflow-x:hidden;
}

.container{ display:flex; }

.sidebar{
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

.sidebar-header{
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid #E5E7EB;
            margin-bottom: 10px;
        }

        .logo{ 
            width: 40px; 
            height: 40px; 
            border-radius: 50%; 
        }

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
        .sub{ 
            font-size: 11px;
            color: #6B7280;
        }

        .group{
            margin-top: 22px;
            font-size: 11px;
            font-weight: bold;
            color: #9CA3AF;
            text-transform: uppercase;
        }

 .nav-item{
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

        .nav-icon{ 
            width: 22px; 
            height: 22px; 
            object-fit: contain; 
            flex-shrink: 0; 
        }

        .nav-item.active{
            background: #EFF6FF;
            color: #1A73E8;
            border-left: 4px solid #1A73E8;
            font-weight: bold;
        }

        .nav-item:hover{ 
            background: #F3F4F6; 
        }

        .main{
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 24px;
            box-sizing: border-box;
        }

        .header{ 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
        }
        .welcome-text{ 
            font-size: 14px; 
            color: #374151; 
            margin-bottom: 5px; 
        }
        .welcome-name{ 
            font-weight: bold; 
            color: #1F2937; 
            font-size: 18px; 
        }

        .role{
            background: #3B82F6; 
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 6px;
        }

        .right{ 
            text-align: right; 
            font-size: 12px; 
            color: #374151; 
        }
        .header-divider{ 
            width: 100%; 
            height: 1px; 
            background: #E5E7EB; 
            margin: 16px 0; 
        }
        .page-title{ 
            font-size: 22px; 
            font-weight: bold; 
            color: #111827; 
            margin-bottom: 12px; 
        }

.content-card{
    background:white;
    border-radius:12px;
    border:1px solid #E5E7EB;
    padding:25px;
}

.card-title{
    font-size:18px;
    font-weight:700;
    margin-bottom:25px;
}

.action-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px;
}

.action-card{
    display:flex;
    align-items:center;
    gap:15px;
    padding:18px;
    border:1px solid #E5E7EB;
    border-radius:12px;
    text-decoration:none;
    color:inherit;
    min-height:90px;
}

.action-card img{
    width:24px;
}

.action-card span{
    font-size:15px;
    font-weight:600;
}

.action-card:hover{
    background:#FAFAFA;
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

        @php
            $user = Auth::user();

            $displayRole = (
                strtolower($user->role) === 'doctor' &&
                $user->is_physician_in_charge == 1
            ) ? 'PIC' : strtoupper($user->role);
        @endphp

        <div class="welcome-name">
            {{ session('user_name') ?? $user->name }}

            <span class="role">
                {{ $displayRole }}
            </span>
        </div>
    </div>

    <div class="right">
        <b>Velasquez Health Center</b><br>
        {{ date('F d, Y | h:i A') }}
    </div>
</div>

    <div class="header-divider"></div>

    <div class="content-card">
        <div class="card-title">Reports Overview</div>

        <div class="action-grid">

            <a href="{{ route('doctor.reports.patient-records') }}" class="action-card">
                <img src="/icons/patient-records.png">
                <span>Patient Reports</span>
            </a>

            <a href="{{ route('doctor.reports.risk') }}" class="action-card">
                <img src="/icons/AI-forecast.png">
                <span>Patient Risk Reports</span>
            </a>

            <a href="{{ route('doctor.reports.medicine') }}" class="action-card">
                <img src="/icons/medicine-inventory.png">
                <span>Medicine Inventory</span>
            </a>

            <a href="{{ route('doctor.reports.dispensing') }}" class="action-card">
                <img src="/icons/reports.png">
                <span>Dispensing Reports</span>
            </a>

            <a href="{{ route('doctor.reports.operational') }}" class="action-card">
                <img src="/icons/reports.png">
                <span>Operational Reports</span>
            </a>

        </div>
    </div>

</div>
</div>

</body>
</html>



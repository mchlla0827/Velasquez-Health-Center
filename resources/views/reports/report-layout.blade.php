@php date_default_timezone_set('Asia/Manila'); @endphp

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>{{ $title ?? 'Reports' }}</title>

<style>
*{box-sizing:border-box;}

body{margin:0; font-family:Arial, sans-serif; background:#F9FAFB; overflow-x:hidden;}
.container{display:flex;}

.main{
    margin-left:260px;
    width:calc(100% - 260px);
    min-height:100vh;
    padding:24px;
    box-sizing:border-box;
}

.header{display:flex; justify-content:space-between; align-items:flex-start;}
.welcome-text{font-size:14px; color:#374151; margin-bottom:5px;}
.welcome-name{font-size:18px; font-weight:bold; color:#111827;}

.role{
    color:white; padding:3px 10px; border-radius:20px;
    font-size:12px; margin-left:6px;
}

.right{text-align:right; font-size:12px; color:#111827;}
.header-divider{height:1px; background:#E5E7EB; margin:16px 0;}

.page-title{font-size:22px; font-weight:bold; color:#111827; margin-bottom:16px;}

.report-controls{
    display:flex; justify-content:space-between; align-items:flex-end;
    flex-wrap:wrap; gap:16px; margin-bottom:24px;
}
.filters{display:flex; gap:14px; flex-wrap:wrap; align-items:flex-end;}
.filter-group{display:flex; flex-direction:column; font-size:12px; color:#374151; gap:4px;}
.filter-group label{font-weight:500;}
.filter-group input, .filter-group select{
    padding:0 10px; height:38px; border:1px solid #D1D5DB;
    border-radius:8px; font-size:13px; background:white;
}

.btn-primary{
    background:#1A73E8; color:white; border:none; padding:9px 16px;
    border-radius:8px; font-size:13px; cursor:pointer;
}
.btn-outline{
    border:1px solid #D1D5DB; background:white; padding:9px 14px;
    border-radius:8px; font-size:13px; cursor:pointer;
}

.content-card{background:white; border-radius:14px; border:1px solid #E5E7EB; padding:28px;}

.summary-grid{
    display:grid; grid-template-columns:repeat(4,1fr);
    gap:14px; margin-bottom:20px;
}
.summary-box{border:1px solid #E5E7EB; border-radius:12px; padding:16px; background:#FAFAFA;}
.summary-box span{font-size:12px; color:#6B7280;}
.summary-box h3{font-size:20px; margin-top:6px;}

.table-container{overflow-x:auto; border:1px solid #E5E7EB; border-radius:10px;}
table{width:100%; border-collapse:collapse; font-size:14px; background:white;}
thead th{
    background:#F9FAFB; font-weight:600; text-align:left;
    padding:14px 12px; border-bottom:1px solid #E5E7EB;
    font-size:13px; color:#374151;
}
tbody td{padding:12px; border-bottom:1px solid #F1F5F9; color:#111827;}

.badge, .risk-badge{
    padding:4px 10px; border-radius:8px;
    font-size:12px; border:1px solid #E5E7EB; background:white;
}
</style>
</head>

<body>

@php
    $__u = Auth::user();
    $__role = strtolower($__u->role ?? 'user');
    $__isPIC = ($__role === 'doctor' && (string) $__u->is_physician_in_charge === '1');
    $__displayRole = $__isPIC ? 'PIC' : strtoupper($__role);
    $__roleColor = match($__role) {
        'admin'  => '#9333EA',
        'nurse'  => '#10B981',
        'doctor' => '#3B82F6',
        'bhw'    => '#6366F1',
        default  => '#6B7280',
    };
@endphp

<div class="container">

<x-sidebar />

<div class="main">

    <div class="header">
        <div>
            <div class="welcome-text">Welcome back,</div>
            <div class="welcome-name">
                {{ $__u->name ?? session('user_name') }}
                <span class="role" style="background: {{ $__roleColor }};">{{ $__displayRole }}</span>
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
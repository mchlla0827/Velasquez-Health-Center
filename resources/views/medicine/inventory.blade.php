@php
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;

// 1. DATA FETCHING
$medicines = Medicine::latest()->get();


// 2. EXACT ROLE DEFINITION
$role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw');
$userName = Auth::user()->name ?? session('admin_name') ?? session('user_name') ?? ucfirst($role);


// 3. ACTION PERMISSIONS
$canViewInventory = (
    $role === 'admin' ||
    $role === 'nurse' ||
    $role === 'bhw' ||
    ($role === 'doctor' && $is_pic === 1)
);

$canAddMedicine = ($role === 'admin');
$canEditOrStock = ($role === 'admin' || $role === 'nurse');
$canDelete = ($role === 'admin');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/bhclogo.jpg">
<meta charset="UTF-8">
<title>Medicine Inventory</title>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #F9FAFB;
        overflow-x: hidden;
    }

    .container { display: flex; }

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

    .logo{ width: 40px; height: 40px; border-radius: 50%; }

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

    .nav-item:hover { 
    background: #F3F4F6; 
}

/* New modifier class for form buttons inside the sidebar */
.nav-btn {
    width: 100%;
    text-align: left;
    border: none;
    background: transparent; /* Changed from 'none' so hover states can override it */
    cursor: pointer;
    font-family: inherit; /* Ensures the font matches your links */
}

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

    .welcome-text{ font-size: 14px; color: #374151; margin-bottom: 5px; }

    .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }

    .role {
        background: #9333EA;
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        margin-left: 6px;
    }

    .right { text-align: right; font-size: 12px; color: #374151; }

    .header-divider {
        width: 100%;
        height: 1px;
        background: #E5E7EB;
        margin: 16px 0;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: -0.02em;
        margin: 0;
    }

    .page-subtitle{
        font-size: 14px;
        font-weight: 400;
        color: #1e293b;
        margin-top: 4px;
        font-style: italic;
    }

    /* ================= TABLE ================= */
    .card {
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 16px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #E5E7EB;
    }

    th {
        text-align: left;
        background: #F9FAFB;
        font-size: 12px;
        text-transform: uppercase;
    }

    .low { color: red; font-weight: bold; }
    .ok { color: green; font-weight: bold; }

/* ================= NEW INVENTORY UI ================= */

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .page-subtitle {
        font-size: 13px;
        color: #6B7280;
        margin-top: 4px;
    }

    .add-btn {
        background: #2563EB;
        color: white;
        padding: 11px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.2);
        transition: background-color 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
    }
    .add-btn:hover { background: #1D4ED8; transform: translateY(-1px); box-shadow: 0 4px 8px rgba(37, 99, 235, 0.22); }
    .add-btn:focus-visible { outline: 3px solid rgba(37, 99, 235, 0.2); outline-offset: 2px; }

/* KPI Grid Wrapper */
.kpi-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 20px;
    margin-bottom: 24px;
    width: 100%;
}

/* Base Card Styling — matches the glass style */
.kpi {
    background: white;
    padding: 18px 20px;
    border-radius: 12px;
    border: 1px solid #E5E7EB;
    cursor: pointer;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* Remove old left border accent — replaced with background tint below */
.kpi::before {
    display: none;
}

/* Typography Styling — matches the example */
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

/* --- Color Theme Assignments — now as soft tints --- */

/* 1. Total Medicines (Grey Theme) */
.kpi-grey { 
    background: rgba(243, 244, 246, 0.4); 
    border-color: rgba(209, 213, 219, 0.5);
}
.kpi-grey .kpi-title { color: #6B7280; }
.kpi-grey .kpi-number { color: #111827; }

/* 2. Low Stock (Yellow/Amber Theme) */
.kpi-yellow { 
    background: rgba(245, 158, 11, 0.08); 
    border-color: rgba(245, 158, 11, 0.2);
}
.kpi-yellow .kpi-title { color: #B45309; }
.kpi-yellow .kpi-number { color: #78350F; }

/* 3. Critical Stock (Red Theme) */
.kpi-red { 
    background: rgba(239, 68, 68, 0.08); 
    border-color: rgba(239, 68, 68, 0.2);
}
.kpi-red .kpi-title { color: #DC2626; }
.kpi-red .kpi-number { color: #991B1B; }

.kpi-orange { 
    background: rgba(37, 99, 235, 0.08);
    border-color: rgba(37, 99, 235, 0.2);
}
.kpi-orange .kpi-title { color: #2563EB; }
.kpi-orange .kpi-number { color: #1E40AF; }
.kpi-expiry { cursor: pointer; }

.kpi:active {
    transform: translateY(-2px);
}
.kpi:hover, .kpi:focus-visible {
    border-color: #93C5FD;
    box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
    transform: translateY(-2px);
}

/* ALERT CONTAINER */
.alert {
    margin-top: 16px;
    background: #FFF5F5; 
    border-left: 4px solid #EF4444; 
    border-radius: 12px; /* Smoother corner matching your KPI cards */
    padding: 16px 20px; /* Slightly more vertical breathing room */
    box-sizing: border-box;
    box-shadow: 0 1px 2px rgba(239, 68, 68, 0.05);
}

.alert-success {
    background: #F0FDF4; 
    border-left-color: #22C55E; 
    box-shadow: 0 1px 2px rgba(34, 197, 94, 0.05);
}

.alert-content {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Pushes text left, button right */
    gap: 16px;
    flex-wrap: wrap; /* Prevents breaking on smaller viewports */
}

.alert-info-group {
    display: flex;
    align-items: center;
    gap: 16px;
}

.alert-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #FEE2E2; /* Soft red background */
}

/* Matches your text color (#DC2626) by filtering black icons to deep red */
.alert-icon-box img {
    width: 20px;
    height: 20px;
    object-fit: contain;
    filter: invert(24%) sepia(91%) saturate(4646%) hue-rotate(348deg) brightness(85%) contrast(97%);
}

/* Success Mode overrides */
.alert-success .alert-icon-box {
    background: #DCFCE7; /* Soft green background */
}

/* Matches your success text color (#166534) by filtering black icons to deep green */
.alert-success .alert-icon-box img {
    filter: invert(30%) sepia(87%) saturate(468%) hue-rotate(95deg) brightness(93%) contrast(93%);
}

/* TYPOGRAPHY */
.alert-text-wrapper {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.alert-headline {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em; /* Elegant tracker gap spacing */
    color: #991B1B; 
}

.alert-subtitle {
    font-size: 14px;
    font-weight: 500;
    color: #4B1A1A; /* Slightly darker slate-brown for beautiful contrast */
}

.alert-highlight {
    color: #DC2626; /* Makes the number count step out forward */
    font-weight: 700;
}

/* Success Text Overrides */
.alert-success .alert-headline { color: #166534; }
.alert-success .alert-subtitle { color: #14532D; }

/* RIGHT SIDE: INTERACTIVE RESTOCK BUTTON */
.alert-action-btn {
    background: #DC2626;
    color: white;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.15s ease-in-out;
    box-shadow: 0 1px 2px rgba(185, 28, 28, 0.2);
}

.alert-action-btn:hover {
    background: #B91C1C;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(185, 28, 28, 0.15);
}

.alert-action-btn:active {
    transform: translateY(0);
}

    /* PRIORITY TABLE */
    .priority-card {
        margin-top: 16px;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        overflow: hidden;
    }

    .priority-header {
        background: #EFF6FF;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(128, 128, 128, 0.466);
    }

    .badge-red {
        background: #FEE2E2;
        color: #B91C1C;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
    }

        /* SEARCH + FILTER */
    .search-row {
        display: flex;
        gap: 10px;
        margin-top: 16px;
        align-items: stretch;
    }

    .search-box {
        display: flex;
        align-items: center;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 0 10px;
        background: white;
        flex: 1;
        min-width: 220px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }

    .search-box input {
        border: none;
        outline: none;
        padding: 10px;
        width: 100%;
    }

    .search-icon {
        width: 18px;
        opacity: 0.6;
    }

    .filter-box {
        display: flex;
        align-items: center;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 0 10px;
        background: white;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }

    .filter-box input {
        border: none;
        outline: none;
        padding: 10px;
        width: 120px;
    }

    .reset-filter-btn {
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        padding: 0 14px;
        background: #FFFFFF;
        color: #475569;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }
    .reset-filter-btn:hover { background: #F8FAFC; border-color: #94A3B8; color: #1E293B; }
    .reset-filter-btn:focus-visible { outline: 3px solid rgba(37, 99, 235, 0.18); outline-offset: 2px; }

    .medicine-name {
        margin-bottom: 5px;
        font-size: 15px;
    }

    .brand {
        font-size: 11px;
        color: #6B7280;
    }

    .stock-normal { font-weight: bold; }
    .stock-low { color: #F59E0B; font-weight: bold; }
    .stock-critical { color: #EF4444; font-weight: bold; }

    .status-pill {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-normal {
        background: #DCFCE7;
        color: #166534;
    }

    .status-low {
        background: #FEF9C3;
        color: #92400E;
    }

    .status-critical {
        background: #FEE2E2;
        color: #991B1B;
    }

    .action-group form {
    margin: 0;
    display: inline-flex;
}

/* Unified Base Button Style */
.action-btn {
    background: transparent;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600; /* Makes typography crisp and readable */
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    display: inline-flex;
    align-items: center;
    font-family: inherit;
}

/* View Details Action Styling */
.view-btn {
    color: #2563EB; /* Dashboard Blue */
}

.view-btn:hover {
    color: #1D4ED8;
    background: #EFF6FF; /* Clean hover backdrop instead of sliding text */
}

/* Delete Action Styling */
.delete-btn {
    color: #DC2626; /* Warning/Destructive Red */
}

.delete-btn:hover {
    color: #B91C1C;
    background: #FEF2F2; /* Subtle warning backdrop */
}

/* Active Click Response */
.action-btn:active {
    transform: scale(0.96); /* Micro-interaction scale down */
}
    .icon-box {
        width: 22px;
        height: 22px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: -15px;
    }

    .inventory-table-wrap { overflow-x: auto; }
    .inventory-table-wrap table {
        min-width: 760px;
        table-layout: fixed;
    }
    .inventory-table th,
    .inventory-table td {
        padding: 14px 20px;
        vertical-align: middle;
    }
    .inventory-table th {
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        line-height: 1.3;
        white-space: nowrap;
    }
    .inventory-table th:nth-child(1),
    .inventory-table td:nth-child(1) { width: 44%; text-align: left; }
    .inventory-table th:nth-child(2),
    .inventory-table td:nth-child(2) { width: 18%; text-align: center; }
    .inventory-table th:nth-child(3),
    .inventory-table td:nth-child(3) { width: 18%; text-align: center; }
    .inventory-table th:nth-child(4),
    .inventory-table td:nth-child(4) { width: 26%; text-align: center; }
    .inventory-table td:nth-child(2) { font-size: 16px; }
    .inventory-table td:nth-child(4) .action-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        white-space: nowrap;
    }
    .inventory-table-wrap tbody tr { transition: background-color 0.15s ease; }
    .inventory-table-wrap tbody tr:hover { background: #F8FAFC; }

    .icon-box img {
        width: 14px;
        height: 14px;
    }
    /* MODAL BACKGROUND */
    /* Modal Background */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* semi-transparent black */
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.2s ease;
}

/* Show class for animation */
.modal-overlay.show {
    display: flex;
    opacity: 1;
}

/* Modal Box */
.modal {
    background: #ffffff;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(20px);
    transition: transform 0.2s ease;
}

.modal-overlay.show .modal {
    transform: translateY(0);
}

/* Modal Header */
.modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.modal-subtitle {
    font-size: 13px;
    color: #6b7280;
    font-style: italic;
}

.close-btn {
    font-size: 22px;
    color: #9ca3af;
    cursor: pointer;
    line-height: 1;
}

.close-btn:hover {
    color: #111827;
}

/* Form Styles */
.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
}

.form-input {
    box-sizing: border-box;
    font-size: 14px;
}

.form-input:focus {
    outline: none;
    border-color: #165DFF;
    box-shadow: 0 0 0 2px rgba(22, 93, 255, 0.1);
}

/* Modal Footer */
.modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

.btn-submit:hover {
    background-color: #0045e6 !important;
}

.btn-close:hover {
    background-color: #e5e7eb !important;
}
    /* MODAL BOX */
    .modal {
        background: white;
        width: 90%;
        max-width: 1100px;
        border-radius: 12px;
        padding: 40px;
        height: 70%;
        max-height: 100vh;
        overflow-y: auto;
    }

    /* HEADER */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .modal-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 3px;
    }

    .modal-subtitle {
        font-size: 13px;
        color: #6B7280;
    }

    .close-btn {
        cursor: pointer;
        font-size: 18px;
        color: #6B7280;
    }

    .close-btn:hover{
        color: #2c2d30;
        font-weight: bold;
    }

    .close-btn:active{
        transform: translateX(-2px);
    }
/* SUMMARY - SAME COLOR | DISTINCT WEIGHTS | TEXT CLOSE TO BLACK | GLASS STYLE */
.modal-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: 16px;
}

/* BASE CARD STYLE - SAME SOFT BLUE GLASS FOR ALL */
.summary-card {
    border-radius: 10px;
    padding: 14px 12px;
    background: rgba(22, 93, 255, 0.12);
    border: 1px solid rgba(22, 93, 255, 0.2);
    backdrop-filter: blur(8px);
    transition: all 0.2s ease;
}
.summary-card:hover {
    transform: translateY(-1px);
}

/* 🏷️ LABEL TEXT: Medium weight, close to black */
.summary-card > div {
    color: #1A1A1A; /* Very dark gray — close to black */
    font-weight: 500; /* Medium — lighter weight */
    font-size: 14px;
}

/* 🔢 VALUE TEXT: Bold weight, pure black — clearly different */
.summary-value {
    font-weight: 700 !important; /* Bold — much thicker */
    margin-top: 6px;
    font-size: 16px;
    color: #000000 !important; /* Pure black */
}

/* Override old blue-text class */
.blue-text {
    color: #000000 !important;
    font-weight: 700 !important;
}

    /* BATCH SECTION & TABLE - MATCHING DESIGN SYSTEM */
.batch-section {
    margin-top: 20px;
}

.batch-title {
    font-weight: 700;
    font-size: 15px;
    color: #111111; /* Close to black */
    margin-bottom: 12px;
}

.bold {
    font-weight: 700;
}

.muted {
    font-size: 12px;
    color: #6B7280;
}

/* STATUS BADGES - CLEANER & MORE MODERN */
.status-ok {
    background: #DCFCE7;
    color: #166534;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.status-expiry {
    background: #FFEDD5;
    color: #9A3412;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

/* BATCH TABLE - FULLY MATCHING YOUR STYLE */
.batch-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid rgba(22, 93, 255, 0.15); /* Soft blue border like cards */
    background: #FFFFFF;
}

.batch-table th, 
.batch-table td {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    text-align: left;
    vertical-align: middle;
}

/* TABLE HEADER - GLASS STYLE LIKE SUMMARY CARDS */
.batch-table th {
    background: rgba(22, 93, 255, 0.08); /* Same glass blue */
    color: #1A1A1A; /* Close to black */
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(22, 93, 255, 0.15);
}

/* TABLE BODY TEXT - CLEAR WEIGHT DIFFERENCE */
.batch-table td {
    color: #1A1A1A; /* Close to black */
    font-weight: 500; /* Medium weight */
}

/* HOVER EFFECT - SOFT BLUE TINT */
.batch-table tbody tr:not(#noBatchRow):hover {
    background-color: rgba(22, 93, 255, 0.05);
}

/* EMPTY STATE STYLING */
.empty-table-state {
    text-align: center !important;
    padding: 40px !important;
    color: #6B7280;
    font-size: 14px;
    font-weight: 500;
    background: #F9FAFB;
}

.stock-card-modal {
    width: 90%;
    max-width: 900px;
    max-height: 90vh;
    padding: 0;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    box-shadow: 0 20px 35px rgba(15, 23, 42, 0.16);
    overflow-y: auto;
}
.stock-card-modal .modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #E5E7EB;
}
.stock-card-modal .modal-title { font-size: 18px; font-weight: 700; color: #111827; }
.stock-card-modal .modal-subtitle { font-size: 13px; color: #64748B; font-style: normal; }
.stock-card-modal .close-btn {
    border: 0;
    background: transparent;
    padding: 4px 8px;
    color: #64748B;
    font-size: 22px;
    line-height: 1;
}
.stock-card-modal .close-btn:hover { color: #111827; background: #F1F5F9; border-radius: 6px; }
.stock-card-content { padding: 24px; }
.stock-card-heading { text-align: center; margin-bottom: 18px; }
.stock-card-heading .gov-sub-title { font-size: 11px; color: #64748B; letter-spacing: 0.08em; font-weight: 600; }
.stock-card-heading .gov-main-title { font-size: 15px; color: #111827; font-weight: 700; margin-top: 5px; }
.stock-card-heading::after { content: ''; display: block; width: 42px; height: 3px; margin: 10px auto 0; border-radius: 2px; background: #2563EB; }
.stock-card-metadata {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px 28px;
    padding: 18px 20px;
    background: #FAFAFA;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
}
.stock-card-metadata .meta-row {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #E5E7EB;
    text-align: left;
}
.stock-card-metadata .meta-row:nth-last-child(-n + 2) { border-bottom: 0; padding-bottom: 0; }
.stock-card-metadata .meta-icon { width: 16px; height: 16px; flex: 0 0 auto; color: #2563EB; }
.stock-card-metadata .meta-label { color: #64748B; font-weight: 600; }
.stock-card-metadata .meta-value { margin-left: auto; color: #111827; font-weight: 600; text-align: right; overflow-wrap: anywhere; }
.stock-card-table-wrap { margin-top: 22px; overflow-x: auto; border: 1px solid #E5E7EB; border-radius: 8px; }
.stock-card-table { width: 100%; min-width: 680px; border-collapse: collapse; table-layout: fixed; }
.stock-card-table th, .stock-card-table td { padding: 12px 14px; border-bottom: 1px solid #E5E7EB; vertical-align: middle; }
.stock-card-table th { background: #FFFFFF; color: #475569; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; text-align: left; }
.stock-card-table td { color: #334155; font-size: 13px; }
.stock-card-table th:nth-child(1), .stock-card-table td:nth-child(1) { width: 18%; }
.stock-card-table th:nth-child(2), .stock-card-table td:nth-child(2), .stock-card-table th:nth-child(3), .stock-card-table td:nth-child(3), .stock-card-table th:nth-child(4), .stock-card-table td:nth-child(4) { width: 14%; text-align: center; }
.stock-card-table th:nth-child(5), .stock-card-table td:nth-child(5) { width: 40%; }
.stock-card-table tbody tr:last-child td { border-bottom: 0; }
.stock-card-table .received-value { color: #16A34A; font-weight: 700; }
.stock-card-table .issued-value { color: #DC2626; font-weight: 700; }
.stock-card-table .balance-value { color: #2563EB; font-weight: 700; }
.stock-card-actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 20px; padding-top: 16px; border-top: 1px solid #E5E7EB; }
.stock-card-actions .print-btn { display: inline-flex; align-items: center; gap: 8px; border: 1px solid #CBD5E1; border-radius: 7px; padding: 9px 14px; background: #FFFFFF; color: #334155; font-weight: 600; cursor: pointer; }
.stock-card-actions .print-btn:hover { background: #F8FAFC; border-color: #94A3B8; }
.stock-card-actions .print-icon { width: 16px; height: 16px; color: #2563EB; }
@media (max-width: 640px) {
    .stock-card-metadata { grid-template-columns: 1fr; }
    .stock-card-metadata .meta-row:nth-last-child(-n + 2) { border-bottom: 1px solid #E5E7EB; padding-bottom: 10px; }
    .stock-card-metadata .meta-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .stock-card-content { padding: 16px; }
}

/*ACTION - VIEW*/
/* HEADER ALIGNMENT */
.stock-card-header {
    text-align: center;
    margin-bottom: 24px;
}

.gov-sub-title {
    font-size: 11px;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.08em; /* Gives it an official government document feel */
    font-weight: 500;
}

.gov-main-title {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    margin-top: 2px;
}

/* DATA LAYOUT GRID */
.stock-card-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 20px;
    padding: 16px 24px;
    background: #F9FAFB; /* Soft light backdrop to frame out the metadata card info */
    border-radius: 8px;
    border: 1px solid #E5E7EB;
}

/* BASE COLUMNS */
.meta-column {
    display: flex;
    flex-direction: column;
    gap: 8px; /* Standard vertical line height gap */
}

/* Pushes text alignment cleanly to the right side if the container is wide */
.meta-column.align-right {
    align-items: flex-end; 
    text-align: right;
}

/* INDIVIDUAL DETAILS ROW SETUP */
.meta-row {
    font-size: 13.5px;
    color: #374151;
    line-height: 1.4;
}

.meta-row b {
    color: #4B5563; /* Subtle slate-gray bold prefix text */
    font-weight: 600;
}

.meta-row span {
    color: #111827;
    font-weight: 500;
}
/* Clean styling specifically for table actions */
.action-btn {
    background: transparent;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600; /* Crisp typography */
    cursor: pointer;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    transition: all 0.15s ease-in-out;
}

/* View Button Specific State */
.view-btn {
    color: #2563EB; /* Dashboard identity blue */
}

.view-btn:hover {
    color: #1D4ED8;
    background: #EFF6FF; /* Soft backdrop fill instead of layout-shifting margins */
}

.view-btn:active {
    transform: scale(0.96); /* Micro-interaction bounce */
}

/*MODAL HEADER*/

    /* FOOTER */
    .modal-footer {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

   .btn-in {
    background-color: #165DFF;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    margin: 0 5px;
    cursor: pointer;
}

    .btn-in:hover{
        background: #097c56;
    }
    .btn-in:active{
        transform: translateX(-2px);
    }

    .btn-edit {
    background-color: #059669;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    margin: 0 5px;
    cursor: pointer;
}
.btn-edit:hover {
    background-color: #097c56;
}

    .btn-out {
        background: #EF4444;
        color: white;
        border: none;
        padding: 10px 14px;
        border-radius: 8px;
    }
    .btn-out:hover{
        background: #a02c2c;
    }
    .btn-out:active{
        transform: translateY(-2px);
    }

   .btn-close {
    background-color: #E5E7EB;
    color: #374151;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    margin: 0 5px;
    cursor: pointer;
}

    .btn-close:hover{
        background: rgb(182, 182, 182);
    }
    .btn-close:active{
        transform: translateX(2px);

    }
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-overlay {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }

    .modal-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }

    .modal {
        background: white;
        border-radius: 12px;
        padding: 30px;
        width: 90%;
        max-width: 900px;
    }
    .modal-label label {
        display: block;
        margin-bottom: 6px;
        font-size: 16px;
        color: #353636;
    }

    .modal-label input{
        display: block;
        margin-bottom: 12px;
    }
    .btn-cancel {
        padding: 10px 14px;
        border: 1px solid #D1D5DB;
        background: rgb(240, 240, 240);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #cecece;
    }

    .btn-cancel:active {
        transform: scale(0.96);
    }

    /* SAVE BUTTON */
    .btn-save {
        background: #10B981;
        color: white;
        padding: 10px 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        background: #027e57;
    }

    .btn-save:active {
        transform: scale(0.96);
    }
    .delete-link {
        color: #EF4444;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: none;
        border: none;
        padding: 0;
    }

    .delete-link:hover {
        color: #B91C1C;
        text-decoration: underline;
        transform: scale(1.05);
    }
    /* Validation Styling */
    /* Validation Styles */
    .is-invalid {
        border: 2px solid #EF4444 !important;
        background-color: #FFF5F5 !important;
    }

    .error-feedback {
        color: #EF4444;
        font-size: 12px;
        margin-top: 4px;
        display: block;
        font-weight: bold;
    }

    /* Keep the stock card compact enough to display without an inner scrollbar. */
    .stock-card-modal {
        height: auto !important;
        max-height: calc(100vh - 32px) !important;
        padding: 0 !important;
        overflow: visible !important;
        box-sizing: border-box;
    }
    #stockCardModal {
        overflow-y: auto;
        padding: 16px;
        box-sizing: border-box;
    }
    #stockCardModal .stock-card-modal { margin: auto; }
    .stock-card-modal .stock-card-content { padding: 18px 24px; }
    .stock-card-modal .stock-card-heading { margin-bottom: 14px; }
    .stock-card-modal .stock-card-metadata { padding: 14px 16px; gap: 10px 24px; }
    .stock-card-modal .stock-card-metadata .meta-row { padding-bottom: 7px; }
    .stock-card-modal .stock-card-table-wrap { margin-top: 16px; }
    .stock-card-modal .stock-card-table th,
    .stock-card-modal .stock-card-table td { padding: 10px 12px; }
    .stock-card-modal .stock-card-actions { margin-top: 16px; padding-top: 12px; }
    @media (max-width: 640px) {
        .stock-card-modal .stock-card-content { padding: 14px 16px; }
        .stock-card-modal .stock-card-metadata { gap: 7px; }
        .stock-card-modal .stock-card-metadata .meta-row { padding-bottom: 6px; }
        .stock-card-modal .stock-card-actions { margin-top: 12px; padding-top: 10px; }
    }

    .expiring-batches-modal {
    width: 92%;
    max-width: 1080px;
    max-height: 90vh;
    padding: 0;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    box-shadow: 0 20px 35px rgba(15, 23, 42, 0.16);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.expiring-batches-modal .modal-header { 
    padding: 20px 24px; 
    border-bottom: 1px solid #E5E7EB; 
    flex-shrink: 0;
}

.expiring-batches-modal .modal-title { font-size: 18px; font-weight: 700; color: #111827; }
.expiring-batches-modal .modal-subtitle { font-size: 13px; color: #64748B; font-style: normal; }

/* Scrollable Container */
.expiring-batches-content { 
    padding: 22px 24px 24px; 
    overflow-y: auto; 
    flex-grow: 1;
}

.expiring-table-wrap { 
    overflow-x: auto; 
    border: 1px solid #E5E7EB; 
    border-radius: 8px; 
}

.expiring-table { width: 100%; min-width: 820px; border-collapse: collapse; table-layout: fixed; }
.expiring-table th, .expiring-table td { padding: 12px 14px; border-bottom: 1px solid #E5E7EB; vertical-align: middle; }

/* Sticky Header Logic */
.expiring-table th { 
    position: sticky;
    top: 0;
    z-index: 10;
    background: #FAFAFA; 
    color: #475569; 
    font-size: 11px; 
    font-weight: 700; 
    letter-spacing: 0.05em; 
    text-align: left; 
    text-transform: uppercase; 
    white-space: nowrap; 
    box-shadow: inset 0 -1px 0 #E5E7EB; /* Prevents border gap during scrolling */
}

.expiring-table td { color: #334155; font-size: 13px; }
.expiring-table th:nth-child(1), .expiring-table td:nth-child(1) { width: 25%; }
.expiring-table th:nth-child(2), .expiring-table td:nth-child(2) { width: 17%; }
.expiring-table th:nth-child(3), .expiring-table td:nth-child(3) { width: 12%; text-align: center; }
.expiring-table th:nth-child(4), .expiring-table td:nth-child(4) { width: 16%; }
.expiring-table th:nth-child(5), .expiring-table td:nth-child(5) { width: 14%; text-align: center; }
.expiring-table th:nth-child(6), .expiring-table td:nth-child(6) { width: 16%; text-align: center; }

.expiring-table tbody tr:last-child td { border-bottom: 0; }
.expiring-table tbody tr.urgent { background: #FFF7F7; }
.expiring-table tbody tr.warning { background: #FFFBEB; }
.expiring-table .medicine-cell { font-weight: 700; color: #1E293B; }
.expiring-table .brand-cell { display: block; margin-top: 3px; color: #64748B; font-size: 12px; font-weight: 400; }
.expiring-table .days-urgent { color: #DC2626; font-weight: 700; }
.expiring-table .days-warning { color: #D97706; font-weight: 700; }

.expiry-view-btn { border: 1px solid #BFDBFE; border-radius: 6px; padding: 7px 10px; background: #EFF6FF; color: #1D4ED8; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap; }
.expiry-view-btn:hover { background: #DBEAFE; border-color: #93C5FD; }

@media (max-width: 640px) { .expiring-batches-content { padding: 16px; } }

</style>
</head>
<script>
    function viewBatch(medicineName, batchString) {
        let batch;
        try { batch = JSON.parse(batchString); } 
        catch (e) { batch = batchString; }

        closeExpiringBatchesModal();
        const modal = document.getElementById("stockCardModal");
        const medicineModal = document.getElementById("medicineModal");

        // Detach the stock card from the medicine modal so it can open independently.
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
        if (medicineModal) {
            medicineModal.classList.remove("show");
            medicineModal.style.display = "none";
        }

        if (!modal) return;
        modal.style.display = "flex";
        setTimeout(() => modal.classList.add("show"), 10);

        document.getElementById("sc-title").innerText = "Stock Card - " + medicineName;
        document.getElementById("sc-batch").innerText = "Batch: " + batch.batch_number;
        document.getElementById("sc-item").innerText = medicineName;
        document.getElementById("sc-expiry").innerText = batch.expiry_date || "Not Set";
        document.getElementById("sc-batchnum").innerText = batch.batch_number;
        document.querySelector('#stockCardModal .mode-val').innerText = batch.remarks || "Not Specified";

        document.getElementById("sc-table-body").innerHTML = `
            <tr>
                <td>${new Date(batch.created_at).toLocaleDateString()}</td>
                <td class="received-value">${batch.quantity}</td>
                <td class="issued-value">0</td>
                <td class="balance-value">${batch.quantity}</td>
                <td>${batch.remarks || 'Initial Stock Entry'}</td>
            </tr>
        `;
    }

    function closeStockCard() {
        const modal = document.getElementById("stockCardModal");
        modal.classList.remove("show");
        setTimeout(() => modal.style.display = "none", 200);
    }

    function openExpiringBatchesModal() {
        const modal = document.getElementById("expiringBatchesModal");
        if (!modal) return;
        modal.style.display = "flex";
        modal.setAttribute("aria-hidden", "false");
        setTimeout(() => modal.classList.add("show"), 10);
    }

    function closeExpiringBatchesModal() {
        const modal = document.getElementById("expiringBatchesModal");
        if (!modal) return;
        modal.classList.remove("show");
        modal.setAttribute("aria-hidden", "true");
        setTimeout(() => modal.style.display = "none", 200);
    }

    function openAddMedicineModal() {
        const modal = document.getElementById("addMedicineModal");
        if(!modal) { alert("Modal missing!"); return; }
        modal.style.display = "flex";
        setTimeout(() => modal.classList.add("show"), 10);
    }

    function closeAddMedicineModal() {
        const modal = document.getElementById("addMedicineModal");
        modal.classList.remove("show");
        setTimeout(() => modal.style.display = "none", 200);
    }

    // ✅ EDIT MODAL FUNCTIONS
function openStockEditModal(medicineId, medicineName, batchId, batchNumber, expiryDate, currentQty, remarks = '') {
    console.log("Editing Batch ID:", batchId); // ✅ PARA SURE, makita mo sa console

    // 1. ILAGAY ANG MGA HALAGA SA LOOB NG MODAL
    document.getElementById('edit_medicine_id').value = medicineId;
    document.getElementById('edit_batch_id').value = batchId; // ✅ ITO ANG SUSI

    document.getElementById('edit_medicine_name').value = medicineName;
    document.getElementById('edit_batch_number').value = batchNumber;
    document.getElementById('edit_expiry').value = expiryDate;
    document.getElementById('edit_quantity').value = currentQty;
    document.getElementById('edit_remarks').value = remarks;

    // 2. BUKASIN ANG MODAL (gagamitin natin yung style mo)
    const modal = document.getElementById("editStockModal");
    modal.style.display = "flex";
    setTimeout(() => modal.classList.add("show"), 10);
}

// ✅ PAGSARA
function closeEditStockModal() {
    const modal = document.getElementById("editStockModal");
    modal.classList.remove("show");
    setTimeout(() => {
        modal.style.display = "none";
        document.getElementById('editStockForm').reset();
    }, 200);
}

    function filterMedicines(type) {
        const rows = document.querySelectorAll("tbody tr");
        rows.forEach(row => {
            const stock = parseInt(row.getAttribute("data-stock")) || 0;
            const expiry = row.getAttribute("data-expiry");
            let show = true;

            if (type === "low") {
                show = stock <= 100 && stock > 20;
            } else if (type === "critical") {
                show = stock <= 20;
            } else if (type === "expiry") {
                if (!expiry) {
                    show = false;
                } else {
                    const today = new Date();
                    const exp = new Date(expiry);
                    const diffDays = (exp - today) / (1000 * 60 * 60 * 24);
                    show = diffDays <= 180 && diffDays > 0;
                }
            }
            row.style.display = show ? "" : "none";
        });
    }

    function applyFilters() {
        const searchValue = document.getElementById("searchInput").value.toLowerCase().trim();
        const filterType = document.getElementById("filterSelect").value;
        const rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            const name = row.querySelector(".medicine-name")?.innerText.toLowerCase() || "";
            const brand = row.querySelector(".brand")?.innerText.toLowerCase() || "";
            const stock = parseInt(row.getAttribute("data-stock")) || 0;
            const expiry = row.getAttribute("data-expiry");

            const matchesSearch = name.includes(searchValue) || brand.includes(searchValue);
            let matchesFilter = true;

            if (filterType === "low") {
                matchesFilter = stock <= 100 && stock > 20;
            } else if (filterType === "critical") {
                matchesFilter = stock <= 20;
            } else if (filterType === "expiry") {
                if (!expiry) {
                    matchesFilter = false;
                } else {
                    const today = new Date();
                    const exp = new Date(expiry);
                    const diffDays = (exp - today) / (1000 * 60 * 60 * 24);
                    matchesFilter = diffDays <= 180 && diffDays > 0;
                }
            }
            row.style.display = (matchesSearch && matchesFilter) ? "" : "none";
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const filterSelect = document.getElementById("filterSelect");

        if(searchInput && filterSelect){
            searchInput.addEventListener("keyup", applyFilters);
            filterSelect.addEventListener("change", applyFilters);
        }

        const resetInventoryFilters = document.getElementById("resetInventoryFilters");
        if (resetInventoryFilters) {
            resetInventoryFilters.addEventListener("click", function() {
                if (searchInput) searchInput.value = "";
                if (filterSelect) filterSelect.value = "all";
                applyFilters();
                if (searchInput) searchInput.focus();
            });
        }

        const addForm = document.querySelector('#addMedicineModal form');
        if(addForm){
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const nameInput = document.getElementById('new_med_name');
                const errorMsg = document.getElementById('med_name_error');

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(async response => {
                    if (response.status === 422) {
                        if(nameInput) nameInput.style.border = "2px solid #EF4444";
                        if(nameInput) nameInput.style.backgroundColor = "#FFF5F5";
                        if(errorMsg) errorMsg.style.display = "block";
                    } else {
                        window.location.href = window.location.pathname + "?success_med=1";
                    }
                });
            });
        }
    });
</script>
<body>

<div class="container">

        <x-sidebar />

<div class="main">

    <div class="header">
<div>
    <div class="welcome-text">Welcome back,</div>

    <div class="welcome-name">
        {{ $userName }}

        @php
            $user = Auth::user();

            $displayRole = (
                strtolower($user->role) === 'doctor' &&
                $user->is_physician_in_charge == 1
            ) ? 'PIC' : strtoupper($role);

            $roleColor = match (strtolower($user->role)) {
                'admin'  => '#9333EA', // Purple
                'nurse'  => '#10B981', // Green
                'doctor' => '#3B82F6', // Blue
                'bhw'    => '#6366F1', // Indigo
                default  => '#6B7280', // Gray
            };
        @endphp

        <span
            class="role"
            style="background-color: {{ $roleColor }};"
        >
            {{ $displayRole }}
        </span>
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

    <div class="top-bar">
        <div>
            <div class="page-title">Medicine Inventory</div>
            <div class="page-subtitle">Track and manage barangay medicine stock levels</div>
        </div>

        {{-- ✅ INAYOS: GAMITIN ANG $role PARA SA PWEDE MAG-ADD --}}
        @if($role == 'admin' || $role == 'nurse')
        <button class="add-btn" onclick="openAddMedicineModal()" style="position:relative; z-index:10;">Add Medicine</button>
        @endif
    </div>

@php
use Carbon\Carbon;

$totalMedicines = $medicines->count();

/* ================= STOCK CALC ================= */
$lowStock = 0;
$criticalStock = 0;
$nearExpiry = 0;

foreach ($medicines as $medicine) {

    $stock = $medicine->total_stock ?? 0;

    // Low: 21-100, Critical: <=20
    if ($stock <= 100 && $stock > 20) {
        $lowStock++;
    } elseif ($stock <= 20) {
        $criticalStock++;
    }

    // ✅ FIX: Loop through ALL batches of this medicine instead of just the first one
    if ($medicine->batches) {
        foreach ($medicine->batches as $batch) {
            if ($batch->expiry_date) {
                $daysLeft = Carbon::now()->diffInDays(Carbon::parse($batch->expiry_date), false);

                // Counts every batch expiring within 180 days (and not yet expired)
                if ($daysLeft <= 180 && $daysLeft > 0) {
                    $nearExpiry++;
                }
            }
        }
    }
}
@endphp


<div class="kpi-row">

    <div class="kpi kpi-grey" role="button" tabindex="0" onclick="filterMedicines('all')" onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); filterMedicines('all'); }">
        <div class="kpi-title">Total Medicines</div>
        <div class="kpi-number">{{ $totalMedicines }}</div>
    </div>

    <div class="kpi kpi-yellow" role="button" tabindex="0" onclick="filterMedicines('low')" onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); filterMedicines('low'); }">
        <div class="kpi-title">Low Stock</div>
        <div class="kpi-number">{{ $lowStock }}</div>
    </div>

    <div class="kpi kpi-red" role="button" tabindex="0" onclick="filterMedicines('critical')" onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); filterMedicines('critical'); }">
        <div class="kpi-title">Critical Stock</div>
        <div class="kpi-number">{{ $criticalStock }}</div>
    </div>

    <div class="kpi kpi-orange kpi-expiry" role="button" tabindex="0" onclick="openExpiringBatchesModal()" onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openExpiringBatchesModal(); }">
        <div class="kpi-title">Near Expiry</div>
        <div class="kpi-number">{{ $nearExpiry }}</div>
    </div>

</div>
@php
$criticalItem = $medicines
    ->filter(function ($m) {
        return $m->total_stock <= 50; // Updated to 50
    })
    ->sortBy('total_stock')
    ->first();
@endphp
<div class="alert {{ !$criticalItem ? 'alert-success' : '' }}">
    <div class="alert-content">
        <div class="alert-info-group">
            <div class="alert-icon-box">
                <img src="{{ $criticalItem ? '/icons/alert.png' : '/icons/check-circle.png' }}" alt="Status Icon">
            </div>
            <div class="alert-text-wrapper">
                @if($criticalItem)
                    <span class="alert-headline">CRITICAL LOW STOCK</span>
                    <span class="alert-subtitle">
                        {{ $criticalItem->name }} {{ $criticalItem->dosage_strength }} 
                        <strong class="alert-highlight">({{ $criticalItem->total_stock }} pcs left)</strong>
                    </span>
                @else
                    <span class="alert-headline">ALL SYSTEMS OPERATIONAL</span>
                    <span class="alert-subtitle">No critical medicine stock shortages detected at the moment.</span>
                @endif
            </div>
        </div>

        @if($criticalItem)
    <button type="button" 
            onclick="openStockInModal('{{ $criticalItem->id }}')" 
            class="alert-action-btn" 
            style="cursor: pointer; background: red; border: none;">
        Restock Now
    </button>
@endif

    </div>
</div>
<div class="search-row">

    <div class="search-box">
        <img src="/icons/search.png" class="search-icon">
        <input type="text" id="searchInput" placeholder="Search medicine name or brand...">
    </div>

    <div class="filter-box">
        <img src="/icons/filter.png" class="search-icon">

        <select id="filterSelect"
            style="border:none; outline:none; padding:10px; width:140px;">
            <option value="all">All</option>
            <option value="low">Low Stock</option>
            <option value="critical">Critical Stock</option>
            <option value="expiry">Near Expiry</option>
        </select>

    </div>

    <button type="button" class="reset-filter-btn" id="resetInventoryFilters">Clear filters</button>

</div>

<div class="modal-overlay" id="expiringBatchesModal" aria-hidden="true">
    <div class="modal expiring-batches-modal" role="dialog" aria-modal="true" aria-labelledby="expiringBatchesTitle">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="expiringBatchesTitle">Expiring Stock Batches</div>
                <div class="modal-subtitle">Active batches expiring within the next 180 days</div>
            </div>
            <button type="button" class="close-btn" onclick="closeExpiringBatchesModal()" aria-label="Close expiring stock batches">✕</button>
        </div>
        <div class="expiring-batches-content">
            <div class="expiring-table-wrap">
                <table class="expiring-table">
                    <thead>
                        <tr>
                            <th>Medicine &amp; Brand</th>
                            <th>Batch Number</th>
                            <th>Qty in Batch</th>
                            <th>Expiration Date</th>
                            <th>Days Remaining</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiringBatches as $batch)
                            @php
                                $daysRemaining = max(0, Carbon::today()->diffInDays(Carbon::parse($batch->expiry_date), false));
                                $urgencyClass = $daysRemaining < 30 ? 'urgent' : 'warning';
                                $daysClass = $daysRemaining < 30 ? 'days-urgent' : 'days-warning';
                            @endphp
                            <tr class="{{ $urgencyClass }}">
                                <td class="medicine-cell">
                                    {{ $batch->medicine_name }}
                                    <span class="brand-cell">{{ $batch->brand_name ?: 'Brand not specified' }}</span>
                                </td>
                                <td>{{ $batch->batch_number }}</td>
                                <td>{{ $batch->quantity }}</td>
                                <td>{{ Carbon::parse($batch->expiry_date)->format('M d, Y') }}</td>
                                <td class="{{ $daysClass }}">{{ $daysRemaining }} days</td>
                                <td>
                                    <button type="button" class="expiry-view-btn" onclick="viewBatch(@js($batch->medicine_name . ' ' . ($batch->dosage_strength ?: '')), @js($batch->toArray()))">
                                        View Stock Card
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-table-state">No active batches are expiring within 180 days.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    @php
    $needAttentionCount = $medicines->filter(function ($medicine) {

        $stock = $medicine->total_stock;

        // Only consider batches that are still usable:
        // still have quantity, and haven't already expired.
        $nearestExpiry = optional(
            $medicine->batches
                ->where('quantity', '>', 0)
                ->filter(fn($batch) => \Carbon\Carbon::parse($batch->expiry_date)->isFuture())
                ->sortBy('expiry_date')
                ->first()
        )->expiry_date;

        // STOCK CONDITION
        $isLowStock = $stock <= 100;

        // EXPIRY CONDITION (180 days)
        $isExpiring = false;

        if ($nearestExpiry) {
            $daysLeft = \Carbon\Carbon::now()
                ->diffInDays(\Carbon\Carbon::parse($nearestExpiry), false);

            $isExpiring = $daysLeft <= 180 && $daysLeft > 0;
        }

        return $isLowStock || $isExpiring;

    })->count();
@endphp
    <div class="priority-card">
        <div class="priority-header">
            <div>
                <b>All Medicines</b>
                <span class="badge-red">
                    {{ $needAttentionCount }} need attention
                </span>
            </div>
        </div>

        @if(request()->get('success_med'))
            <div style="background:#DCFCE7; color:#166534; padding:12px; border-radius:8px; margin: 10px; border: 1px solid #10B981;">
                <b>Success!</b> New medicine added to inventory successfully!
            </div>
        @endif

        @if(session('success'))
            <div style="background:#DCFCE7; color:#166534; padding:12px; border-radius:8px; margin: 10px; border: 1px solid #10B981;">
                {{ session('success') }}
            </div>
        @endif

        @if(request()->get('success_stock'))
            <div style="background:#DCFCE7; color:#166534; padding:12px; border-radius:8px; margin: 10px;">
                Stock added successfully for the medicine!
            </div>
        @endif

        <div class="inventory-table-wrap">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>MEDICINE NAME</th>
                    <th>CURRENT STOCK</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                    @forelse($medicines as $medicine)
                        @php
                            $stock = $medicine->total_stock;
                            // Stock Status Logic
                            if ($stock == 0) {
                                $stockStatus = "NO STOCK"; $stockClass = "status-critical"; $stockTextClass = "stock-critical";
                            } elseif ($stock <= 50) { 
                                $stockStatus = "CRITICAL STOCK"; $stockClass = "status-critical"; $stockTextClass = "stock-critical";
                            } elseif ($stock <= 100) { 
                                $stockStatus = "LOW STOCK"; $stockClass = "status-low"; $stockTextClass = "stock-low";
                            } else { 
                                $stockStatus = "NORMAL"; $stockClass = "status-normal"; $stockTextClass = "stock-normal";
                            }
                        @endphp

                        <tr data-stock="{{ $medicine->total_stock }}" 
                            data-expiry="{{ optional($medicine->batches->sortBy('expiry_date')->first())->expiry_date }}">
                            
                            <td>
                                <div class="medicine-name">{{ $medicine->name }} {{ $medicine->dosage_strength }}</div>
                                <div class="brand">{{ $medicine->brand }}</div>
                            </td>

                            <td class="{{ $stockTextClass }}">
                                {{ $medicine->total_stock }}
                            </td>

                            <td>
                                <span class="status-pill {{ $stockClass }}">
                                    {{ $stockStatus }}
                                </span>
                            </td>

                            <td>
                                <div class="action-group">
                                <button type="button" class="action-btn view-btn"
                                    onclick="openModal(
                                        @js($medicine->id),
                                        @js($medicine->name),
                                        @js($medicine->brand),
                                        @js($medicine->dosage_strength),
                                        @js($medicine->total_stock),
                                        @js($medicine->batches)
                                    )">
                                    View Details
                                </button>

                                <form action="{{ url('/medicine/delete/' . $medicine->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this medicine?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn">
                                        Delete
                                    </button>
                                </form>
            
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:20px;">
                                No medicines found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

    </div>
</div>

<div class="modal-overlay" id="medicineModal">
    <div class="modal">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="modalTitle">—</div>
                <div class="modal-subtitle" id="modalaSubTitle">—</div>
            </div>
            <div class="close-btn" onclick="closeModal()">✕</div>
        </div>

        <div class="modal-summary">
            <div class="summary-card">
                <div>Medicine Name</div>
                <div class="summary-value" id="modalMedicine">—</div>
            </div>
            <div class="summary-card">
                <div>Total Stock</div>
                <div class="summary-value blue-text" id="modalStock">0 pcs</div>            
            </div>
            <div class="summary-card">
                <div>Number of Batches</div>
                <div class="summary-value" id="modalBatches">0</div>
            </div>
        </div>

        <div class="batch-section">
            <div class="batch-title">Batches</div>
            <table class="batch-table">
                <thead>
                    <tr>
                        <th>Batch Number</th>
                        <th>Expiration Date</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="batchTableBody">
                    <tr id="noBatchRow">
                        <td colspan="5" class="empty-table-state">
                            No stock available yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

       <div class="modal-footer">
            <button 
                type="button"
                onclick="openStockInModal(selectedMedicineId, selectedMedicineName)" 
                onmouseover="this.style.backgroundColor='#0045E6'"
                onmouseout="this.style.backgroundColor='#165DFF'"
                style="background-color: #165DFF; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; transition: background-color 0.2s ease;">
                Add Stock (IN)
            </button>  

            <button class="btn-close" onclick="closeModal()" style="margin-left: 10px; padding: 8px 16px; border: 1px solid #ccc; background: #fff; border-radius: 6px; cursor: pointer;">Close</button>
        </div>

        <!-- STOCK CARD MODAL (Nakapatong sa loob) -->
        <div class="modal-overlay" id="stockCardModal">
            <div class="modal stock-card-modal">
                <div class="modal-header">
                    <div>
                        <div class="modal-title" id="sc-title">Stock Card —</div>
                        <div class="modal-subtitle" id="sc-batch">Batch: —</div>
                    </div>
                    <div class="close-btn" onclick="closeStockCard()">✕</div>
                </div>

                <div class="stock-card-content">
                <div class="stock-card-heading">
                    <div class="gov-sub-title">Republic of the Philippines</div>
                    <div class="gov-main-title">Barangay Health Center — Stock Card</div>
                </div>

                <div class="stock-card-metadata">
                    <div class="meta-row">
                        <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15.5a2.5 2.5 0 0 0-2.5-2.5h-11A2.5 2.5 0 0 0 4 18.5V5.5Z"/><path d="M4 18.5A2.5 2.5 0 0 0 6.5 21H20"/></svg>
                        <span class="meta-label">Item Description</span><span class="meta-value" id="sc-item">—</span>
                    </div>
                    <div class="meta-row">
                        <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3.5" y="4.5" width="17" height="16" rx="2"/><path d="M7.5 2.5v4M16.5 2.5v4M3.5 9h17"/></svg>
                        <span class="meta-label">Expiration Date</span><span class="meta-value" id="sc-expiry">—</span>
                    </div>
                    <div class="meta-row">
                        <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 13.5 13.5 20a2.1 2.1 0 0 1-3 0L4 13.5V4h9.5L20 10.5a2.1 2.1 0 0 1 0 3Z"/><circle cx="8.5" cy="8.5" r="1.2"/></svg>
                        <span class="meta-label">Batch Number</span><span class="meta-value" id="sc-batchnum">—</span>
                    </div>
                    <div class="meta-row">
                        <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
                        <span class="meta-label">Entity Name</span><span class="meta-value">Velasquez Health Center</span>
                    </div>
                    <div class="meta-row">
                        <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10"/><circle cx="18" cy="17" r="2"/></svg>
                        <span class="meta-label">Mode</span><span class="meta-value mode-val">—</span>
                    </div>
                </div>

                <div class="stock-card-table-wrap">
                    <table class="stock-card-table">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th style="color:#16A34A;">RECEIVED</th>
                                <th style="color:#DC2626;">ISSUED</th>
                                <th style="color:#2563EB;">BALANCE</th>
                                <th>REMARKS</th>
                            </tr>
                        </thead>
                        <tbody id="sc-table-body">
                        </tbody>
                    </table>
                </div>

                <div class="stock-card-actions">
                    <button type="button" class="print-btn" onclick="window.print()">
                        <svg class="print-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M7 9V3h10v6M7 17H5a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2"/><path d="M7 14h10v7H7z"/><path d="M17 12h.01"/></svg>
                        Print
                    </button>
                    <button type="button" class="btn-close" onclick="closeStockCard()">Close</button>
                </div>
                </div>
            </div>
        </div>
        <!-- END STOCK CARD MODAL -->

    </div>
</div>

@if($canEditOrStock)
<div class="modal-overlay" id="stockInModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 15px; box-sizing: border-box;">

    <div class="modal" style="width: 100%; max-width: 700px; max-height: 90vh; overflow-y: auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">

        <div class="modal-header" style="padding: 20px 26px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">            
            <div>
                <div class="modal-title" style="font-size: 18px; font-weight: bold; color: #111827;">Add Stock (IN)</div>
                <div class="modal-subtitle" style="font-size: 13px; color: #6B7280; margin-top: 3px;">Record new incoming medicine stock</div>
            </div>
            <div class="close-btn" onclick="closeStockInModal()" style="font-size: 20px; cursor: pointer; color: #6B7280; line-height: 1;">✕</div>
        </div>

        <form method="POST" action="{{ route('stock.in.store') }}" id="stockInForm" style="padding: 26px;">
            @csrf

            <input type="hidden" name="medicine_id" id="stock_in_medicine_id" value="">

            <div>

                <div class="modal-label" style="margin-top:15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom:6px;">
                        <label style="font-weight:600; font-size:14px; color:#374151;">Batch Number</label>
                    </div>
                    <!-- Added readonly so auto-generated batch isn't accidentally modified, remove 'readonly' if manual edit is needed -->
                    <input type="text" name="batch_number" id="batch_number_input" required placeholder="Auto-generating..." readonly
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; color:#1F2937; background-color: #F9FAFB; box-sizing: border-box;">
                    
                    <span id="batch_error_text" class="error-feedback" style="display: none; color: #EF4444; font-size: 12px; font-weight: bold; margin-top:4px;">
                        ⚠️ This batch number already exists.
                    </span>
                    <span id="batch_error_text_ajax" class="error-feedback" style="display: none; color: #EF4444; font-size: 12px; font-weight: bold; margin-top:4px;">
                        ⚠️ This batch number already exists.
                    </span>
                </div>

                <div class="modal-label" style="margin-top:15px;">
                    <label style="font-weight:600; font-size:14px; color:#374151; margin-bottom:6px; display:block;">Expiration Date</label>
                    <input type="date" name="expiry" required min="{{ date('Y-m-d') }}"
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; color:#1F2937; box-sizing: border-box;">
                    <small style="color:#6B7280; font-size:11px; margin-top:4px; display:block;">* Date must be in the future / not expired.</small>
                </div>

                <div class="modal-label" style="margin-top:15px;">
                    <label style="font-weight:600; font-size:14px; color:#374151; margin-bottom:6px; display:block;">Quantity Received</label>
                    <input type="number" name="quantity" required min="1" placeholder="Enter number of items"
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; color:#1F2937; box-sizing: border-box;">
                </div>

                <div class="modal-label" style="margin-top:15px;">
                    <label style="font-weight:600; font-size:14px; color:#374151; margin-bottom:6px; display:block;">Remarks</label>
                    <input type="text" name="remarks" placeholder="e.g., Initial Delivery, From CHO, Donation"
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; color:#1F2937; box-sizing: border-box;">
                    <small style="color:#6B7280; font-size:11px; margin-top:4px; display:block;">* Reason or source of stock.</small>
                </div>
            </div>

            <div style="margin-top:25px; display:flex; gap:12px; justify-content:flex-end; padding-top:10px; border-top:1px solid #E5E7EB; bottom: 0; background: #fff;">
                <button type="button" onclick="closeStockInModal()"
                    style="border-radius:8px; border:1px solid #D1D5DB; padding:10px 20px; background:#FFFFFF; color:#374151; font-weight:500; cursor:pointer; box-sizing: border-box;" class="btn-cancel">
                    Cancel
                </button>
                <button type="submit"
                    style="color:white; padding:10px 20px; border:none; border-radius:8px; background:#165DFF; font-weight:500; cursor:pointer; box-sizing: border-box;" class="btn-save">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ✅ MODAL: EDIT / UPDATE STOCK --}}
<div class="modal-overlay" id="editStockModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 15px; box-sizing: border-box;">

    <div class="modal" style="width: 100%; max-width: 700px; max-height: 90vh; overflow-y: auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">

        <div class="modal-header" style="padding: 20px 26px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">            
            <div>
                <div class="modal-title" style="font-size: 18px; font-weight: bold; color: #111827;">Edit / Update Stock</div>
                <div class="modal-subtitle" style="font-size: 13px; color: #6B7280; margin-top: 3px;">Modify batch details or correct quantity</div>
            </div>
            <div class="close-btn" onclick="closeEditStockModal()" style="font-size: 20px; cursor: pointer; color: #6B7280; line-height: 1;">✕</div>
        </div>

        <form method="POST" action="{{ url('/stock-update') }}" id="editStockForm" style="padding: 26px;">
            @csrf

            {{-- ✅ MGA HIDDEN ID NA KAILANGAN --}}
            <input type="hidden" name="medicine_id" id="edit_medicine_id" value="">
            <input type="hidden" name="batch_id" id="edit_batch_id" value=""> {{-- Ito ang pinaka-susi --}}

            <div>
                <div class="modal-label">
                    <label style="font-weight:600; font-size:14px; color:#374151; margin-bottom:6px; display:block;">Medicine</label>
                    <input type="text" 
                        id="edit_medicine_name" 
                        readonly
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; background:#F3F4F6; font-weight:500; color:#1F2937; box-sizing: border-box;">
                </div>


                <div class="modal-label" style="margin-top:15px;">
                    <label style="font-weight:600; font-size:14px; color:#374151; margin-bottom:6px; display:block;">Batch Number</label>
                    <input type="text" 
                        name="batch_number" 
                        id="edit_batch_number" 
                        required
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; color:#1F2937; box-sizing: border-box;">
                </div>


                <div class="modal-label" style="margin-top:15px;">
                    <label style="font-weight:600; font-size:14px; color:#374151; margin-bottom:6px; display:block;">Expiration Date</label>
                    <input type="date" 
                        name="expiry" 
                        id="edit_expiry"
                        required
                        min="{{ date('Y-m-d') }}"
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; color:#1F2937; box-sizing: border-box;">
                </div>


                <div class="modal-label" style="margin-top:15px;">
                    <label style="font-weight:600; font-size:14px; color:#374151; margin-bottom:6px; display:block;">Current Stock Quantity</label>
                    <input type="number" 
                        name="quantity" 
                        id="edit_quantity"
                        required
                        min="0"
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; color:#1F2937; box-sizing: border-box;">
                    <small style="color:#6B7280; font-size:11px; margin-top:4px; display:block;">* Set exact number of items remaining.</small>
                </div>


                <div class="modal-label" style="margin-top:15px;">
                    <label style="font-weight:600; font-size:14px; color:#374151; margin-bottom:6px; display:block;">Remarks / Reason</label>
                    <input type="text" 
                        name="remarks"
                        id="edit_remarks"
                        placeholder="e.g., Corrected count, Damaged items, Updated batch"
                        style="width:100%; padding:10px 12px; border:1px solid #D1D5DB; border-radius:6px; color:#1F2937; box-sizing: border-box;">
                </div>

            </div>

            <div style="margin-top:25px; display:flex; gap:12px; justify-content:flex-end; padding-top:10px; border-top:1px solid #E5E7EB; bottom: 0; background: #fff;">
                <button type="button" 
                        onclick="closeEditStockModal()" 
                        style="border-radius:8px; border:1px solid #D1D5DB; padding:10px 20px; background:#FFFFFF; color:#374151; font-weight:500; cursor:pointer; box-sizing: border-box;" 
                        class="btn-cancel">
                    Cancel
                </button>

                <button type="submit"
                        style="color:white; padding:10px 20px; border:none; border-radius:8px; background:#165DFF; font-weight:500; cursor:pointer; box-sizing: border-box;" 
                        class="btn-save">
                    Update Stock
                </button>
            </div>

        </form>

    </div>
</div>
@endif

<div class="modal-overlay" id="addMedicineModal">
    <div class="modal" style="max-width: 600px;">
        <div class="modal-header">
            <div>
                <div class="modal-title">Add New Medicine</div>
                <div class="modal-subtitle">Fill in the details below</div>
            </div>
            <div class="close-btn" onclick="closeAddMedicineModal()">✕</div>
        </div>

        <form action="{{ url('/medicine/store') }}" method="POST" id="addMedicineForm">
            @csrf
            <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 20px;">
                
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Medicine Name <span style="color:red;">*</span></label>
                    <input type="text" name="name" id="new_med_name" class="form-input" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    <small id="med_name_error" style="color:#DC2626; display:none; margin-top:4px; font-size:12px;">⚠️ Medicine name already exists or invalid!</small>
                </div>

                <div class="form-group">
                    <label>Brand / Manufacturer</label>
                    <input type="text" name="brand" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div class="form-group">
                    <label>Dosage Form</label>
                    <select name="dosage_form" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="Tablet">Tablet</option>
                        <option value="Capsule">Capsule</option>
                        <option value="Syrup">Syrup</option>
                        <option value="Sachet">Sachet</option>
                        <option value="Ointment">Ointment</option>
                        <option value="Vial">Vial/Ampoule</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Dosage Strength</label>
                    <input type="text" name="dosage_strength" placeholder="e.g. 500mg, 10ml" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                </div>

                <div class="form-group">
                    <label>Unit of Measure</label>
                    <select name="unit" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="Tablet">Tablet</option>
                        <option value="Capsule">Capsule</option>
                        <option value="Bottle">Bottle</option>
                        <option value="Sachet">Sachet</option>
                        <option value="Box">Box</option>
                        <option value="Piece">Piece/Unit</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" value="50" min="1" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="font-size:11px; color:#666;">Alert when stock falls below this number</small>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Description / Notes</label>
                    <textarea name="description" rows="3" class="form-input" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-submit" style="background:#165DFF; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer;">Save Medicine</button>
                <button type="button" class="btn-close" onclick="closeAddMedicineModal()" style="background:#f3f4f6; color:#333; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; margin-left:8px;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
// ==================================================
// ✅ GLOBAL VARIABLES
// ==================================================
let selectedMedicineId = "";
let selectedMedicineName = "";   
let selectedMedicineFullDetails = "";


// ==================================================
// ✅ OPEN MEDICINE DETAILS MODAL
// ==================================================
function openModal(id, name, brand, strength, stock, batches) {
    selectedMedicineId = id;
    selectedMedicineName = name;
    selectedMedicineFullDetails = name + " (" + strength + ")";

    const modal = document.getElementById("medicineModal");
    if(modal) {
        modal.style.display = "flex";
        setTimeout(() => modal.classList.add("show"), 10);
    }

    if(document.getElementById("modalTitle")) document.getElementById("modalTitle").innerText = name;
    if(document.getElementById("modalaSubTitle")) document.getElementById("modalaSubTitle").innerText = strength;
    if(document.getElementById("modalMedicine")) document.getElementById("modalMedicine").innerText = name;
    if(document.getElementById("modalStock")) document.getElementById("modalStock").innerText = stock + " pcs";
    if(document.getElementById("modalBatches")) document.getElementById("modalBatches").innerText = batches.length;

    let tableBody = document.getElementById("batchTableBody");
    if(!tableBody) return;
    tableBody.innerHTML = "";

    if (batches.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:20px;">No stock available yet.</td></tr>`;
    } else {
        batches.forEach((batch) => {
            let status = "ACTIVE";
            let statusClass = "status-ok"; // or "status-normal"
            
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Clear time portion for clean date comparison
            const expiry = new Date(batch.expiry_date);
            const diffDays = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));

            // 1. Check quantity first
            if (batch.quantity <= 0) {
                status = "OUT OF STOCK";
                statusClass = "status-critical";
            } 
            // 2. Check expiration status
            else if (diffDays < 0) { 
                status = "EXPIRED"; 
                statusClass = "status-critical"; 
            }
            else if (diffDays <= 7) { 
                status = "EXPIRING SOON"; 
                statusClass = "status-critical"; 
            }
            else if (diffDays <= 180) { 
                status = "EXPIRING"; 
                statusClass = "status-expiry"; // or "status-low"
            }

            tableBody.innerHTML += `
                <tr>
                    <td>${batch.batch_number}</td>
                    <td>${batch.expiry_date}</td>
                    <td>${batch.quantity}</td>
                    <td><span class="${statusClass}">${status}</span></td>
                    <td>
                        <button type="button" onclick="viewBatch('${selectedMedicineFullDetails}', '${JSON.stringify(batch).replaceAll('"', '&quot;')}')" style="background: rgba(22, 93, 255, 0.1); color: #165DFF; padding: 6px 14px; border: 1px solid rgba(22, 93, 255, 0.2); border-radius: 6px; font-size: 12px; margin-left: 5px; cursor: pointer;">View</button>
                        <button type="button" onclick="openStockEditModal('${id}', '${selectedMedicineFullDetails}', '${batch.id}', '${batch.batch_number}', '${batch.expiry_date}', '${batch.quantity}', '${batch.remarks || ''}')" style="background: rgba(22, 93, 255, 0.1); color: #165DFF; padding: 6px 14px; border: 1px solid rgba(22, 93, 255, 0.2); border-radius: 6px; font-size: 12px; margin-left: 5px; cursor: pointer;">Edit</button>
                    </td>
                </tr>`;
        });
    }
}

function closeModal() {
    const modal = document.getElementById("medicineModal");
    if(modal) {
        modal.classList.remove("show");
        setTimeout(() => modal.style.display = "none", 300);
    }
}


// ==================================================
// ✅ OPEN STOCK IN: ID LANG ANG KAILANGAN
// ==================================================
// Function to generate unique Batch Number automatically (e.g. BATCH-20260727-8A3F)
function generateAutoBatch() {
    const today = new Date();
    const dateStr = today.getFullYear().toString() +
                    String(today.getMonth() + 1).padStart(2, '0') +
                    String(today.getDate()).padStart(2, '0');
    
    // Generates 4 random alphanumeric characters
    const randomCode = Math.random().toString(36).substring(2, 6).toUpperCase();
    
    const generatedBatch = `BATCH-${dateStr}-${randomCode}`;
    
    let batchInput = document.getElementById('batch_number_input');
    if (batchInput) {
        batchInput.value = generatedBatch;
    }
}

function openStockInModal(medicineId) {

    let form = document.getElementById('stockInForm');
    if(form) form.reset();

    let inputId = document.getElementById('stock_in_medicine_id');
    if(inputId) inputId.value = medicineId;

    let formModal = document.getElementById('stockInModal');
    if(formModal) {
        formModal.style.cssText = `
            display: flex !important; 
            visibility: visible !important; 
            opacity: 1 !important;
            pointer-events: auto !important; 
            overflow-y: auto !important;
            z-index: 10000 !important;
        `;
        let innerForm = formModal.querySelector('.modal');
        if(innerForm) innerForm.style.pointerEvents = 'auto';
    }

    // ✅ AUTOMATICALLY GENERATE BATCH NUMBER
    generateAutoBatch();

    document.querySelectorAll('.error-feedback').forEach(el => el.style.display = 'none');
    let batchInput = document.getElementById('batch_number_input');
    if(batchInput) batchInput.style.border = "1px solid #D1D5DB";
}


// ==================================================
// ✅ CLOSE FUNCTION
// ==================================================
function closeStockInModal() {
    const modal = document.getElementById("stockInModal");
    if (modal) {
        modal.style.display = "none";
        let form = document.getElementById('stockInForm');
        if(form) form.reset();
    }
}


// ==================================================
// ✅ EDIT FUNCTIONS
// ==================================================
function openStockEditModal(medicineId, medicineName, batchId, batchNumber, expiry, quantity, remarks) {
    document.getElementById('edit_medicine_id').value = medicineId;
    document.getElementById('edit_batch_id').value = batchId;
    document.getElementById('edit_medicine_name').value = medicineName;
    document.getElementById('edit_batch_number').value = batchNumber;
    document.getElementById('edit_expiry').value = expiry;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_remarks').value = remarks;
    
    let editModal = document.getElementById('editStockModal');
    if(editModal) editModal.style.cssText = `display: flex !important; visibility: visible !important; opacity: 1 !important; pointer-events: auto !important;`;
}

function closeEditStockModal() {
    document.getElementById('editStockModal').style.display = 'none';
}


// ==================================================
// ✅ FORM SUBMIT & VALIDATION LOGIC (FIXED SWAL ERROR)
// ==================================================
document.addEventListener('DOMContentLoaded', function () {
    const batchInput = document.getElementById('batch_number_input');
    const form = document.getElementById('stockInForm');

    // LIVE CHECK BATCH
    if(batchInput){
        batchInput.addEventListener('blur', function () {
            let batchVal = this.value.trim();
            if (batchVal === '') {
                document.getElementById('batch_error_text').style.display = 'none';
                this.style.border = "1px solid #D1D5DB";
                return;
            }
            fetch("{{ route('check.batch') }}?batch_number=" + encodeURIComponent(batchVal))
                .then(res => res.json())
                .then(data => {
                    document.getElementById('batch_error_text').style.display = data.exists ? 'block' : 'none';
                    this.style.border = data.exists ? "2px solid #EF4444" : "1px solid #10B981";
                });
        });
    }

    // SAVE TO DATABASE (STOCK CONTROLLER)
    if(form){
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const errorTextAjax = document.getElementById('batch_error_text_ajax');
            
            if(batchInput) batchInput.style.border = "1px solid #D1D5DB";
            if(errorTextAjax) errorTextAjax.style.display = 'none';

            fetch("{{ route('stock.in.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw data;
                return data;
            })
            .then(data => {
                if (data.success) {
                    closeStockInModal();
                    window.location.reload();
                }
            })
            .catch(errors => {
                if(batchInput) batchInput.style.border = "2px solid #EF4444";
                if(errorTextAjax) {
                    errorTextAjax.style.display = 'block';
                    errorTextAjax.innerText = errors.errors?.batch_number ? `⚠️ ${errors.errors.batch_number[0]}` : `⚠️ ${errors.message || 'Error!'}`;
                }
            });
        });
    }
});

document.getElementById('addMedicineForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const alertBox = document.getElementById('med_duplicate_alert');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => {
        if (response.status === 422) {
            alertBox.style.display = 'block';
            
            const modalBody = form.closest('.modal');
            if (modalBody) modalBody.scrollTop = 0;
            
        } else if (response.ok) {
            window.location.href = window.location.pathname + "?success_med=1";
        }
    })
    .catch(error => {
        console.error('Submission Error:', error);
    });
});


</script>


</body>
</html>
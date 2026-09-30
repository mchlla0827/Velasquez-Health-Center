<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Forecast - Velasquez Health Center</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            color: #1F2937;
        }
        .container { display: flex; }
        .main { margin-left: 260px; width: calc(100% - 260px); padding: 24px; box-sizing: border-box; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; }
        .welcome-text { font-size: 14px; color: #374151; margin-bottom: 5px; }
        .welcome-name { font-weight: bold; color: #1F2937; font-size: 18px; }
        .role { color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-left: 6px; text-transform: uppercase; }
        .right { text-align: right; font-size: 12px; color: #374151; }
        .header-divider { width: 100%; height: 1px; background: #E5E7EB; margin: 16px 0; }

        .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .page-title { font-size: 24px; font-weight: 700; color: #1e293b; letter-spacing: -0.02em; margin: 0; }

        .forecast-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    padding: 22px 24px;
    margin-bottom: 18px;
    background: linear-gradient(
        135deg,
        #F8FBFF 0%,
        #FFFFFF 100%
    );
    border: 1px solid #DCE6F2;
    border-radius: 14px;
}

.forecast-hero-text {
    min-width: 0;
}

.eyebrow {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.12em;
    color: #2563EB;
    margin-bottom: 7px;
    font-style: Arial, sans-serif;
}

.forecast-page-title {
    margin: 0;
    font-size: 27px;
    line-height: 1.2;
    font-weight: 750;
    color: #0F172A;
}

.forecast-page-subtitle {
    margin: 7px 0 0;
    max-width: 680px;
    font-size: 13px;
    line-height: 1.55;
    color: #64748B;
}

.forecast-hero-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.hero-btn {
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.hero-btn.primary {
    background: #2563EB;
    color: #fff;
}

.hero-btn.primary:hover {
    background: #1D4ED8;
}

.hero-btn.secondary {
    background: #fff;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
}

.hero-btn.secondary:hover {
    background: #EFF6FF;
}

        .forecast-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    padding: 8px 2px 14px;
    font-size: 10.5px;
    color: #64748B;
}

.forecast-meta b {
    color: #334155;
}
       .forecast-status-card {
    background: #FFFFFF;

    /* blue accent around the whole status section */
    border: 1px solid #BFDBFE;
    border-radius: 12px;

    margin-bottom: 20px;
    overflow: hidden;

    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.05);
}

/* Main healthy status */
.forecast-status-main {
    display: flex;
    align-items: flex-start;
    gap: 12px;

    padding: 17px 19px;

    background: #EFF6FF;
}

.forecast-status-indicator.success {
    width: 10px;
    height: 10px;

    margin-top: 5px;

    background: #10B981;
    border-radius: 50%;

    flex-shrink: 0;

    box-shadow: 0 0 0 4px #D1FAE5;
}

.forecast-status-indicator.warning {
    width: 10px;
    height: 10px;

    margin-top: 5px;

    background: #F59E0B;
    border-radius: 50%;

    flex-shrink: 0;

    box-shadow: 0 0 0 4px #FEF3C7;
}

/* Small heading */
.forecast-status-label {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.1em;

    color: #2563EB;

    margin-bottom: 3px;
}

/* Main status */
.forecast-status-title {
    font-size: 15px;
    font-weight: 700;

    color: #065F46;
}

/* Supporting text */
.forecast-status-message {
    margin-top: 4px;

    font-size: 11.5px;
    line-height: 1.5;

    color: #334155;
}


/* =========================================================
   LIMITATION / WARNING
   ========================================================= */

.forecast-status-note {
    padding: 11px 19px;

    background: #FFFBEB;

    border-top: 1px solid #FDE68A;

    font-size: 11px;
    line-height: 1.5;

    color: #92400E;
}

.forecast-status-note b {
    color: #78350F;
}

.forecast-note-toggle {
    border: none;
    background: none;

    padding: 0;
    margin-left: 5px;

    color: #B45309;

    font-size: 11px;
    font-weight: 700;

    text-decoration: underline;

    cursor: pointer;
}

.forecast-note-toggle:hover {
    color: #92400E;
}


/* Expanded details */
.forecast-note-details {
    display: none;

    padding: 10px 19px 12px;

    background: #FFFDF5;

    border-top: 1px solid #FDE68A;

    font-size: 10.5px;
    line-height: 1.55;

    color: #92400E;
}

.forecast-note-details.open {
    display: block;
}
 /* =========================================================
   KPI / SUMMARY CARDS
   ========================================================= */

.summary-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 16px;

    width: 100%;

    margin-bottom: 24px;
}


.summary-card {
    position: relative;

    min-width: 0;
    min-height: 128px;

    padding: 16px 17px 15px;

    background: #FFFFFF;

    border: 1px solid #E5E7EB;

    border-radius: 11px;

    box-shadow:
        0 1px 2px rgba(15, 23, 42, 0.04);

    overflow: hidden;

    transition:
        transform 0.15s ease,
        box-shadow 0.15s ease,
        border-color 0.15s ease;
}


.summary-card:hover {
    transform: translateY(-2px);

    border-color: #D1D5DB;

    box-shadow:
        0 5px 14px rgba(15, 23, 42, 0.07);
}


/* -----------------------------------------
   TOP ACCENT
   ----------------------------------------- */

.summary-card.summary-blue {
    border-top: 3px solid #2563EB;
}

.summary-card.summary-red {
    border-top: 3px solid #DC2626;
}

.summary-card.summary-amber {
    border-top: 3px solid #D97706;
}

.summary-card.summary-purple {
    border-top: 3px solid #7C3AED;
}


/* -----------------------------------------
   TOP ROW
   ----------------------------------------- */

.summary-card-top {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;
}


/* -----------------------------------------
   LABEL
   ----------------------------------------- */

.summary-card-label {
    font-size: 10.5px;

    line-height: 1.35;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: 0.05em;

    color: #64748B;
}


/* -----------------------------------------
   ICON
   ----------------------------------------- */

.summary-icon {
    width: 29px;
    height: 29px;

    flex: 0 0 29px;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    border-radius: 8px;

    font-size: 11px;

    font-weight: 800;
}


.summary-icon.blue {
    background: #DBEAFE;
    color: #1D4ED8;
}

.summary-icon.red {
    background: #FEE2E2;
    color: #B91C1C;
}

.summary-icon.amber {
    background: #FEF3C7;
    color: #92400E;
}

.summary-icon.purple {
    background: #EDE9FE;
    color: #6D28D9;
}


/* -----------------------------------------
   MAIN NUMBER
   ----------------------------------------- */

.summary-card-value {
    margin-top: 13px;

    font-size: 28px;

    line-height: 1;

    font-weight: 700;

    color: #111827;
}


.summary-red .summary-card-value {
    color: #DC2626;
}

.summary-amber .summary-card-value {
    color: #D97706;
}

.summary-purple .summary-card-value {
    color: #7C3AED;
}


/* -----------------------------------------
   DESCRIPTION
   ----------------------------------------- */

.summary-card-help {
    margin-top: 8px;

    font-size: 10.5px;

    line-height: 1.4;

    color: #94A3B8;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 1000px) {

    .summary-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }
}


@media (max-width: 600px) {

    .summary-grid {
        grid-template-columns: 1fr;

        gap: 12px;
    }

    .summary-card {
        min-height: 112px;
    }
}

        /* Filter card - matches Dispense Medicine exactly */
        .filter-card {
            background: white; border: 1px solid #E5E7EB; border-radius: 12px;
            padding: 15px; margin-bottom: 24px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .filter-grid {
    display: grid;
    grid-template-columns: minmax(260px, 1fr) 240px auto;
    align-items: end;
    gap: 14px;
}

.filter-grid > div {
    width: 100%;
    max-width: none;
}

.filter-actions {
    width: auto;
    flex: none;
}
        label { font-size: 12px; font-weight: 600; color: #374151; display: block; margin-bottom: 6px; }
        input, select {
            width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px;
            font-size: 13px; background: #fff; color: #0F172A; outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        input:focus, select:focus { border-color: #1A73E8; box-shadow: 0 0 0 3px rgba(26,115,232,0.15); }
        .filter-actions { flex: 0 0 auto; }
        .clear-filters-btn {
            min-height: 42px; padding: 10px 16px; border: 1px solid #93C5FD; border-radius: 8px;
            background: #EFF6FF; color: #1D4ED8; font-size: 14px; font-weight: 600; cursor: pointer;
        }
        .clear-filters-btn:hover { background: #DBEAFE; border-color: #60A5FA; color: #1E40AF; }

        .btn-primary {
            background-color: #1A73E8; color: white; font-size: 14px; font-weight: 600;
            padding: 10px 18px; border: none; border-radius: 8px; cursor: pointer;
        }
        .btn-primary:hover { background-color: #1557B0; }

       /* =========================================================
   FORECAST TABLE
   ========================================================= */

.table-card {
    width: 100%;
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    margin-bottom: 0;
}

.table-header-title {
    padding: 17px 20px;
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    border-bottom: 1px solid #E5E7EB;
    background: #FFFFFF;
}

.table-card table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
    table-layout: auto;
    text-align: left;
    font-size: 12.5px;
}

.table-card th,
.table-card td {
    padding: 12px 14px;
    border-bottom: 1px solid #E5E7EB;
    color: #374151;
    vertical-align: middle;
}

.table-card th {
    background: #F8FAFC;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748B;
}

.table-card tbody tr {
    transition:
        background-color 0.15s ease,
        box-shadow 0.15s ease;
}

.table-card tbody tr:hover {
    background: #F8FBFF;
    cursor: pointer;
}

.table-card td:first-child {
    font-size: 12.5px;
    color: #111827;
}

.table-card td:nth-child(5) {
    font-weight: 700;
    color: #1D4ED8;
}

.forecast-main-column {
    overflow-x: auto;
}

.table-card {
    overflow-x: auto;
}

        .status-chip { display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 999px; }
        .status-chip.stable { background: #DCFCE7; color: #166534; }
        .status-chip.low { background: #FEF3C7; color: #92400E; }
        .status-chip.shortage { background: #FEE2E2; color: #991B1B; }
        .status-chip.critical { background: #FECACA; color: #7F1D1D; }
        .status-chip.insufficient { background: #F3F4F6; color: #6B7280; }

        /* =========================================================
   MEDICINE DETAIL DRAWER
   ========================================================= */

.detail-panel {
    display: none;

    position: fixed;
    top: 0;
    right: 0;

    width: min(680px, 52vw);
    height: 100vh;

    padding: 24px;

    background: #FFFFFF;

    border-left: 1px solid #E5E7EB;

    box-shadow:
        -8px 0 30px rgba(15, 23, 42, 0.12);

    z-index: 1000;

    overflow-y: auto;
}

.detail-panel.open {
    display: block;
}


/* -----------------------------------------
   DRAWER HEADER
   ----------------------------------------- */

.detail-head {
    position: sticky;
    top: -24px;

    display: flex;
    justify-content: space-between;
    align-items: flex-start;

    margin: -24px -24px 18px;
    padding: 20px 24px 16px;

    background: #FFFFFF;

    border-bottom: 1px solid #E5E7EB;

    z-index: 2;
}

.detail-head h3 {
    margin: 0 0 7px 0;

    font-size: 18px;
    font-weight: 700;

    color: #111827;
}


/* -----------------------------------------
   CLOSE BUTTON
   ----------------------------------------- */

.detail-close {
    width: 34px;
    height: 34px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0;

    background: #F8FAFC;

    border: 1px solid #E5E7EB;
    border-radius: 8px;

    font-size: 20px;
    line-height: 1;

    cursor: pointer;

    color: #64748B;
}

.detail-close:hover {
    background: #F1F5F9;
    color: #111827;
}


/* -----------------------------------------
   TOP METRICS
   ----------------------------------------- */

.detail-panel .metric-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 10px;

    margin-bottom: 16px;
}

.detail-panel .metric-box {
    min-width: 0;

    background: #F9FAFB;

    border: 1px solid #E5E7EB;
    border-radius: 9px;

    padding: 12px;
}

.detail-panel .metric-box .k {
    font-size: 10px;
    font-weight: 700;

    text-transform: uppercase;

    color: #64748B;

    line-height: 1.3;
}

.detail-panel .metric-box .v {
    margin-top: 5px;

    font-size: 19px;
    font-weight: 700;

    color: #111827;
}


/* -----------------------------------------
   CALCULATION
   ----------------------------------------- */

.detail-panel .calc-box {
    background: #EFF6FF;

    border: 1px solid #BFDBFE;

    border-radius: 9px;

    padding: 13px 15px;

    margin-bottom: 18px;

    font-size: 12px;
    line-height: 1.6;

    color: #1E3A8A;
}


/* -----------------------------------------
   MAIN DETAIL CONTENT
   SINGLE COLUMN
   ----------------------------------------- */

.detail-cols {
    display: flex;
    flex-direction: column;

    gap: 18px;

    width: 100%;
}

.detail-cols > div {
    width: 100%;
    min-width: 0;
}


/* -----------------------------------------
   HISTORY TABLE
   ----------------------------------------- */

.hist-table {
    width: 100%;

    border-collapse: collapse;

    margin-bottom: 14px;

    font-size: 12.5px;
}

.hist-table th,
.hist-table td {
    padding: 9px 10px;

    border-bottom: 1px solid #E5E7EB;

    font-size: 12px;

    text-align: left;
}

.hist-table th {
    background: #F8FAFC;

    font-weight: 700;

    color: #64748B;
}

.hist-table tr.forecast-row td {
    font-weight: 700;

    color: #2563EB;

    background: #EFF6FF;
}


/* -----------------------------------------
   CHART
   ----------------------------------------- */

.chart-section-title {
    margin-top: 12px;

    font-size: 14px;
    font-weight: 700;

    color: #111827;
}

.chart-section-subtitle {
    margin-top: 3px;
    margin-bottom: 10px;

    font-size: 10.5px;

    color: #94A3B8;
}

.chart-wrap {
    position: relative;
    width: 100%;
    height: 230px;
}

#dp-chart {
    display: block;
}

/* -----------------------------------------
   CONTEXT BOXES
   ----------------------------------------- */

.detail-panel .context-box {
    width: 100%;

    background: #FFFFFF;

    border: 1px solid #E5E7EB;

    border-radius: 9px;

    padding: 12px 14px;

    margin-bottom: 10px;

    font-size: 12px;

    line-height: 1.5;

    color: #475569;
}

.detail-panel .context-box b {
    color: #111827;
}


/* -----------------------------------------
   SHORTAGE / RESTOCK
   ----------------------------------------- */

.detail-cols .metric-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 10px;

    margin-top: 12px;
}

.detail-cols .metric-box .v {
    font-size: 20px;
}


/* -----------------------------------------
   RECOMMENDATION
   ----------------------------------------- */

#dp-recommendation {
    background: #F8FAFC;

    border: 1px solid #CBD5E1;

    border-left: 4px solid #2563EB;

    border-radius: 9px;

    padding: 13px 14px;

    margin-top: 12px !important;
    margin-bottom: 10px;

    font-size: 12.5px;

    line-height: 1.5;
}

#dp-recommendation b {
    color: #1E3A8A;
}


/* -----------------------------------------
   WHY RECOMMENDATION
   ----------------------------------------- */

.why-box {
    width: 100%;

    border: 1px solid #E5E7EB;

    border-left: 4px solid #2563EB;

    background: #F9FAFB;

    border-radius: 9px;

    padding: 13px 15px;

    margin-top: 10px;

    font-size: 12px;

    line-height: 1.6;

    color: #374151;
}

.why-box ul {
    margin: 7px 0 10px 18px;

    padding: 0;
}

.why-box li {
    margin-bottom: 4px;
}

.why-box .why-h {
    font-weight: 700;

    color: #111827;
}


/* -----------------------------------------
   STAFF REVIEW
   ----------------------------------------- */

.review-box {
    width: 100%;

    margin-top: 16px;

    padding: 16px;

    background: #F8FBFF;

    border: 1px solid #BFDBFE;

    border-radius: 10px;
}

.review-title {
    font-size: 14px;

    font-weight: 700;

    color: #0F172A;

    margin-bottom: 4px;
}

.review-description {
    font-size: 11px;

    line-height: 1.5;

    color: #64748B;

    margin-bottom: 14px;
}

.review-suggestion {
    display: flex;

    justify-content: space-between;
    align-items: center;

    padding: 11px 13px;

    margin-bottom: 12px;

    background: #FFFFFF;

    border: 1px solid #DBEAFE;

    border-radius: 8px;

    font-size: 12px;

    color: #334155;
}

.review-suggestion strong {
    font-size: 20px;

    color: #1D4ED8;
}


/* -----------------------------------------
   MOBILE
   ----------------------------------------- */

@media (max-width: 900px) {

    .detail-panel {
        width: 100vw;
        max-width: 100vw;
    }

    .detail-panel .metric-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }
}
        .detail-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
        .detail-head h3 { margin: 0; font-size: 17px; font-weight: 700; color: #111827; }
        .detail-close { background: none; border: none; font-size: 20px; cursor: pointer; color: #9CA3AF; }
        .metric-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px; }
        .metric-box { background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px 14px; }
        .metric-box .k { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6B7280; }
        .metric-box .v { font-size: 18px; font-weight: 700; margin-top: 3px; color: #111827; }
        .metric-box.warn .v { color: #DC2626; }

        .calc-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 12px 16px; font-size: 12.5px; color: #1E3A8A; margin-bottom: 16px; line-height: 1.6; }
        .conf-chip { display: inline-block; padding: 2px 9px; border-radius: 999px; font-size: 11px; font-weight: 600; }
        .conf-chip.high { background: #D1FAE5; color: #065F46; }
        .conf-chip.medium { background: #FEF3C7; color: #92400E; }
        .conf-chip.low { background: #FEE2E2; color: #991B1B; }
        .conf-chip.none { background: #F3F4F6; color: #6B7280; }
        .range-sub { display: block; font-size: 11px; color: #9CA3AF; font-weight: 400; }
        .why-box { border: 1px solid #E5E7EB; border-left: 4px solid #2563EB; background: #F9FAFB; border-radius: 8px; padding: 10px 14px; font-size: 12px; color: #374151; margin-top: 10px; line-height: 1.55; }
        .why-box ul { margin: 4px 0 8px 16px; padding: 0; }
        .why-box .why-h { font-weight: 700; color: #111827; }
        .notice-strip { background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; border-radius: 8px; padding: 10px 14px; font-size: 12.5px; line-height: 1.5; margin-bottom: 14px; }
        .notice-strip b { color: #78350F; }
        .trend-up { color: #B45309; font-weight: 600; }
        .trend-down { color: #047857; font-weight: 600; }
        .trend-none { color: #9CA3AF; }
        .trend-chip { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; white-space: nowrap; }
        .trend-chip.up   { background: #FEF3C7; color: #B45309; }  /* demand rising  - amber */
        .trend-chip.down { background: #DBEAFE; color: #1D4ED8; }  /* demand falling - blue  */
        .trend-chip.flat { background: #E5E7EB; color: #374151; }  /* no change      - grey  */
        .trend-chip.none { background: #F3F4F6; color: #9CA3AF; font-weight: 600; }  /* not applied / no forecast */
        .calc-box code { background: #fff; padding: 1px 6px; border-radius: 4px; font-weight: 700; }

        .detail-cols { display: grid; flex-direction: column; gap: 20px; }
        .hist-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 12px; }
        .hist-table th, .hist-table td { padding: 8px 10px; font-size: 12.5px; }
        .hist-table tr.forecast-row td { font-weight: 700; color: #2563EB; background: #EFF6FF; }

        .context-box { border: 1px solid #E5E7EB; border-radius: 8px; padding: 10px 14px; font-size: 12px; color: #4B5563; margin-bottom: 10px; }
        .context-box b { color: #111827; }
        .transparency-footer { margin-top: 14px; padding-top: 14px; border-top: 1px dashed #E5E7EB; display: flex; gap: 24px; flex-wrap: wrap; font-size: 11.5px; color: #6B7280; }
        .transparency-footer b { color: #374151; }

        @media (max-width: 1000px) {
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
            .metric-grid { grid-template-columns: repeat(2, 1fr); }
            .detail-cols { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>


<div class="container">
    <x-sidebar />

    <div class="main">

<style>
   /* =========================================================
   FORECAST WORKSPACE
   ========================================================= */

.forecast-workspace {
    display: flex;
    flex-direction: column;
    gap: 18px;
    width: 100%;
}

.forecast-main-column {
    width: 100%;
    min-width: 0;
}

.forecast-insights-column {
    width: 100%;
    min-width: 0;
}
    .ai-insights-panel {
    width: 100%;
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
    .ai-insights-panel-header {
    padding: 17px 20px 13px;
    border-bottom: 1px solid #F1F5F9;
}

.ai-insights-panel-title {
    font-size: 17px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.ai-insights-panel-subtitle {
    font-size: 11.5px;
    color: #64748B;
    margin-top: 4px;
    line-height: 1.5;
}
    .ai-filter-tabs { display: flex; gap: 6px; padding: 12px 16px; flex-wrap: wrap; border-bottom: 1px solid #F1F5F9; }
    .ai-filter-tab { padding: 5px 11px; border-radius: 999px; border: 1px solid #E5E7EB; background: #fff; color: #4B5563; font-size: 11px; font-weight: 600; cursor: pointer; }
    .ai-filter-tab.active { background: #111827; color: #fff; border-color: #111827; }
.ai-insights-panel-body {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0;
    max-height: 420px;
    overflow-y: auto;
}

.ai-insight-item {
    border-left: 4px solid #E5E7EB;
    padding: 14px 16px;
    border-bottom: 1px solid #F1F5F9;
    min-width: 0;
}

.ai-insight-title-text {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 5px;
    line-height: 1.4;
}

.ai-insight-detail {
    font-size: 11.5px;
    color: #4B5563;
    line-height: 1.55;
    margin: 0;
}
    .ai-insight-item.sev-critical { border-left-color: #DC2626; background: #FEF2F2; }
    .ai-insight-item.sev-warning { border-left-color: #D97706; background: #FFFBEB; }
    .ai-insight-item.sev-info { border-left-color: #2563EB; background: #F0F9FF; }
    .ai-insight-item.sev-positive { border-left-color: #16A34A; background: #F0FDF4; }
    .ai-sev-badge { display: inline-block; font-size: 10px; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase; padding: 2px 8px; border-radius: 4px; margin-bottom: 5px; }
    .ai-sev-badge.sev-critical { background: #FEE2E2; color: #991B1B; }
    .ai-sev-badge.sev-warning { background: #FEF3C7; color: #92400E; }
    .ai-sev-badge.sev-info { background: #DBEAFE; color: #1E40AF; }
    .ai-sev-badge.sev-positive { background: #DCFCE7; color: #166534; }
    .ai-insight-title-text { font-size: 13px; font-weight: 700; color: #111827; margin: 0 0 3px; line-height: 1.3; }
    .ai-insight-detail { font-size: 11.5px; color: #4B5563; line-height: 1.5; margin: 0; }
    .ai-insights-empty { padding: 30px 16px; text-align: center; color: #9CA3AF; font-size: 12px; }
    .ai-insights-panel-footnote { padding: 10px 16px; font-size: 10px; color: #9CA3AF; border-top: 1px solid #F1F5F9; }

    .review-box {
    margin-top: 14px;
    padding: 14px;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
}

.review-title {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.review-description {
    font-size: 11.5px;
    line-height: 1.5;
    color: #6B7280;
    margin-bottom: 12px;
}

.review-suggestion {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    margin-bottom: 12px;
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    border-radius: 7px;
    color: #1E3A8A;
    font-size: 12px;
}

.review-suggestion strong {
    font-size: 18px;
    color: #1D4ED8;
}

.review-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.review-btn {
    border: none;
    border-radius: 7px;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.review-btn.accept {
    background: #DCFCE7;
    color: #166534;
}

.review-btn.accept:hover {
    background: #BBF7D0;
}

.review-btn.edit {
    background: #DBEAFE;
    color: #1D4ED8;
}

.review-btn.edit:hover {
    background: #BFDBFE;
}

.review-btn.dismiss {
    background: #FEE2E2;
    color: #991B1B;
}

.review-btn.dismiss:hover {
    background: #FECACA;
}

.review-form-area {
    margin-top: 12px;
}

.review-label {
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
    font-size: 11px;
    font-weight: 700;
    color: #374151;
}

.review-input {
    width: 100%;
    padding: 9px 11px;
    border: 1px solid #D1D5DB;
    border-radius: 7px;
    background: white;
    font-size: 12px;
    color: #111827;
    outline: none;
}

.review-input:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.review-form-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.review-submit-btn {
    border: none;
    border-radius: 7px;
    padding: 8px 16px;
    background: #2563EB;
    color: white;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.review-submit-btn:hover {
    background: #1D4ED8;
}

.review-cancel-btn {
    border: 1px solid #D1D5DB;
    border-radius: 7px;
    padding: 8px 16px;
    background: white;
    color: #374151;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.chart-section-title {
    margin-top: 14px;
    font-size: 13px;
    font-weight: 700;
    color: #111827;
}

.chart-section-subtitle {
    margin-top: 3px;
    margin-bottom: 10px;
    font-size: 10.5px;
    color: #94A3B8;
}

.drawer-backdrop {
    display: none;

    position: fixed;
    inset: 0;

    background: rgba(15, 23, 42, 0.28);

    z-index: 999;
}

.drawer-backdrop.open {
    display: block;
}
</style>



        <div class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>
                <div class="welcome-name">
                    {{ $userName }}
                    <span class="role" style="background-color: {{ $roleColor }};">{{ strtoupper($role) }}</span>
                </div>
            </div>
            <div class="right">
                <b>Velasquez Health Center</b><br>
                @php date_default_timezone_set('Asia/Manila'); @endphp
                {{ date('F d, Y | h:i A') }}
            </div>
        </div>

        <div class="header-divider"></div>
        @if (session('success'))
    <div
        class="notice-strip"
        style="
            background:#ECFDF5;
            border-color:#A7F3D0;
            color:#065F46;
        "
    >
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div
        class="notice-strip"
        style="
            background:#FEF2F2;
            border-color:#FECACA;
            color:#991B1B;
        "
    >
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div
        class="notice-strip"
        style="
            background:#FEF2F2;
            border-color:#FECACA;
            color:#991B1B;
        "
    >
        {{ $errors->first() }}
    </div>
@endif

<div class="forecast-hero">

    <div class="forecast-hero-text">

        <div class="eyebrow">
            MEDICINE INVENTORY DECISION SUPPORT
        </div>

        <h1 class="forecast-page-title">
            AI-Assisted Medicine Forecasting
        </h1>

        <p class="forecast-page-subtitle">
            Review projected medicine demand, inventory risks,
            and system recommendations for the next 30 days.
        </p>

    </div>

    <div class="forecast-hero-actions">

        <a
            href="{{
                $role === 'nurse'
                    ? route('nurse.forecast.accuracy')
                    : route('admin.forecast.accuracy')
            }}"
            class="hero-btn secondary"
        >
            Forecast Accuracy
        </a>

        <a
            href="{{
                $role === 'nurse'
                    ? route('nurse.forecast.history')
                    : route('admin.forecast.history')
            }}"
            class="hero-btn primary"
        >
            Forecast History
        </a>

    </div>

</div>

<div class="forecast-meta">
    <span><b>Method:</b> Trend-Adjusted 3-Month Moving Average</span>
    <span><b>Data:</b> Dispensed + Unmet Demand</span>
    <span><b>Horizon:</b> Next 30 Days</span>
</div>

<div class="forecast-status-card">

    <div class="forecast-status-main">

        <div class="forecast-status-indicator {{ $aiStatus }}"></div>

        <div>

            <div class="forecast-status-label">
                FORECAST STATUS
            </div>

            <div class="forecast-status-title">
                {{ $aiTitle }}
            </div>

            <div class="forecast-status-message">
                {{ $aiMessage }} {{ $aiAction }}
            </div>

        </div>

    </div>


    @if (
        $seasonalReadyCount < count($forecastData)
        || $lowConfidenceCount > 0
    )

        <div class="forecast-status-note">

            <b>Data limitation:</b>

            Seasonal forecasting is not yet available.
            Current forecasts use recent level and trend only.

            {{ $lowConfidenceCount }}
            of {{ count($forecastData) }} forecasts have
            Low confidence.

            <button
                type="button"
                class="forecast-note-toggle"
                onclick="toggleForecastNote()"
            >
                View details
            </button>

        </div>

        <div
            class="forecast-note-details"
            id="forecastNoteDetails"
        >

            Seasonal demand requires at least
            24 months of dispensing history,
            with dispensing recorded in at least
            12 of those months.

            Currently, only
            {{ $seasonalReadyCount }}
            of {{ count($forecastData) }}
            medicines meet that requirement.

            The trend adjustment is applied to
            {{ $trendAppliedCount }}
            of {{ count($forecastData) }}
            medicines.

        </div>

    @endif

</div>

<div class="summary-grid">

    <!-- Medicines Analyzed -->
    <div class="summary-card summary-blue">

        <div class="summary-card-top">

            <div class="summary-card-label">
                Medicines Analyzed
            </div>

            <div class="summary-icon blue">
                M
            </div>

        </div>

        <div class="summary-card-value">
            {{ count($forecastData) }}
        </div>

        <div class="summary-card-help">
            Medicines included in today's forecast
        </div>

    </div>


    <!-- Possible Shortage -->
    <div class="summary-card summary-red">

        <div class="summary-card-top">

            <div class="summary-card-label">
                Possible Shortage
            </div>

            <div class="summary-icon red">
                !
            </div>

        </div>

        <div class="summary-card-value">
            {{ $shortageCount }}
        </div>

        <div class="summary-card-help">
            Forecast demand may exceed usable stock
        </div>

    </div>


    <!-- Low Stock -->
    <div class="summary-card summary-amber">

        <div class="summary-card-top">

            <div class="summary-card-label">
                Low Stock / Critical
            </div>

            <div class="summary-icon amber">
                !
            </div>

        </div>

        <div class="summary-card-value">
            {{ $lowStockCount }}
        </div>

        <div class="summary-card-help">
            Medicines needing closer inventory review
        </div>

    </div>


    <!-- Expiring Soon -->
    <div class="summary-card summary-purple">

        <div class="summary-card-top">

            <div class="summary-card-label">
                Expiring Soon
            </div>

            <div class="summary-icon purple">
                E
            </div>

        </div>

        <div class="summary-card-value">
            {{ $expiringSoonCount }}
        </div>

        <div class="summary-card-help">
            Batches expiring within 180 days
        </div>

    </div>

</div>

        <div class="forecast-workspace">

    <!-- LEFT: FORECAST TABLE -->
    <div class="forecast-main-column">

        <div class="filter-card">

            <div class="filter-grid">

                <div>
                    <label>Search</label>

                    <input
                        type="text"
                        id="searchBox"
                        placeholder="Search medicine..."
                    >
                </div>

                <div>
                    <label>Status Filter</label>

                    <select id="statusFilter">

                        <option value="all">
                            All
                        </option>

                        <option value="stable">
                            Stable
                        </option>

                        <option value="low">
                            Low Stock
                        </option>

                        <option value="shortage">
                            Possible Shortage
                        </option>

                        <option value="critical">
                            Critical
                        </option>

                        <option value="insufficient">
                            Insufficient Data
                        </option>

                    </select>

                </div>

                <div class="filter-actions">

                    <button
                        type="button"
                        class="clear-filters-btn"
                        id="clearFiltersBtn"
                    >
                        Clear Filters
                    </button>

                </div>

            </div>

        </div>


        <div class="table-card">

            <div class="table-header-title">

                Predictive Forecast Metrics

            </div>

            <table>

                <thead>

                    <tr>

                        <th>Medicine</th>

                        <th>Usable Stock</th>

                        <th>3-Month Avg</th>

                        <th>Trend</th>

                        <th>Forecast (30d)</th>

                        <th>Confidence</th>

                        <th>Shortage</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody id="forecastTableBody">

                    @foreach ($forecastData as $i => $data)

                        @php

                            $statusKey = match(true) {

                                $data['status'] === 'Critical'
                                    => 'critical',

                                $data['status'] === 'Possible Shortage'
                                    => 'shortage',

                                $data['status'] === 'Low Stock Alert'
                                    => 'low',

                                $data['status'] === 'Insufficient Data'
                                    => 'insufficient',

                                default
                                    => 'stable',

                            };

                            $threeMonthAvg =
                                $data['moving_average'];

                            $trendLabel = match(
                                $data['trend_direction']
                            ) {

                                'up'
                                    => '&uarr; +' .
                                       abs(
                                           $data['trend_adjustment']
                                       ),

                                'down'
                                    => '&darr; -' .
                                       abs(
                                           $data['trend_adjustment']
                                       ),

                                'flat'
                                    => '&rarr; 0',

                                default
                                    => 'Not applied',

                            };

                            $shortageAmt =
                                $data['forecast_available']
                                    ? max(
                                        0,
                                        $data['estimated_demand']
                                        - $data['usable_stock']
                                    )
                                    : 0;

                        @endphp

                        <tr
                            class="med-row"
                            data-index="{{ $i }}"
                            data-status="{{ $statusKey }}"
                            data-name="{{ strtolower($data['medicine']->name) }}"
                        >

                            <td>

                                <strong>
                                    {{ $data['medicine']->name }}
                                </strong>

                                <span
                                    style="color:#9CA3AF;"
                                >
                                    {{ $data['medicine']->dosage_strength ?? '' }}
                                </span>

                            </td>

                            <td>
                                {{ number_format($data['usable_stock']) }}
                            </td>

                            <td>

                                {{
                                    $data['forecast_available']
                                        ? number_format($threeMonthAvg)
                                        : '-'
                                }}

                            </td>

                            <td>
                                @php
                                    $trendClass = $data['forecast_available']
                                        && in_array($data['trend_direction'], ['up', 'down', 'flat'], true)
                                            ? $data['trend_direction']
                                            : 'none';
                                @endphp

                                <span class="trend-chip {{ $trendClass }}">
                                    {!! $data['forecast_available'] ? $trendLabel : '-' !!}
                                </span>
                            </td>

                            <td>

                                {{
                                    $data['forecast_available']
                                        ? number_format(
                                            $data['estimated_demand']
                                        )
                                        : 'N/A'
                                }}

                                @if (
                                    $data['forecast_available']
                                    && $data['range_low'] !== null
                                )

                                    <span class="range-sub">

                                        {{ $data['range_low'] }}
                                        &ndash;
                                        {{ $data['range_high'] }}

                                    </span>

                                @endif

                            </td>

                            <td>

                                <span
                                    class="conf-chip {{ strtolower($data['confidence']) }}"
                                >

                                    {{
                                        $data['confidence'] === 'None'
                                            ? '-'
                                            : $data['confidence']
                                    }}

                                </span>

                            </td>

                            <td>

                                {{
                                    $data['forecast_available']
                                    && $shortageAmt > 0
                                        ? number_format($shortageAmt)
                                        : '-'
                                }}

                            </td>

                            <td>

                                <span
                                    class="status-chip {{ $statusKey }}"
                                >

                                    {{ $data['status'] }}

                                </span>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    <!-- RIGHT: AI INSIGHTS -->
    <aside class="forecast-insights-column">

        <div class="ai-insights-panel">

            <div class="ai-insights-panel-header">

                <h1 class="ai-insights-panel-title">
                    AI Insights
                </h1>

                <div class="ai-insights-panel-subtitle">
                    System-generated observations that may need
                    staff attention.
                </div>

            </div>


            <div
                class="ai-filter-tabs"
                id="aiFilterTabs"
            >

                <button
                    type="button"
                    class="ai-filter-tab active"
                    data-filter="all"
                >
                    All
                </button>

                <button
                    type="button"
                    class="ai-filter-tab"
                    data-filter="critical"
                >
                    Critical
                </button>

                <button
                    type="button"
                    class="ai-filter-tab"
                    data-filter="warning"
                >
                    Warning
                </button>

                <button
                    type="button"
                    class="ai-filter-tab"
                    data-filter="info"
                >
                    Info
                </button>

                <button
                    type="button"
                    class="ai-filter-tab"
                    data-filter="positive"
                >
                    Positive
                </button>

            </div>


            <div
                class="ai-insights-panel-body"
                id="aiInsightsList"
            >

                <div class="ai-insights-empty">
                    Loading insights...
                </div>

            </div>


            <div class="ai-insights-panel-footnote">

                These are system-generated observations.
                Staff review is still required before taking
                inventory action.

            </div>

        </div>

    </aside>

</div>

        <div
            class="drawer-backdrop"
            id="drawerBackdrop"
            onclick="closeDetail()"
        ></div>
        <div class="detail-panel" id="detailPanel">
            <div class="detail-head">
                <div>
                    <h3 id="dp-name">-</h3>
                    <span class="status-chip" id="dp-status-chip">-</span>
                </div>
                <button class="detail-close" onclick="closeDetail()">&times;</button>
            </div>

            <div class="metric-grid">
                <div class="metric-box"><div class="k">Usable Stock</div><div class="v" id="dp-usable">-</div></div>
                <div class="metric-box warn"><div class="k">Expired Stock</div><div class="v" id="dp-expired">-</div></div>
                <div class="metric-box"><div class="k">Low-Stock Threshold</div><div class="v" id="dp-threshold">-</div></div>
                <div class="metric-box"><div class="k">Expiring Soon (180d)</div><div class="v" id="dp-expiring">-</div></div>
            </div>

            <div class="calc-box" id="dp-calc"></div>

            <div class="detail-cols">
                <div>
                    <table class="hist-table">
                        <thead><tr><th>Period</th><th>Demand (dispensed + unmet)</th></tr></thead>
                        <tbody id="dp-hist-body"></tbody>
                    </table>

                    <div class="chart-section-title">
                        Demand History
                    </div>

                    <div class="chart-section-subtitle">
                        Historical demand compared with the 30-day forecast
                    </div>
                                        <div class="chart-wrap">
                        <canvas id="dp-chart"></canvas>
                    </div>
                </div>
                <div>
                    <div class="context-box" id="dp-stockout-box"></div>
                    <div class="context-box" id="dp-request-box"></div>
                    <div class="metric-grid" style="grid-template-columns: repeat(2,1fr); margin-top: 14px;">
                        <div class="metric-box"><div class="k">Projected Shortage</div><div class="v" id="dp-shortage">-</div></div>
                        <div class="metric-box"><div class="k">Suggested Restock</div><div class="v" id="dp-restock">-</div></div>
                    </div>
                    <div
    class="context-box"
    id="dp-recommendation"
    style="margin-top: 10px;"
></div>

<div class="why-box" id="dp-why"></div>

<!-- Forecast Review -->
<div class="review-box">

    <div class="review-title">
        Staff Review
    </div>

    <div class="review-description">
        Review the system-generated recommendation before
        taking action. The selected decision will be recorded
        in the Activity Log.
    </div>

    <div class="review-suggestion">

        <span>
            System Suggested Quantity
        </span>

        <strong id="dp-review-suggested">
            -
        </strong>

        units

    </div>

    <div class="review-actions">

        <button
            type="button"
            class="review-btn accept"
            onclick="showReview('accept')"
        >
            Accept
        </button>

        <button
            type="button"
            class="review-btn edit"
            onclick="showReview('edit')"
        >
            Edit
        </button>

        <button
            type="button"
            class="review-btn dismiss"
            onclick="showReview('dismiss')"
        >
            Dismiss
        </button>

    </div>

    <div
        id="reviewFormArea"
        style="display:none;"
    >

        <form
            method="POST"
            id="forecastReviewForm"
        >

            @csrf

            <div
                id="reviewQuantityGroup"
                style="display:none;"
            >

                <label
                    for="reviewQuantity"
                    class="review-label"
                >
                    Reviewed Reorder Quantity
                </label>

                <input
                    type="number"
                    id="reviewQuantity"
                    name="quantity"
                    min="0"
                    max="100000"
                    value="0"
                    class="review-input"
                >

            </div>

            <div>

                <label
                    for="reviewReason"
                    class="review-label"
                >
                    Reason / Remarks
                </label>

                <textarea
                    id="reviewReason"
                    name="reason"
                    rows="3"
                    maxlength="500"
                    class="review-input"
                    placeholder="Enter your reason..."
                ></textarea>

            </div>

            <div class="review-form-actions">

                <button
                    type="submit"
                    class="review-submit-btn"
                    id="reviewSubmitBtn"
                >
                    Confirm
                </button>

                <button
                    type="button"
                    class="review-cancel-btn"
                    onclick="cancelReview()"
                >
                    Cancel
                </button>

            </div>

        </form>

    </div>

</div>
                </div>
            </div>

            <div class="transparency-footer">
                <span><b>Method:</b> Trend-Adjusted 3-Month Moving Average</span>
                <span><b>Data Basis:</b> Historical Dispensing Records</span>
                <span><b>Horizon:</b> Next 30 Days</span>
                <span><b>Not an automatic purchase order</b> - staff review required</span>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const forecastData = @json($forecastData);
    let detailChart = null;

    const reviewBaseUrl =
    @json(
        $role === 'nurse'
            ? url('/nurse/forecast/review')
            : url('/admin/forecast/review')
    );

let currentReviewMedicine = null;

    // ---------- Filtering / search ----------
    const rows = document.querySelectorAll('#forecastTableBody tr.med-row');
    const searchBox = document.getElementById('searchBox');
    const statusFilter = document.getElementById('statusFilter');
    const clearBtn = document.getElementById('clearFiltersBtn');

    function applyFilters() {
        const q = searchBox.value.toLowerCase().trim();
        const status = statusFilter.value;
        rows.forEach(row => {
            const matchesFilter = status === 'all' || row.dataset.status === status;
            const matchesSearch = !q || row.dataset.name.includes(q);
            row.style.display = (matchesFilter && matchesSearch) ? '' : 'none';
        });
    }

    searchBox.addEventListener('input', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    clearBtn.addEventListener('click', () => {
        searchBox.value = '';
        statusFilter.value = 'all';
        applyFilters();
    });

    // ---------- Detail panel ----------
    function closeDetail() {

    document
        .getElementById('detailPanel')
        .classList.remove('open');

        document
        .getElementById('drawerBackdrop')
        .classList.remove('open');

    document.body.style.overflow = '';
}

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const data = forecastData[row.dataset.index];
            openDetail(data);
        });
    });

    function statusClass(status) {
        if (status === 'Critical') return 'critical';
        if (status === 'Possible Shortage') return 'shortage';
        if (status === 'Low Stock Alert') return 'low';
        if (status === 'Insufficient Data') return 'insufficient';
        return 'stable';
    }

    function openDetail(data) {
        const med = data.medicine;
        currentReviewMedicine = data;

document.getElementById(
    'dp-review-suggested'
).textContent =
    data.forecast_available
        ? data.suggested_reorder_qty
        : 'N/A';

document.getElementById(
    'reviewFormArea'
).style.display = 'none';

document.getElementById(
    'reviewReason'
).value = '';

document.getElementById(
    'reviewQuantity'
).value =
    data.suggested_reorder_qty || 0;
        document.getElementById('dp-name').textContent = med.name + (med.dosage_strength ? ' ' + med.dosage_strength : '');
        const chip = document.getElementById('dp-status-chip');
        chip.textContent = data.status;
        chip.className = 'status-chip ' + statusClass(data.status);

        document.getElementById('dp-usable').textContent = data.usable_stock;
        document.getElementById('dp-expired').textContent = data.expired_stock;
        document.getElementById('dp-threshold').textContent = data.threshold;
        document.getElementById('dp-expiring').textContent = data.expiring_soon_qty;

        const avg = data.moving_average;

        const trendReason = {
            sparse: 'Trend not applied: fewer than 3 months in the last 6 had any dispensing, so a slope would only chase one-off spikes.',
            short_history: 'Trend not applied: fewer than 4 months of history for this medicine.',
            none: 'Trend not applied.'
        };
        const seasonalNote = '<br><i>' + data.seasonal.message + '</i>';

        if (!data.forecast_available) {
            document.getElementById('dp-calc').innerHTML =
                '<b>Insufficient historical data for a reliable forecast.</b><br>' +
                'This medicine has ' + data.months_available + ' month(s) of usable history. A forecast will become available once at least 3 full months of dispensing history exist.' +
                seasonalNote;
        } else {
            let html = 'Moving Average = (' + data.period3 + ' + ' + data.period2 + ' + ' + data.period1 + ') / 3 = <code>' + avg + ' units</code><br>';
            if (data.trend_applied) {
                const sign = data.trend_adjustment >= 0 ? '+' : '';
                html += 'Trend = <code>' + data.trend_slope + ' units/month</code> (last ' + data.monthly_history.length + ' months)<br>' +
                        'Trend Adjustment = slope x 2-month lag x 0.5 damping = <code>' + sign + data.trend_adjustment + ' units</code><br>';
            } else {
                html += '<i>' + (trendReason[data.trend_status] || trendReason.none) + '</i><br>';
            }
            html += 'Forecast Demand (next 30 days) = <code>' + data.estimated_demand + ' units</code><br>';
            if (data.range_low !== null) {
                html += 'Expected range = forecast &plusmn; 1 standard deviation of recent monthly demand = <code>' + data.range_low + '&ndash;' + data.range_high + ' units</code><br>';
            }
            html += 'Confidence: <span class="conf-chip ' + data.confidence.toLowerCase() + '">' + data.confidence + '</span>';
            if (data.unmet_demand_3mo > 0) {
                html += '<br>Demand includes <code>' + data.unmet_demand_3mo + ' units</code> requested but not dispensed (last 3 months).';
            }
            html += seasonalNote;
            document.getElementById('dp-calc').innerHTML = html;
        }

        // monthly_history is oldest -> newest (6 items): show newest 6 with labels.
        const hist = data.monthly_history;
        const labels6 = ['6 months ago', '5 months ago', '4 months ago', '3 months ago', '2 months ago', 'Last month'];
        const shownFrom = Math.max(0, hist.length - Math.max(3, data.trend_months_available));
        document.getElementById('dp-hist-body').innerHTML =
            hist.slice(shownFrom).map((v, i) => '<tr><td>' + labels6[shownFrom + i] + '</td><td>' + v + '</td></tr>').join('') +
            (data.forecast_available ? '<tr class="forecast-row"><td>Forecast (next 30d)</td><td>' + data.estimated_demand + '</td></tr>' : '');

        const shortageAmt = data.forecast_available ? Math.max(0, data.estimated_demand - data.usable_stock) : 0;
        document.getElementById('dp-shortage').textContent = data.forecast_available ? shortageAmt : 'N/A';
        document.getElementById('dp-restock').textContent = (data.suggested_reorder_qty || 0) +
            (data.suggested_reorder_range ? ' (range ' + data.suggested_reorder_range[0] + '\u2013' + data.suggested_reorder_range[1] + ')' : '');

        const r = data.rationale;
        const li = arr => arr.length ? '<ul>' + arr.map(x => '<li>' + x + '</li>').join('') + '</ul>' : '';
        document.getElementById('dp-why').innerHTML =
            '<span class="why-h">Why this recommendation</span><br>' + r.rule +
            '<ul>' + r.inputs.map(i => '<li>' + i.label + ': <b>' + i.value + '</b></li>').join('') + '</ul>' +
            (data.confidence !== 'None' ? '<span class="why-h">Confidence: ' + data.confidence + '</span>' + li(r.confidence_reasons) : '') +
            (r.caveats.length ? '<span class="why-h">Caveats</span>' + li(r.caveats) : '');
        document.getElementById('dp-recommendation').innerHTML = '<b>Recommendation:</b> ' + data.recommendation;

        const so = data.stock_out_context;
        document.getElementById('dp-stockout-box').innerHTML = so.incident_count > 0
            ? '<b>Stock-Out History (90d):</b> ' + so.incident_count + ' incident(s), ' + so.total_patients_affected + ' patient(s) affected. <i>(Context only - not counted as demand)</i>'
            : '<b>Stock-Out History (90d):</b> No incidents recorded.';

        const rq = data.request_context;
        document.getElementById('dp-request-box').innerHTML = rq.request_count > 0
            ? '<b>Medicine Requests (90d):</b> ' + rq.request_count + ' request(s) totaling ' + rq.total_requested + ' units requested. <i>(Requested is not the same as dispensed - context only)</i>'
            : '<b>Medicine Requests (90d):</b> No requests recorded.';

        // Open the drawer FIRST so the chart can measure a real size.
        document.getElementById('detailPanel').classList.add('open');
        document.getElementById('drawerBackdrop').classList.add('open');
        document.body.style.overflow = 'hidden';

        if (detailChart) detailChart.destroy();

        const forecastOk = !!data.forecast_available;
        const chartLabels = labels6.slice(shownFrom)
            .map(l => l.replace(' months ago', 'mo ago').replace('Last month', 'Last mo'))
            .concat([forecastOk ? 'Forecast' : 'Forecast (N/A)']);
        const chartValues = hist.slice(shownFrom).concat([forecastOk ? data.estimated_demand : 0]);
        const lastIdx = chartValues.length - 1;

        // Draws the value above each bar so numbers are always visible.
        const valueLabels = {
            id: 'valueLabels',
            afterDatasetsDraw(chart) {
                const c = chart.ctx;
                c.save();
                c.font = '600 11px Arial';
                c.fillStyle = '#374151';
                c.textAlign = 'center';
                c.textBaseline = 'bottom';
                chart.getDatasetMeta(0).data.forEach((bar, i) => {
                    const text = (i === lastIdx && !forecastOk) ? 'N/A' : chartValues[i];
                    c.fillText(text, bar.x, bar.y - 4);
                });
                c.restore();
            }
        };

        detailChart = new Chart(document.getElementById('dp-chart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Units',
                    data: chartValues,
                    backgroundColor: hist.slice(shownFrom).map(() => '#93C5FD').concat(['#2563EB']),
                    borderRadius: 4,
                    maxBarThickness: 48
                }]
            },
            plugins: [valueLabels],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                layout: { padding: { top: 18 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => (ctx.dataIndex === lastIdx
                                ? (forecastOk ? 'Forecast (next 30 days): ' + ctx.parsed.y + ' units' : 'Forecast not available')
                                : 'Demand: ' + ctx.parsed.y + ' units')
                        }
                    }
                },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }

    function showReview(decision) {

    if (!currentReviewMedicine) {
        return;
    }

    const reviewFormArea =
        document.getElementById('reviewFormArea');

    const quantityGroup =
        document.getElementById('reviewQuantityGroup');

    const quantityInput =
        document.getElementById('reviewQuantity');

    const reasonInput =
        document.getElementById('reviewReason');

    const form =
        document.getElementById('forecastReviewForm');

    const submitButton =
        document.getElementById('reviewSubmitBtn');

    const medicineId =
        currentReviewMedicine.medicine.id;

    const suggestedQty =
        currentReviewMedicine.suggested_reorder_qty || 0;

    /*
     * ACCEPT
     */
    if (decision === 'accept') {

        const confirmed = confirm(
            'Accept the system suggested reorder quantity of '
            + suggestedQty
            + ' units for '
            + currentReviewMedicine.medicine.name
            + '?'
        );

        if (!confirmed) {
            return;
        }

        quantityGroup.style.display = 'none';

        reasonInput.value = '';

        reasonInput.placeholder =
            'Optional remarks...';

        submitButton.textContent =
            'Confirm Accept';

        form.action =
            reviewBaseUrl
            + '/'
            + medicineId
            + '/accept';

        reviewFormArea.style.display =
            'block';

        return;
    }

    /*
     * EDIT
     */
    if (decision === 'edit') {

        quantityGroup.style.display =
            'block';

        quantityInput.value =
            suggestedQty;

        reasonInput.value = '';

        reasonInput.placeholder =
            'Why did you change the recommended quantity?';

        submitButton.textContent =
            'Save Edit';

        form.action =
            reviewBaseUrl
            + '/'
            + medicineId
            + '/edit';

        reviewFormArea.style.display =
            'block';

        quantityInput.focus();

        return;
    }

    /*
     * DISMISS
     */
    if (decision === 'dismiss') {

        quantityGroup.style.display =
            'none';

        reasonInput.value = '';

        reasonInput.placeholder =
            'Why is this recommendation being dismissed?';

        submitButton.textContent =
            'Confirm Dismiss';

        form.action =
            reviewBaseUrl
            + '/'
            + medicineId
            + '/dismiss';

        reviewFormArea.style.display =
            'block';

        reasonInput.focus();
    }
}

function cancelReview() {

    document.getElementById(
        'reviewFormArea'
    ).style.display = 'none';

    document.getElementById(
        'reviewReason'
    ).value = '';

    document.getElementById(
        'reviewQuantity'
    ).value = '0';

    currentReviewMedicine = null;
}

// ---------- AI Insights ----------

(function () {

    let allInsights = [];

    function renderInsights(filter) {

        const list =
            document.getElementById(
                'aiInsightsList'
            );

        if (!list) {
            return;
        }

        const filtered =
            filter === 'all'
                ? allInsights
                : allInsights.filter(
                    item =>
                        item.severity === filter
                );

        if (filtered.length === 0) {

            list.innerHTML = `
                <div class="ai-insights-empty">
                    No insights in this category right now.
                </div>
            `;

            return;
        }

        list.innerHTML =
            filtered.map(item => `

                <div
                    class="ai-insight-item sev-${item.severity}"
                >

                    <span
                        class="ai-sev-badge sev-${item.severity}"
                    >
                        ${item.severity}
                    </span>

                    <p class="ai-insight-title-text">
                        ${item.title}
                    </p>

                    <p class="ai-insight-detail">
                        ${item.detail}
                    </p>

                    ${
                        item.confidence
                        && item.confidence !== 'None'

                            ? `
                                <p class="ai-insight-detail">

                                    <b>Confidence:</b>
                                    ${item.confidence}

                                    ${
                                        item.why
                                            ? `
                                                &middot;
                                                <b>Why:</b>
                                                ${item.why}
                                              `
                                            : ''
                                    }

                                </p>
                              `

                            : ''
                    }

                </div>

            `).join('');
    }


    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const filterTabs =
                document.getElementById(
                    'aiFilterTabs'
                );

            if (filterTabs) {

                filterTabs.addEventListener(
                    'click',
                    function (event) {

                        const button =
                            event.target.closest(
                                '.ai-filter-tab'
                            );

                        if (!button) {
                            return;
                        }

                        document
                            .querySelectorAll(
                                '.ai-filter-tab'
                            )
                            .forEach(tab => {

                                tab.classList.remove(
                                    'active'
                                );

                            });

                        button.classList.add(
                            'active'
                        );

                        renderInsights(
                            button.dataset.filter
                        );

                    }
                );

            }


            fetch('/forecast/insights')

                .then(response => response.json())

                .then(data => {

                    allInsights =
                        data.insights || [];

                    renderInsights('all');

                })

                .catch(() => {

                    const list =
                        document.getElementById(
                            'aiInsightsList'
                        );

                    if (list) {

                        list.innerHTML = `
                            <div class="ai-insights-empty">
                                Unable to load insights right now.
                            </div>
                        `;

                    }

                });

        }
    );

})();

function toggleForecastNote() {
    document
        .getElementById('forecastNoteDetails')
        .classList.toggle('open');
}
</script>

</body>
</html>
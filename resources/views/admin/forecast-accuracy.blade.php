@php
    date_default_timezone_set('Asia/Manila');

    $forecastRoute =
        $role === 'nurse'
            ? route('nurse.forecast')
            : route('admin.forecast');

    $accuracy = $overallMetrics['accuracy'] ?? null;
    $wape = $overallMetrics['wape'] ?? null;
    $mae = $overallMetrics['mae'] ?? null;
    $evaluationCount = $overallMetrics['n'] ?? 0;

    $accuracyClass = 'neutral';
    $accuracyLabel = 'Not enough data';

    if ($accuracy !== null) {
        if ($accuracy >= 90) {
            $accuracyClass = 'excellent';
            $accuracyLabel = 'High historical accuracy';
        } elseif ($accuracy >= 80) {
            $accuracyClass = 'good';
            $accuracyLabel = 'Good historical accuracy';
        } elseif ($accuracy >= 70) {
            $accuracyClass = 'moderate';
            $accuracyLabel = 'Moderate historical accuracy';
        } else {
            $accuracyClass = 'attention';
            $accuracyLabel = 'Needs review';
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="icon"
          type="image/png"
          href="/bhclogo.jpg">

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Forecast Accuracy - Velasquez Health Center</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F8FAFC;
            color: #1F2937;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 24px;
            min-width: 0;
        }

        /* =========================================================
           HEADER
        ========================================================= */

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .welcome-text {
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 4px;
        }

        .welcome-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .role {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            color: white;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .right {
            text-align: right;
            font-size: 12px;
            line-height: 1.5;
            color: #64748B;
        }

        .right b {
            color: #1E293B;
        }

        .header-divider {
            width: 100%;
            height: 1px;
            background: #E5E7EB;
            margin: 16px 0 24px;
        }

        /* =================================
   FORECAST ACCURACY HEADER
================================= */

.page-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin: 28px 0 22px;
}

.heading-left h1 {
    margin: 0;
    font-size: 27px;
    line-height: 1.2;
    font-weight: 750;
    color: #111827;
    letter-spacing: -0.02em;
}

.heading-left p {
    margin: 7px 0 0;
    font-size: 13px;
    color: #6b7280;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #374151;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transition: .18s ease;
}

.back-button:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}


/* =================================
   SUMMARY
================================= */

.summary-label {
    margin-bottom: 10px;
    font-size: 12px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .05em;
}

.accuracy-kpis {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 13px;
}

.accuracy-kpi {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px 17px;
    min-height: 108px;
}

.kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
}

.kpi-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 11px;
    font-weight: 700;
}

.kpi-value {
    margin-top: 10px;
    font-size: 24px;
    line-height: 1;
    font-weight: 750;
    color: #111827;
}

.kpi-description {
    margin-top: 7px;
    font-size: 11px;
    color: #9ca3af;
}


/* =================================
   BACKTEST NOTE
================================= */

.backtest-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 13px 0 24px;
    padding: 9px 11px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #fafafa;
    color: #6b7280;
    font-size: 11px;
    line-height: 1.5;
}

.backtest-note strong {
    color: #374151;
}

.backtest-icon {
    flex-shrink: 0;
    color: #6b7280;
}


/* =================================
   NO RESULTS
================================= */

.no-results-note {
    margin: 14px 0 24px;
    padding: 12px 14px;
    border: 1px solid #fde68a;
    border-radius: 8px;
    background: #fffbeb;
    color: #92400e;
    font-size: 12px;
    line-height: 1.5;
}

.no-results-note strong {
    display: block;
    margin-bottom: 2px;
    color: #78350f;
}


/* =================================
   RESPONSIVE
================================= */

@media (max-width: 1000px) {

    .accuracy-kpis {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 700px) {

    .page-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .accuracy-kpis {
        grid-template-columns: 1fr;
    }

}

.kpi-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
}

/* Evaluations */
.accuracy-kpi:nth-child(1) .kpi-icon {
    background: #e0f2fe;
    color: #0284c7;
}

/* MAE */
.accuracy-kpi:nth-child(2) .kpi-icon {
    background: #fef3c7;
    color: #d97706;
}

/* WAPE */
.accuracy-kpi:nth-child(3) .kpi-icon {
    background: #f3e8ff;
    color: #9333ea;
}

/* Weighted Accuracy */
.accuracy-kpi:nth-child(4) .kpi-icon {
    background: #dcfce7;
    color: #16a34a;
}

        /* =========================================================
           ACCURACY STATUS
        ========================================================= */

        .accuracy-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 15px 18px;
            margin-bottom: 20px;
        }

        .accuracy-summary-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .accuracy-status-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            font-weight: 700;
            font-size: 15px;
        }

        .accuracy-status-icon.excellent,
        .accuracy-status-icon.good {
            background: #DCFCE7;
            color: #15803D;
        }

        .accuracy-status-icon.moderate {
            background: #FEF3C7;
            color: #B45309;
        }

        .accuracy-status-icon.attention {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .accuracy-status-icon.neutral {
            background: #F1F5F9;
            color: #64748B;
        }

        .accuracy-summary-title {
            font-size: 13px;
            font-weight: 700;
            color: #1F2937;
        }

        .accuracy-summary-text {
            margin-top: 2px;
            font-size: 11px;
            color: #64748B;
        }

        .accuracy-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .accuracy-pill.excellent,
        .accuracy-pill.good {
            background: #DCFCE7;
            color: #166534;
        }

        .accuracy-pill.moderate {
            background: #FEF3C7;
            color: #92400E;
        }

        .accuracy-pill.attention {
            background: #FEE2E2;
            color: #991B1B;
        }

        .accuracy-pill.neutral {
            background: #F1F5F9;
            color: #64748B;
        }

        /* =========================================================
           NOTICE
        ========================================================= */

        .notice {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            color: #92400E;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12px;
            line-height: 1.55;
            margin-bottom: 20px;
        }

        .notice-icon {
            font-weight: 700;
            font-size: 14px;
            flex: 0 0 auto;
        }

        /* =========================================================
           SECTION CARD
        ========================================================= */

        .section-card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 16px 18px;
            border-bottom: 1px solid #E5E7EB;
        }

        .section-title-group {
            min-width: 0;
        }

        .section-title {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .section-subtitle {
            margin-top: 3px;
            font-size: 11px;
            color: #64748B;
        }

        .section-badge {
    padding: 4px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* Accuracy by Medicine */
.section-header:nth-child(1) .section-badge {
    background: #E0F2FE;
    color: #0369A1;
}

/* Recent Backtest Observations */
.section-header:nth-child(2) .section-badge {
    background: #F3E8FF;
    color: #7E22CE;
}
        /* =========================================================
           TABLE
        ========================================================= */

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        th,
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #EEF2F7;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #F8FAFC;
            color: #64748B;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        tbody tr {
            transition: background .12s ease;
        }

        tbody tr:hover {
            background: #F8FAFC;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        td {
            color: #374151;
        }

        .medicine-name {
            color: #111827;
            font-weight: 700;
        }

        .metric-value {
            font-variant-numeric: tabular-nums;
        }

        /* =========================================================
           STATUS / METRIC PILLS
        ========================================================= */

        .metric-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            min-width: 65px;
        }

        .metric-pill.good {
            background: #ECFDF5;
            color: #047857;
        }

        .metric-pill.warning {
            background: #FFFBEB;
            color: #B45309;
        }

        .metric-pill.danger {
            background: #FEF2F2;
            color: #B91C1C;
        }

        .metric-pill.neutral {
            background: #F1F5F9;
            color: #64748B;
        }

        .bias-positive {
            color: #B45309;
            font-weight: 700;
        }

        .bias-negative {
            color: #2563EB;
            font-weight: 700;
        }

        .bias-neutral {
            color: #64748B;
            font-weight: 600;
        }

        /* =========================================================
           EMPTY STATE
        ========================================================= */

        .empty-state {
            padding: 42px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 42px;
            height: 42px;
            margin: 0 auto 10px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F1F5F9;
            color: #64748B;
            font-size: 17px;
            font-weight: 700;
        }

        .empty-title {
            font-size: 13px;
            font-weight: 700;
            color: #374151;
        }

        .empty-text {
            margin-top: 4px;
            font-size: 11px;
            color: #94A3B8;
        }

        /* =========================================================
           METRIC GUIDE
        ========================================================= */

        .metric-guide {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .guide-item {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 13px 14px;
        }

        .guide-label {
            font-size: 11px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .guide-text {
            font-size: 10.5px;
            line-height: 1.45;
            color: #64748B;
        }

        /* =========================================================
           FOOTER NOTE
        ========================================================= */

        .footer-note {
            padding: 4px 2px 10px;
            font-size: 11px;
            line-height: 1.55;
            color: #64748B;
        }

        .footer-note b {
            color: #475569;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1150px) {

            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .metric-guide {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 850px) {

            .main {
                margin-left: 0;
                width: 100%;
                padding: 18px;
            }

            .header {
                flex-direction: column;
            }

            .right {
                text-align: left;
            }

            .page-heading {
                flex-direction: column;
            }

            .accuracy-summary {
                align-items: flex-start;
                flex-direction: column;
            }

        }

        @media (max-width: 600px) {

            .kpi-grid {
                grid-template-columns: 1fr;
            }

            .metric-guide {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 21px;
            }

            .page-description {
                font-size: 12px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <x-sidebar />

    <main class="main">

        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="header">

            <div>

                <div class="welcome-text">
                    Welcome,
                </div>

                <div class="welcome-name">

                    {{ $userName }}

                    <span
                        class="role"
                        style="
                            background:
                            {{ $role === 'admin'
                                ? '#9333EA'
                                : '#10B981' }};
                        "
                    >
                        {{ $role }}
                    </span>

                </div>

            </div>

            <div class="right">

                <b>Velasquez Health Center</b>

                <br>

                {{ date('F d, Y | h:i A') }}

            </div>

        </div>

        <div class="header-divider"></div>


{{-- =========================
     FORECAST ACCURACY HEADER
========================== --}}
<div class="page-heading">

    <div class="heading-left">
        <h1>Forecast Accuracy Tracking</h1>

        <p>
            Historical evaluation of the medicine inventory forecasting model.
        </p>
    </div>

    <a href="{{ $forecastRoute }}" class="back-button">
        <span>←</span>
        Back to Forecast
    </a>

</div>


{{-- =========================
     PERFORMANCE SUMMARY
========================== --}}
<div class="summary-label">
    Performance Summary
</div>

<div class="accuracy-kpis">

    {{-- Forecast Evaluations --}}
    <div class="accuracy-kpi">
        <div class="kpi-top">
            <span>Forecast Evaluations</span>
            <span class="kpi-icon">N</span>
        </div>

        <div class="kpi-value">
            {{ $overallMetrics['n'] }}
        </div>

        <div class="kpi-description">
            Completed historical forecast windows
        </div>
    </div>


    {{-- MAE --}}
    <div class="accuracy-kpi">
        <div class="kpi-top">
            <span>MAE</span>
            <span class="kpi-icon">±</span>
        </div>

        <div class="kpi-value">
            @if($overallMetrics['mae'] !== null)
                {{ number_format($overallMetrics['mae'], 2) }}
            @else
                N/A
            @endif
        </div>

        <div class="kpi-description">
            Average absolute error in units
        </div>
    </div>


    {{-- WAPE --}}
    <div class="accuracy-kpi">
        <div class="kpi-top">
            <span>WAPE</span>
            <span class="kpi-icon">%</span>
        </div>

        <div class="kpi-value">
            @if($overallMetrics['wape'] !== null)
                {{ number_format($overallMetrics['wape'], 2) }}%
            @else
                N/A
            @endif
        </div>

        <div class="kpi-description">
            Weighted absolute percentage error
        </div>
    </div>


    {{-- Weighted Accuracy --}}
    <div class="accuracy-kpi">
        <div class="kpi-top">
            <span>Weighted Accuracy</span>
            <span class="kpi-icon">✓</span>
        </div>

        <div class="kpi-value">
            @if($overallMetrics['accuracy'] !== null)
                {{ number_format($overallMetrics['accuracy'], 2) }}%
            @else
                N/A
            @endif
        </div>

        <div class="kpi-description">
            100% minus WAPE
        </div>
    </div>

</div>


{{-- =========================
     SIMPLE BACKTEST NOTE
========================== --}}
@if(($overallMetrics['n'] ?? 0) > 0)

    <div class="backtest-note">
        <span class="backtest-icon">ⓘ</span>

        <span>
            These are <strong>historical backtest results</strong>.
            They show how the forecasting method performed on completed
            historical periods and are not future predictions.
        </span>
    </div>

@endif


{{-- =========================
     NO RESULTS NOTICE
========================== --}}
@if(($overallMetrics['n'] ?? 0) === 0)

    <div class="no-results-note">

        <strong>No completed forecast evaluations yet.</strong>

        The system needs enough historical dispensing data to complete
        the 30-day backtest windows. Results will appear once sufficient
        historical records are available.

    </div>

@endif


        <!-- =====================================================
             ACCURACY BY MEDICINE
        ====================================================== -->

        <div class="section-card">

            <div class="section-header">

                <div class="section-title-group">

                    <h2 class="section-title">
                        Accuracy by Medicine
                    </h2>

                    <div class="section-subtitle">
                        Historical forecasting performance for each medicine
                    </div>

                </div>

                <span class="section-badge">
                    {{ count($medicineResults) }} medicines
                </span>

            </div>


            @if (count($medicineResults) === 0)

                <div class="empty-state">

                    <div class="empty-icon">
                        —
                    </div>

                    <div class="empty-title">
                        No medicine records available
                    </div>

                    <div class="empty-text">
                        Completed historical evaluations will appear here.
                    </div>

                </div>

            @else

                <div class="table-wrap">

                    <table>

                        <thead>

                        <tr>

                            <th>Medicine</th>

                            <th>Evaluations</th>

                            <th>MAE</th>

                            <th>WAPE</th>

                            <th>Weighted Accuracy</th>

                            <th>Bias</th>

                            <th>Total Actual</th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach ($medicineResults as $result)

                            @php

                                $medicineAccuracy =
                                    $result['metrics']['accuracy'] ?? null;

                                $bias =
                                    $result['metrics']['bias'] ?? null;

                                if ($medicineAccuracy === null) {
                                    $medicineAccuracyClass = 'neutral';
                                } elseif ($medicineAccuracy >= 80) {
                                    $medicineAccuracyClass = 'good';
                                } elseif ($medicineAccuracy >= 70) {
                                    $medicineAccuracyClass = 'warning';
                                } else {
                                    $medicineAccuracyClass = 'danger';
                                }

                            @endphp

                            <tr>

                                <td>
                                    <span class="medicine-name">
                                        {{ $result['medicine']->name }}
                                    </span>
                                </td>

                                <td class="metric-value">
                                    {{ $result['metrics']['n'] }}
                                </td>

                                <td class="metric-value">

                                    {{
                                        $result['metrics']['mae'] !== null
                                            ? number_format(
                                                $result['metrics']['mae'],
                                                2
                                            )
                                            : 'N/A'
                                    }}

                                </td>

                                <td class="metric-value">

                                    {{
                                        $result['metrics']['wape'] !== null
                                            ? number_format(
                                                $result['metrics']['wape'],
                                                2
                                            ) . '%'
                                            : 'N/A'
                                    }}

                                </td>

                                <td>

                                    <span class="metric-pill {{ $medicineAccuracyClass }}">

                                        {{
                                            $medicineAccuracy !== null
                                                ? number_format(
                                                    $medicineAccuracy,
                                                    2
                                                ) . '%'
                                                : 'N/A'
                                        }}

                                    </span>

                                </td>

                                <td>

                                    @if ($bias === null)

                                        <span class="bias-neutral">
                                            N/A
                                        </span>

                                    @elseif ($bias > 0)

                                        <span class="bias-positive">
                                            +{{ number_format($bias, 2) }}
                                        </span>

                                    @elseif ($bias < 0)

                                        <span class="bias-negative">
                                            {{ number_format($bias, 2) }}
                                        </span>

                                    @else

                                        <span class="bias-neutral">
                                            0.00
                                        </span>

                                    @endif

                                </td>

                                <td class="metric-value">

                                    {{
                                        number_format(
                                            $result['metrics']['total_actual']
                                        )
                                    }}

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>


        <!-- =====================================================
             RECENT BACKTEST OBSERVATIONS
        ====================================================== -->

        <div class="section-card">

            <div class="section-header">

                <div class="section-title-group">

                    <h2 class="section-title">
                        Recent Backtest Observations
                    </h2>

                    <div class="section-subtitle">
                        Individual historical forecast-versus-actual results
                    </div>

                </div>

                <span class="section-badge">
                    Recent Results
                </span>

            </div>


            @if (count($recentObservations) === 0)

                <div class="empty-state">

                    <div class="empty-icon">
                        —
                    </div>

                    <div class="empty-title">
                        No completed observations yet
                    </div>

                    <div class="empty-text">
                        Historical backtest observations will appear here.
                    </div>

                </div>

            @else

                <div class="table-wrap">

                    <table>

                        <thead>

                        <tr>

                            <th>Medicine</th>

                            <th>Cutoff</th>

                            <th>Target Period</th>

                            <th>Forecast</th>

                            <th>Actual</th>

                            <th>Error</th>

                            <th>Absolute Error</th>

                            <th>Method</th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach ($recentObservations as $observation)

                            @php

                                $error = $observation['error'];

                                if ($error > 0) {
                                    $errorClass = 'bias-positive';
                                    $errorText = '+' . number_format($error);
                                } elseif ($error < 0) {
                                    $errorClass = 'bias-negative';
                                    $errorText = number_format($error);
                                } else {
                                    $errorClass = 'bias-neutral';
                                    $errorText = '0';
                                }

                            @endphp

                            <tr>

                                <td>
                                    <span class="medicine-name">
                                        {{ $observation['medicine_name'] }}
                                    </span>
                                </td>

                                <td>

                                    {{
                                        date(
                                            'M d, Y',
                                            strtotime($observation['as_of'])
                                        )
                                    }}

                                </td>

                                <td>

                                    {{
                                        date(
                                            'M d',
                                            strtotime(
                                                $observation['target_start']
                                            )
                                        )
                                    }}

                                    –

                                    {{
                                        date(
                                            'M d, Y',
                                            strtotime(
                                                $observation['target_end']
                                            )
                                        )
                                    }}

                                </td>

                                <td class="metric-value">

                                    {{
                                        number_format(
                                            $observation['forecast']
                                        )
                                    }}

                                </td>

                                <td class="metric-value">

                                    {{
                                        number_format(
                                            $observation['actual']
                                        )
                                    }}

                                </td>

                                <td>

                                    <span class="{{ $errorClass }}">
                                        {{ $errorText }}
                                    </span>

                                </td>

                                <td class="metric-value">

                                    {{
                                        number_format(
                                            $observation['absolute_error']
                                        )
                                    }}

                                </td>

                                <td>

                                    <span class="metric-pill neutral">
                                        {{ $observation['forecast_method'] }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>


        <!-- =====================================================
             THESIS NOTE
        ====================================================== -->

        <div class="footer-note">

            For thesis reporting, use the aggregate
            <b>MAE</b> and <b>WAPE</b> together with the number
            of completed evaluation windows.

            These results describe historical forecasting performance
            and should not be presented as a guarantee of future accuracy.

        </div>

    </main>

</div>

</body>
</html>
{{-- resources/views/admin/forecast-history.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forecast History - Velasquez Health Center</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .main-content {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 28px 40px 40px;
}

        /* =========================
           HEADER
        ========================= */

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 26px;
        }

        .welcome h2 {
            margin: 0;
            font-size: 20px;
            color: #0f172a;
        }

        .welcome p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #64748b;
        }

        .facility {
            text-align: right;
        }

        .facility-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .facility-date {
            margin-top: 4px;
            font-size: 11px;
            color: #64748b;
        }

        /* =========================
           TITLE
        ========================= */

        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 20px;
        }

        .title-area h1 {
            margin: 0;
            font-size: 26px;
            color: #0f172a;
        }

        .title-area p {
            margin: 6px 0 0;
            font-size: 12px;
            color: #64748b;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            padding: 0 14px;
            border-radius: 7px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
        }

        .btn:hover {
            background: #f8fafc;
        }

        .btn-primary {
            background: #2563EB;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #1D4ED8;
        }

        /* =========================
           METHOD
        ========================= */

        .method-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            padding: 14px 16px;
            margin-bottom: 18px;
        }

        .method-title {
            font-size: 11px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 5px;
        }

        .method-description {
            font-size: 11px;
            line-height: 1.5;
            color: #64748b;
        }

        .method-name {
            font-weight: 700;
            color: #475569;
        }

        /* =========================
           KPI CARDS
        ========================= */

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }

        .kpi-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            padding: 15px 16px;
        }

        .kpi-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .4px;
            font-weight: 700;
            color: #94a3b8;
        }

        .kpi-value {
            margin-top: 7px;
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
        }

        .kpi-description {
            margin-top: 3px;
            font-size: 10px;
            color: #94a3b8;
        }

        /* =========================
           HISTORY CARD
        ========================= */

        .history-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 17px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .history-header h2 {
            margin: 0;
            font-size: 15px;
            color: #0f172a;
        }

        .history-header p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #94a3b8;
        }

        .record-count {
            padding: 5px 9px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* =========================
           TABLE
        ========================= */

        .table-container {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead {
            background: #f8fafc;
        }

        th {
            padding: 12px 14px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .35px;
            font-weight: 700;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        td {
            padding: 14px;
            font-size: 11px;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fafafa;
        }

        /* Column widths */

        th:nth-child(1),
        td:nth-child(1) {
            width: 21%;
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 13%;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 20%;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 11%;
            text-align: center;
        }

        th:nth-child(5),
        td:nth-child(5) {
            width: 11%;
            text-align: center;
        }

        th:nth-child(6),
        td:nth-child(6) {
            width: 10%;
            text-align: center;
        }

        th:nth-child(7),
        td:nth-child(7) {
            width: 14%;
            text-align: center;
        }

        .medicine-name {
            font-weight: 700;
            color: #0f172a;
        }

        .date-text {
            color: #475569;
            white-space: nowrap;
        }

        .target-text {
            color: #475569;
            white-space: nowrap;
        }

        .number {
            font-weight: 700;
            color: #334155;
        }

        .unit {
            margin-left: 2px;
            font-size: 9px;
            color: #94a3b8;
            font-weight: 400;
        }

        /* =========================
           STATUS
        ========================= */

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 5px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-evaluated {
            background: #f0fdf4;
            color: #15803d;
        }

        .status-pending {
            background: #fffbeb;
            color: #b45309;
        }

        .error-value {
            font-weight: 700;
        }

        .error-over {
            color: #b45309;
        }

        .error-under {
            color: #15803d;
        }

        .error-none {
            color: #64748b;
        }

        /* =========================
           EMPTY
        ========================= */

        .empty-state {
            padding: 50px 20px;
            text-align: center;
        }

        .empty-state h3 {
            margin: 0;
            font-size: 14px;
            color: #334155;
        }

        .empty-state p {
            margin: 6px 0 0;
            font-size: 11px;
            color: #94a3b8;
        }

        /* =========================
           PAGINATION
        ========================= */

        .pagination-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            border-top: 1px solid #e2e8f0;
        }

        .pagination-info {
            font-size: 10px;
            color: #64748b;
        }

        .pagination-info strong {
            color: #334155;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination a,
        .pagination span {
            min-width: 29px;
            height: 29px;
            padding: 0 8px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #ffffff;
            color: #475569;
            text-decoration: none;
            font-size: 10px;
            font-weight: 700;
        }

        .pagination a:hover {
            background: #f8fafc;
        }

        .pagination .active {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }

        .pagination .disabled {
            background: #f8fafc;
            color: #cbd5e1;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {

            .main-content {
        width: 100%;
        padding: 24px;
    }

            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            table {
                font-size: 10px;
            }

            th,
            td {
                padding: 11px 9px;
            }
        }

        @media (max-width: 800px) {

            .top-header,
            .title-row,
            .history-header,
            .pagination-area {
                flex-direction: column;
                align-items: flex-start;
            }

            .facility {
                text-align: left;
            }

            .actions {
                width: 100%;
            }

            .btn {
                flex: 1;
            }

            /*
             * On smaller screens, transform the table
             * into readable rows instead of allowing
             * horizontal scrolling.
             */

            .table-container {
                padding: 10px;
            }

            table,
            thead,
            tbody,
            tr,
            th,
            td {
                display: block;
                width: 100% !important;
            }

            thead {
                display: none;
            }

            tbody tr {
                padding: 14px;
                margin-bottom: 10px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #ffffff;
            }

            tbody tr:last-child {
                margin-bottom: 0;
            }

            td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 15px;
                padding: 7px 0;
                border: none;
                text-align: right !important;
            }

            td::before {
                content: attr(data-label);
                font-size: 9px;
                font-weight: 700;
                text-transform: uppercase;
                color: #94a3b8;
                text-align: left;
            }

            td:first-child {
                padding-top: 0;
                padding-bottom: 10px;
                border-bottom: 1px solid #f1f5f9;
            }

            td:first-child::before {
                display: none;
            }

            td:last-child {
                padding-bottom: 0;
                margin-top: 5px;
                padding-top: 10px;
                border-top: 1px solid #f1f5f9;
            }

            .medicine-name {
                font-size: 13px;
                text-align: left;
            }

            .pagination-area {
                padding: 14px;
            }
        }

        @media (max-width: 520px) {

            .main-content {
                padding: 16px;
            }

            .kpi-grid {
                grid-template-columns: 1fr;
            }

            .pagination {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

<main class="main-content">


    {{-- =========================
         TITLE
    ========================== --}}

    <div class="title-row">

        <div class="title-area">
            <h1>Forecast History</h1>

            <p>
                View saved forecasts and compare predicted demand with actual demand.
            </p>
        </div>

        <div class="actions">

            <a href="{{ route('admin.forecast.accuracy') }}"
               class="btn">
                Forecast Accuracy
            </a>

            <a href="{{ route('admin.forecast') }}"
               class="btn btn-primary">
                ← Back to Forecast
            </a>

        </div>

    </div>


    {{-- =========================
         METHOD
    ========================== --}}

    <div class="method-card">

        <div class="method-title">
            Forecast Method
        </div>

        <div class="method-description">
            Forecasts are saved and later compared with actual medicine demand
            after the target period.
            Current method:
            <span class="method-name">
                Trend-Adjusted Moving Average
            </span>
        </div>

    </div>


    {{-- =========================
         KPI CARDS
    ========================== --}}

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">
                Saved Forecasts
            </div>

            <div class="kpi-value">
                {{ $snapshots->total() }}
            </div>

            <div class="kpi-description">
                Total forecast records
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">
                Completed Evaluations
            </div>

            <div class="kpi-value">
                {{ $evaluationCount }}
            </div>

            <div class="kpi-description">
                Forecasts with actual demand
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">
                MAE
            </div>

            <div class="kpi-value">
                {{ $mae !== null ? number_format($mae, 2) : '—' }}
            </div>

            <div class="kpi-description">
                Mean absolute error
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">
                Accuracy
            </div>

            <div class="kpi-value">
                {{ $accuracy !== null ? number_format($accuracy, 1) . '%' : '—' }}
            </div>

            <div class="kpi-description">
                Based on completed evaluations
            </div>
        </div>

    </div>


    {{-- =========================
         HISTORY TABLE
    ========================== --}}

    <section class="history-card">

        <div class="history-header">

            <div>
                <h2>Saved Forecasts</h2>

                <p>
                    Each row represents one saved medicine forecast.
                </p>
            </div>

            <div class="record-count">
                {{ $snapshots->total() }} records
            </div>

        </div>


        <div class="table-container">

            @if ($snapshots->count() > 0)

                <table>

                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Forecast Date</th>
                            <th>Target Period</th>
                            <th>Forecast</th>
                            <th>Actual</th>
                            <th>Error</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach ($snapshots as $snapshot)

                        @php

                            $medicineName =
                                $snapshot->medicine->name
                                ?? $snapshot->medicine->medicine_name
                                ?? 'Unknown Medicine';

                            $forecast =
                                $snapshot->forecast_quantity;

                            $actual =
                                $snapshot->actual_demand;

                            $error =
                                $snapshot->absolute_error;

                            $forecastDate =
                                $snapshot->forecast_date
                                ? \Carbon\Carbon::parse(
                                    $snapshot->forecast_date
                                )->format('M d, Y')
                                : '—';

                            $targetStart =
                                $snapshot->target_start
                                ? \Carbon\Carbon::parse(
                                    $snapshot->target_start
                                )->format('M d')
                                : '—';

                            $targetEnd =
                                $snapshot->target_end
                                ? \Carbon\Carbon::parse(
                                    $snapshot->target_end
                                )->format('M d, Y')
                                : '—';

                            $status =
                                $actual !== null
                                ? 'Evaluated'
                                : 'Pending';

                            $statusClass =
                                $actual !== null
                                ? 'status-evaluated'
                                : 'status-pending';

                            /*
                             * Positive error means the forecast
                             * was higher than actual demand.
                             */
                            if (
                                $actual !== null &&
                                $forecast !== null &&
                                $forecast > $actual
                            ) {
                                $errorClass = 'error-over';
                            } elseif (
                                $actual !== null &&
                                $forecast !== null &&
                                $forecast < $actual
                            ) {
                                $errorClass = 'error-under';
                            } else {
                                $errorClass = 'error-none';
                            }

                        @endphp

                        <tr>

                            {{-- MEDICINE --}}

                            <td data-label="Medicine">
                                <span class="medicine-name">
                                    {{ $medicineName }}
                                </span>
                            </td>


                            {{-- FORECAST DATE --}}

                            <td data-label="Forecast Date">
                                <span class="date-text">
                                    {{ $forecastDate }}
                                </span>
                            </td>


                            {{-- TARGET PERIOD --}}

                            <td data-label="Target Period">
                                <span class="target-text">
                                    {{ $targetStart }} – {{ $targetEnd }}
                                </span>
                            </td>


                            {{-- FORECAST --}}

                            <td data-label="Forecast">

                                @if ($forecast !== null)

                                    <span class="number">
                                        {{ number_format($forecast) }}
                                    </span>

                                    <span class="unit">
                                        units
                                    </span>

                                @else
                                    —
                                @endif

                            </td>


                            {{-- ACTUAL --}}

                            <td data-label="Actual">

                                @if ($actual !== null)

                                    <span class="number">
                                        {{ number_format($actual) }}
                                    </span>

                                    <span class="unit">
                                        units
                                    </span>

                                @else
                                    —
                                @endif

                            </td>


                            {{-- ERROR --}}

                            <td data-label="Error">

                                @if ($error !== null)

                                    <span class="error-value {{ $errorClass }}">
                                        {{ number_format($error) }}
                                    </span>

                                    <span class="unit">
                                        units
                                    </span>

                                @else
                                    —
                                @endif

                            </td>


                            {{-- STATUS --}}

                            <td data-label="Status">

                                <span class="status {{ $statusClass }}">

                                    <span class="status-dot"></span>

                                    {{ $status }}

                                </span>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <h3>
                        No forecast history yet
                    </h3>

                    <p>
                        Saved forecast records will appear here after forecasts are generated.
                    </p>

                </div>

            @endif

        </div>


        {{-- =========================
             PAGINATION
        ========================== --}}

        @if ($snapshots->total() > 0)

            <div class="pagination-area">

                <div class="pagination-info">

                    @if ($snapshots->firstItem())

                        Showing
                        <strong>{{ $snapshots->firstItem() }}</strong>
                        –
                        <strong>{{ $snapshots->lastItem() }}</strong>
                        of
                        <strong>{{ $snapshots->total() }}</strong>
                        records

                    @endif

                </div>


                @if ($snapshots->hasPages())

                    <div class="pagination">

                        {{-- PREVIOUS --}}

                        @if ($snapshots->onFirstPage())

                            <span class="disabled">
                                ‹
                            </span>

                        @else

                            <a href="{{ $snapshots->previousPageUrl() }}">
                                ‹
                            </a>

                        @endif


                        {{-- PAGE NUMBERS --}}

                        @foreach (
                            $snapshots->getUrlRange(
                                max(1, $snapshots->currentPage() - 2),
                                min(
                                    $snapshots->lastPage(),
                                    $snapshots->currentPage() + 2
                                )
                            ) as $page => $url
                        )

                            @if ($page == $snapshots->currentPage())

                                <span class="active">
                                    {{ $page }}
                                </span>

                            @else

                                <a href="{{ $url }}">
                                    {{ $page }}
                                </a>

                            @endif

                        @endforeach


                        {{-- NEXT --}}

                        @if ($snapshots->hasMorePages())

                            <a href="{{ $snapshots->nextPageUrl() }}">
                                ›
                            </a>

                        @else

                            <span class="disabled">
                                ›
                            </span>

                        @endif

                    </div>

                @endif

            </div>

        @endif

    </section>

</main>

</body>
</html>
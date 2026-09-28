@php
    date_default_timezone_set('Asia/Manila');

    $forecastRoute =
        $role === 'nurse'
            ? route('nurse.forecast')
            : route('admin.forecast');

    $accuracyRoute =
        $role === 'nurse'
            ? route('nurse.forecast.accuracy')
            : route('admin.forecast.accuracy');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Forecast History - Velasquez Health Center
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F9FAFB;
            color: #1F2937;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 24px;
        }

        .header {
            display: flex;
            justify-content: space-between;
        }

        .welcome-text {
            font-size: 14px;
            color: #6B7280;
        }

        .welcome-name {
            font-size: 18px;
            font-weight: 700;
        }

        .right {
            text-align: right;
            font-size: 12px;
        }

        .header-divider {
            height: 1px;
            background: #E5E7EB;
            margin: 16px 0;
        }

        .title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
        }

        .buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            text-decoration: none;
            padding: 8px 13px;
            border-radius: 7px;
            border: 1px solid #D1D5DB;
            background: #fff;
            color: #374151;
            font-size: 12px;
            font-weight: 600;
        }

        .method {
            margin: 15px 0;
            padding: 12px 15px;
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 8px;
            color: #1E3A8A;
            font-size: 12px;
            line-height: 1.5;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin: 18px 0;
        }

        .card {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 15px;
        }

        .label {
            font-size: 11px;
            color: #6B7280;
            text-transform: uppercase;
            font-weight: 700;
        }

        .value {
            font-size: 23px;
            font-weight: 700;
            margin-top: 5px;
        }

        .table-card {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-title {
            padding: 15px 18px;
            font-size: 16px;
            font-weight: 700;
            border-bottom: 1px solid #E5E7EB;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            padding: 11px 13px;
            border-bottom: 1px solid #EEF2F7;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #F9FAFB;
            color: #6B7280;
            font-size: 10px;
            text-transform: uppercase;
        }

        .pending {
            color: #92400E;
            font-weight: 700;
        }

        .evaluated {
            color: #047857;
            font-weight: 700;
        }

        .decision-accept {
            color: #166534;
            font-weight: 700;
        }

        .decision-edit {
            color: #1D4ED8;
            font-weight: 700;
        }

        .decision-dismiss {
            color: #991B1B;
            font-weight: 700;
        }

        .note {
            margin-top: 15px;
            font-size: 11px;
            color: #6B7280;
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .main {
                margin-left: 0;
                width: 100%;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

</head>

<body>

    <x-sidebar />

    <div class="main">

        <div class="header">

            <div>

                <div class="welcome-text">
                    Welcome back,
                </div>

                <div class="welcome-name">
                    {{ $userName }}
                </div>

            </div>

            <div class="right">

                <b>Velasquez Health Center</b>

                <br>

                {{ date('F d, Y | h:i A') }}

            </div>

        </div>

        <div class="header-divider"></div>

        <div class="title-row">

            <h1>
                Forecast History
            </h1>

            <div class="buttons">

                <a
                    class="btn"
                    href="{{ $accuracyRoute }}"
                >
                    Forecast Accuracy
                </a>

                <a
                    class="btn"
                    href="{{ $forecastRoute }}"
                >
                    Back to Forecast
                </a>

            </div>

        </div>

        <div class="method">

            <b>What this page does:</b>

            The system saves the forecast generated for each
            medicine each day. Once the 30-day forecast period
            has completely ended, the system compares the saved
            forecast with the actual medicine demand.

        </div>

        <div class="cards">

            <div class="card">

                <div class="label">
                    Saved Forecasts
                </div>

                <div class="value">
                    {{ $snapshots->count() }}
                </div>

            </div>

            <div class="card">

                <div class="label">
                    Completed Evaluations
                </div>

                <div class="value">
                    {{ $evaluationCount }}
                </div>

            </div>

            <div class="card">

                <div class="label">
                    MAE
                </div>

                <div class="value">

                    {{
                        $mae !== null
                            ? number_format($mae, 2)
                            : 'N/A'
                    }}

                </div>

            </div>

            <div class="card">

                <div class="label">
                    Accuracy
                </div>

                <div class="value">

                    {{
                        $accuracy !== null
                            ? number_format($accuracy, 2) . '%'
                            : 'N/A'
                    }}

                </div>

            </div>

        </div>

        <div class="table-card">

            <div class="table-title">
                Saved Forecasts
            </div>

            <div class="table-wrap">

                <table>

                    <thead>

                    <tr>

                        <th>Medicine</th>
                        <th>Forecast Date</th>
                        <th>Target Period</th>
                        <th>Forecast</th>
                        <th>Actual</th>
                        <th>Error</th>
                        <th>Method</th>
                        <th>Decision</th>
                        <th>Reviewed Qty</th>
                        <th>Status</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse ($snapshots as $snapshot)

                        <tr>

                            <td>
                                <strong>
                                    {{ $snapshot->medicine->name }}
                                </strong>
                            </td>

                            <td>
                                {{
                                    $snapshot->forecast_date
                                        ->format('M d, Y')
                                }}
                            </td>

                            <td>

                                {{
                                    $snapshot->target_start
                                        ->format('M d, Y')
                                }}

                                –

                                {{
                                    $snapshot->target_end
                                        ->format('M d, Y')
                                }}

                            </td>

                            <td>
                                {{
                                    number_format(
                                        $snapshot->forecasted_demand
                                    )
                                }}
                            </td>

                            <td>

                                @if ($snapshot->actual_demand !== null)

                                    {{
                                        number_format(
                                            $snapshot->actual_demand
                                        )
                                    }}

                                @else

                                    —

                                @endif

                            </td>

                            <td>

                                @if ($snapshot->error !== null)

                                    {{
                                        $snapshot->error > 0
                                            ? '+'
                                            : ''
                                    }}{{ $snapshot->error }}

                                @else

                                    —

                                @endif

                            </td>

                            <td>
                                {{ $snapshot->forecast_method }}
                            </td>

                            <td>

                                @if ($snapshot->review_decision === 'accept')

                                    <span class="decision-accept">
                                        ACCEPTED
                                    </span>

                                @elseif ($snapshot->review_decision === 'edit')

                                    <span class="decision-edit">
                                        EDITED
                                    </span>

                                @elseif ($snapshot->review_decision === 'dismiss')

                                    <span class="decision-dismiss">
                                        DISMISSED
                                    </span>

                                @else

                                    —

                                @endif

                            </td>

                            <td>

                                {{
                                    $snapshot->reviewed_quantity !== null
                                        ? number_format(
                                            $snapshot->reviewed_quantity
                                        )
                                        : '—'
                                }}

                            </td>

                            <td>

                                @if ($snapshot->actual_demand !== null)

                                    <span class="evaluated">
                                        Evaluated
                                    </span>

                                @else

                                    <span class="pending">
                                        Pending
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="10"
                                style="
                                    text-align:center;
                                    padding:30px;
                                    color:#6B7280;
                                "
                            >
                                No saved forecasts yet.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="note">

            <b>Pending</b> means the 30-day target period has not
            completely ended yet.

            <br>

            <b>Evaluated</b> means the system has already compared
            the saved forecast against actual medicine demand.

            <br>

            The saved forecast is the original daily forecast and
            is not overwritten when the Forecast page is refreshed.

        </div>

    </div>

</body>

</html>
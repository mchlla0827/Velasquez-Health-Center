@php
    date_default_timezone_set('Asia/Manila');

    $forecastRoute =
        $role === 'nurse'
            ? route('nurse.forecast')
            : route('admin.forecast');
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

    <title>
        Forecast Accuracy - Velasquez Health Center
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

        .container {
            display: flex;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 24px;
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
            text-transform: uppercase;
        }

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

        .title-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
            margin-bottom: 6px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
            color: #1E293B;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #D1D5DB;
            background: #fff;
            color: #374151;
        }

        .method-strip {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 8px;
            padding: 12px 16px;
            margin: 14px 0 20px;
            font-size: 12.5px;
            color: #1E3A8A;
            line-height: 1.5;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 16px 18px;
        }

        .label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .05em;
            color: #6B7280;
        }

        .value {
            font-size: 24px;
            font-weight: 700;
            margin-top: 6px;
            color: #111827;
        }

        .sub {
            font-size: 11px;
            color: #6B7280;
            margin-top: 4px;
        }

        .notice {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            color: #92400E;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .table-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
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
            font-size: 13px;
        }

        th,
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #EEF2F7;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #F9FAFB;
            color: #4B5563;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .good {
            color: #047857;
            font-weight: 700;
        }

        .metric-note {
            font-size: 11px;
            color: #6B7280;
            padding: 0 18px 16px;
            line-height: 1.5;
        }

        .empty {
            padding: 26px 18px;
            color: #6B7280;
            font-size: 13px;
        }

        @media (max-width: 1000px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {
            .main {
                margin-left: 0;
                width: 100%;
                padding: 16px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .title-row {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <x-sidebar />

    <div class="main">

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

                <b>
                    Velasquez Health Center
                </b>

                <br>

                {{ date('F d, Y | h:i A') }}

            </div>

        </div>

        <div class="header-divider"></div>

        <div class="title-row">

            <h1>
                Forecast Accuracy Tracking
            </h1>

            <a
                class="btn"
                href="{{ $forecastRoute }}"
            >
                Back to Forecast
            </a>

        </div>

        <div class="method-strip">

            <b>Evaluation method:</b>

            Rolling-origin backtesting using the same
            Trend-Adjusted 3-Month Moving Average
            used on the live Forecast page.

            Each historical cutoff forecasts the
            following 30 days using only records
            available before that cutoff.

        </div>

        <div class="cards">

            <div class="card">

                <div class="label">
                    Forecast Evaluations
                </div>

                <div class="value">
                    {{ $overallMetrics['n'] }}
                </div>

                <div class="sub">
                    Historical 30-day forecast windows
                </div>

            </div>

            <div class="card">

                <div class="label">
                    MAE
                </div>

                <div class="value">

                    {{
                        $overallMetrics['mae'] !== null
                            ? number_format(
                                $overallMetrics['mae'],
                                2
                            )
                            : 'N/A'
                    }}

                </div>

                <div class="sub">
                    Average absolute error in units
                </div>

            </div>

            <div class="card">

                <div class="label">
                    WAPE
                </div>

                <div class="value">

                    {{
                        $overallMetrics['wape'] !== null
                            ? number_format(
                                $overallMetrics['wape'],
                                2
                            ) . '%'
                            : 'N/A'
                    }}

                </div>

                <div class="sub">
                    Weighted absolute percentage error
                </div>

            </div>

            <div class="card">

                <div class="label">
                    Weighted Accuracy
                </div>

                <div class="value">

                    {{
                        $overallMetrics['accuracy'] !== null
                            ? number_format(
                                $overallMetrics['accuracy'],
                                2
                            ) . '%'
                            : 'N/A'
                    }}

                </div>

                <div class="sub">
                    100% minus WAPE
                </div>

            </div>

        </div>

        @if ($overallMetrics['n'] === 0)

            <div class="notice">

                No completed historical forecast
                windows are available yet.

                Accuracy will appear once the database
                contains enough dispensing history to
                create a prior forecast and its following
                30-day actual demand period.

            </div>

        @else

            <div class="notice">

                These are <b>historical backtest results</b>,
                not future predictions.

                They show how the current forecasting
                method performed when applied only to
                information that would have been available
                at each historical cutoff.

                Zero-demand periods are retained.

            </div>

        @endif

        <div class="table-card">

            <div class="table-title">
                Accuracy by Medicine
            </div>

            @if (count($medicineResults) === 0)

                <div class="empty">
                    No medicine records are available
                    for evaluation.
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

                            <tr>

                                <td>
                                    <strong>
                                        {{ $result['medicine']->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $result['metrics']['n'] }}
                                </td>

                                <td>

                                    {{
                                        $result['metrics']['mae']
                                            !== null
                                            ? number_format(
                                                $result['metrics']['mae'],
                                                2
                                            )
                                            : 'N/A'
                                    }}

                                </td>

                                <td>

                                    {{
                                        $result['metrics']['wape']
                                            !== null
                                            ? number_format(
                                                $result['metrics']['wape'],
                                                2
                                            ) . '%'
                                            : 'N/A'
                                    }}

                                </td>

                                <td
                                    class="{{
                                        ($result['metrics']['accuracy'] ?? 0)
                                            >= 80
                                            ? 'good'
                                            : ''
                                    }}"
                                >

                                    {{
                                        $result['metrics']['accuracy']
                                            !== null
                                            ? number_format(
                                                $result['metrics']['accuracy'],
                                                2
                                            ) . '%'
                                            : 'N/A'
                                    }}

                                </td>

                                <td>

                                    {{
                                        $result['metrics']['bias']
                                            !== null
                                            ? number_format(
                                                $result['metrics']['bias'],
                                                2
                                            )
                                            : 'N/A'
                                    }}

                                </td>

                                <td>
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

                <div class="metric-note">

                    <b>MAE</b> shows the average absolute
                    difference between forecast and actual
                    demand.

                    <b>WAPE</b> divides total absolute error
                    by total actual demand.

                    <b>Bias</b> is forecast minus actual
                    on average.

                    Positive values indicate over-forecasting;
                    negative values indicate under-forecasting.

                </div>

            @endif

        </div>

        <div class="table-card">

            <div class="table-title">
                Recent Backtest Observations
            </div>

            @if (count($recentObservations) === 0)

                <div class="empty">
                    No completed observations yet.
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

                            <tr>

                                <td>
                                    <strong>
                                        {{ $observation['medicine_name'] }}
                                    </strong>
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

                                <td>
                                    {{
                                        number_format(
                                            $observation['forecast']
                                        )
                                    }}
                                </td>

                                <td>
                                    {{
                                        number_format(
                                            $observation['actual']
                                        )
                                    }}
                                </td>

                                <td>

                                    {{
                                        $observation['error'] > 0
                                            ? '+'
                                            : ''
                                    }}

                                    {{
                                        number_format(
                                            $observation['error']
                                        )
                                    }}

                                </td>

                                <td>
                                    {{
                                        number_format(
                                            $observation['absolute_error']
                                        )
                                    }}
                                </td>

                                <td>
                                    {{
                                        $observation['forecast_method']
                                    }}
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

        <div class="metric-note">

            For thesis reporting, use the aggregate
            <b>MAE</b> and <b>WAPE</b> together with
            the number of completed evaluation windows.

            These results describe historical forecasting
            performance and should not be presented as
            a guarantee of future accuracy.

        </div>

    </div>

</div>

</body>
</html>
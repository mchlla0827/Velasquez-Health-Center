<?php

namespace App\Services;

use App\Models\Medicine;
use Carbon\Carbon;
use App\Models\ForecastSnapshot;

/**
 * MedicineForecastService
 * ========================
 * Single source of truth for demand forecasting and restock
 * recommendations. This is a TREND-ADJUSTED MOVING-AVERAGE statistical
 * forecast, not a machine-learning model - described accurately in any
 * thesis/documentation as "automated demand forecasting using a
 * 3-month moving average with damped linear trend adjustment", not as
 * AI/ML prediction.
 *
 * METHOD
 *   1. Level  = simple 3-month moving average (MA) of dispensing.
 *      All three months are ALWAYS included, even if one had zero
 *      dispensing - a quiet month is real data, not missing data.
 *   2. Trend  = least-squares slope (units/month) over the last
 *      TREND_WINDOW_MONTHS monthly totals.
 *   3. Forecast = MA + (slope x TREND_LAG_MONTHS x TREND_DAMPING),
 *      adjustment capped at +/-TREND_MAX_ADJUSTMENT_RATIO of the MA,
 *      floored at 0.
 *      A 3-month MA is centred ~2 months behind the month being
 *      forecast, so a plain MA under-forecasts a rising series and
 *      over-forecasts a falling one. The slope x lag term corrects
 *      for that; damping (0.5) stops a short, noisy history from
 *      projecting a straight line forever.
 *
 * WHEN THE TREND IS NOT APPLIED (falls back to the plain MA and says why)
 *   - short_history : fewer than TREND_MIN_MONTHS months of history.
 *   - sparse        : fewer than TREND_MIN_ACTIVE_MONTHS months with
 *                     any dispensing in the window. A slope through a
 *                     series that is mostly zeros just chases spikes.
 *
 * DEMAND = DISPENSED + UNMET
 *   Monthly demand is quantity_dispensed plus unfulfilled_quantity, so
 *   a stock-out month is not mistaken for a low-demand month. To avoid
 *   double counting (a follow-up dispense of the remainder is a new
 *   record), only the LATEST dispensing record of each prescription
 *   contributes its unfulfilled quantity, and cancelled prescriptions
 *   are excluded.
 *
 * RANGE AND CONFIDENCE
 *   Range = forecast +/- RANGE_SD_MULTIPLIER x standard deviation of
 *   recent monthly demand. It is descriptive spread, not a statistical
 *   prediction interval. Confidence (High / Medium / Low) is rule-based
 *   on months of history, months with any demand, and variation
 *   relative to the average; the reasons are returned with it.
 *
 * SEASONAL FORECASTING IS NOT IMPLEMENTED - AND IS FLAGGED, NOT FAKED.
 *   Seasonality needs at least two full annual cycles
 *   (SEASONAL_MIN_HISTORY_MONTHS = 24) with dispensing recorded in at
 *   least SEASONAL_MIN_ACTIVE_MONTHS of those months. Every result
 *   carries a 'seasonal' block saying whether that bar is met and what
 *   is missing, so the UI can state the limitation explicitly.
 *
 * RECOMMENDATION FORMULA (documented, not arbitrary):
 * Target coverage = 1 month of estimated demand (the forecast period
 * itself). Suggested reorder quantity = estimated demand - usable
 * stock, floored at 0. If your methodology calls for an additional
 * safety-stock buffer (e.g. 1.5x coverage), adjust SAFETY_MULTIPLIER
 * below and document the reasoning in your thesis - don't scatter a
 * different multiplier across other files.
 */
class MedicineForecastService
{
    /** Safety-stock multiplier applied to estimated demand when
     *  calculating the suggested reorder quantity. 1.0 = order exactly
     *  enough to cover the forecast period, no extra buffer. */
    const SAFETY_MULTIPLIER = 1.0;

    /**
     * Dynamic Reorder Level = Average Monthly Demand x Lead Time + Safety Stock.
     * These are reasonable barangay-health-center defaults since the
     * system doesn't currently track a real per-supplier lead time.
     * LEAD_TIME_MONTHS = 0.5 (~15 days), SAFETY_STOCK_UNITS = 20.
     */
    const LEAD_TIME_MONTHS = 0.5;
    const SAFETY_STOCK_UNITS = 20;

    /** Number of monthly buckets used to estimate the trend slope. */
    const TREND_WINDOW_MONTHS = 6;
    /** Minimum months of history before a trend is estimated at all. */
    const TREND_MIN_MONTHS = 4;
    /** Minimum months (inside the window) with any dispensing, so the
     *  slope is not fitted through a series that is mostly zeros. */
    const TREND_MIN_ACTIVE_MONTHS = 3;
    /** A 3-month MA lags the forecast month by (3 + 1) / 2 = 2 months. */
    const TREND_LAG_MONTHS = 2;
    /** 0-1. Fraction of the raw trend correction that is applied. */
    const TREND_DAMPING = 0.5;
    /** Trend adjustment may not exceed this fraction of the MA. */
    const TREND_MAX_ADJUSTMENT_RATIO = 0.5;

    /** Range = forecast +/- this many SDs of recent monthly demand. */
    const RANGE_SD_MULTIPLIER = 1.0;
    /** Confidence rules (see class docblock). */
    const CONFIDENCE_HIGH_MIN_MONTHS = 6;
    const CONFIDENCE_HIGH_MIN_ACTIVE = 5;
    const CONFIDENCE_HIGH_MAX_CV = 0.5;
    const CONFIDENCE_MEDIUM_MIN_ACTIVE = 3;
    const CONFIDENCE_MEDIUM_MAX_CV = 1.0;

    /** Seasonal methods need two full annual cycles of history... */
    const SEASONAL_MIN_HISTORY_MONTHS = 24;
    /** ...with dispensing in at least this many distinct months. */
    const SEASONAL_MIN_ACTIVE_MONTHS = 12;

    public function __construct(
        private InventoryStockService $stockService
    ) {
    }

    /**
     * Estimate next-30-day demand: 3-month moving average of DEMAND
     * (dispensed + unmet), plus a damped linear trend adjustment when
     * the history supports one, with a range and confidence level.
     *
     * period1..period3 and months_available keep their original shape
     * (last 3 monthly buckets; months_available capped at 3) but the
     * period values are now demand, not dispensed-only.
     *
     * @return array<string,mixed>
     */
public function estimateMonthlyDemand(Medicine $medicine): array
{
    return $this->estimateMonthlyDemandAt($medicine, Carbon::now());
}

/**
 * Save today's forecast as an immutable daily snapshot.
 *
 * Refreshing the Forecast page on the same day does not overwrite
 * the first forecast generated for that medicine.
 */
public function saveForecastSnapshot(
    Medicine $medicine,
    array $evaluation,
    ?Carbon $forecastDate = null
): ?ForecastSnapshot {
    $forecastDate = ($forecastDate ?? Carbon::today())->copy()->startOfDay();

    // Do not save a forecast when there is not enough history.
    if (($evaluation['data_quality'] ?? null) === 'insufficient') {
        return null;
    }

    $targetStart = $forecastDate->copy()->addDay();
    $targetEnd = $forecastDate->copy()->addDays(30);

    return ForecastSnapshot::firstOrCreate(
        [
            'medicine_id' => $medicine->id,
            'forecast_date' => $forecastDate->toDateString(),
        ],
        [
            'target_start' => $targetStart->toDateString(),
            'target_end' => $targetEnd->toDateString(),

            'forecasted_demand' =>
                (int) ($evaluation['estimated_demand'] ?? 0),

            'suggested_reorder_qty' =>
                (int) ($evaluation['suggested_reorder_qty'] ?? 0),

            'usable_stock' =>
                (int) ($evaluation['usable_stock'] ?? 0),

            'forecast_method' =>
                $evaluation['forecast_method'] ?? null,

            'confidence' =>
                $evaluation['confidence'] ?? null,

            'moving_average' =>
                isset($evaluation['moving_average'])
                    ? (int) $evaluation['moving_average']
                    : null,

            'trend_adjustment' =>
                isset($evaluation['trend_adjustment'])
                    ? (float) $evaluation['trend_adjustment']
                    : null,
        ]
    );
}

public function estimateMonthlyDemandAt(Medicine $medicine, Carbon $asOf): array
{
    $now = $asOf->copy();
    $today = $now->copy()->startOfDay();

    $rows = $medicine->dispensingRecords()
        ->where(
            'dispense_date',
            '>=',
            $today->copy()->subMonthsNoOverflow(
                self::SEASONAL_MIN_HISTORY_MONTHS + 1
            )
        )
        ->where(
            'dispense_date',
            '<',
            $today->copy()->addDay()
        )
        ->get([
            'id',
            'prescription_id',
            'dispense_date',
            'quantity_dispensed',
            'unfulfilled_quantity'
        ]);

    $unmetById = self::resolveUnmetDemand(
        $rows->map(fn ($r) => [
            'id' => (int) $r->id,
            'prescription_id' => $r->prescription_id
                ? (int) $r->prescription_id
                : null,
            'unfulfilled' => (int) $r->unfulfilled_quantity,
        ])->all(),
        $this->cancelledPrescriptionIds($rows)
    );

    $records = $rows->map(function ($r) use ($unmetById) {
        $dispensed = (int) $r->quantity_dispensed;
        $unmet = $unmetById[(int) $r->id] ?? 0;

        return [
            'date' => Carbon::parse($r->dispense_date)->startOfDay(),
            'dispensed' => $dispensed,
            'unmet' => $unmet,
            'qty' => $dispensed + $unmet,
        ];
    });

    $demandBuckets = [];
    $dispensedBuckets = [];
    $unmetBuckets = [];

    for ($i = 0; $i < self::TREND_WINDOW_MONTHS; $i++) {
        $from = $today->copy()->subMonthsNoOverflow($i + 1);
        $to = $today->copy()->subMonthsNoOverflow($i);

        $inBucket = $records->filter(
            fn ($r) =>
                $r['date']->gte($from)
                && ($i === 0 || $r['date']->lt($to))
        );

        $demandBuckets[$i] = (int) $inBucket->sum('qty');
        $dispensedBuckets[$i] = (int) $inBucket->sum('dispensed');
        $unmetBuckets[$i] = (int) $inBucket->sum('unmet');
    }

    $period1 = $demandBuckets[0];
    $period2 = $demandBuckets[1];
    $period3 = $demandBuckets[2];

    $history = array_reverse($demandBuckets);

    $unmetRecent =
        $unmetBuckets[0]
        + $unmetBuckets[1]
        + $unmetBuckets[2];

    $hasHistory = $records->filter(
        fn ($r) =>
            $r['date']->gte(
                $today->copy()->subMonthsNoOverflow(3)
            )
    )->isNotEmpty();

    $medicineAgeInDays = $medicine->created_at
        ? $medicine->created_at->diffInDays($now, true)
        : 0;

    $ageMonths = (int) floor($medicineAgeInDays / 30);

    $monthsAvailable = min(3, $ageMonths);

    $trendMonthsAvailable =
        min(
            self::TREND_WINDOW_MONTHS,
            $ageMonths
        );

    if ($monthsAvailable >= 3) {
        $dataQuality = 'sufficient';
    } elseif ($monthsAvailable >= 1) {
        $dataQuality = 'partial';
    } else {
        $dataQuality = 'insufficient';
    }

    if ($dataQuality === 'sufficient') {
        $movingAverage = (int) round(
            ($period1 + $period2 + $period3) / 3
        );
    } elseif ($dataQuality === 'partial' && $hasHistory) {
        $movingAverage = (int) round(
            ($period1 + $period2 + $period3)
            / max(1, $monthsAvailable)
        );
    } else {
        $movingAverage = 0;
    }

    $window = array_slice(
        $history,
        -max(1, $trendMonthsAvailable)
    );

    $trend = [
        'status' => 'none',
        'slope' => 0.0,
        'adjustment' => 0.0
    ];

    if ($dataQuality !== 'insufficient' && $hasHistory) {
        $trend = self::computeTrend(
            $window,
            $movingAverage
        );
    }

    $estimatedDemand = max(
        0,
        (int) round(
            $movingAverage + $trend['adjustment']
        )
    );

    $spread = self::computeRangeAndConfidence(
        $window,
        $estimatedDemand,
        $dataQuality
    );

    return [
        'period1' => $period1,
        'period2' => $period2,
        'period3' => $period3,

        'monthly_history' => $history,

        'monthly_dispensed' =>
            array_reverse($dispensedBuckets),

        'monthly_unmet' =>
            array_reverse($unmetBuckets),

        'unmet_demand_3mo' =>
            $unmetRecent,

        'moving_average' =>
            $movingAverage,

        'estimated_demand' =>
            $estimatedDemand,

        'range_low' =>
            $spread['range_low'],

        'range_high' =>
            $spread['range_high'],

        'demand_sd' =>
            $spread['sd'],

        'confidence' =>
            $spread['confidence'],

        'confidence_reasons' =>
            $spread['reasons'],

        'trend_applied' =>
            $trend['status'] === 'applied',

        'trend_status' =>
            $trend['status'],

        'trend_slope' =>
            round($trend['slope'], 2),

        'trend_adjustment' =>
            round($trend['adjustment'], 1),

        'trend_direction' =>
            $trend['status'] !== 'applied'
                ? 'none'
                : (
                    $trend['adjustment'] > 0
                        ? 'up'
                        : (
                            $trend['adjustment'] < 0
                                ? 'down'
                                : 'flat'
                        )
                ),

        'forecast_method' =>
            $trend['status'] === 'applied'
                ? 'Trend-adjusted 3-month moving average'
                : '3-month moving average (trend not applied)',

        'has_history' =>
            $hasHistory,

        'data_quality' =>
            $dataQuality,

        'months_available' =>
            $monthsAvailable,

        'trend_months_available' =>
            $trendMonthsAvailable,

        'seasonal' =>
            $this->seasonalReadiness(
                $records,
                $today
            ),
    ];
}

/**
 * Rolling-origin backtest of the production forecast.
 *
 * Each fold forecasts the next 30 days using only information available
 * before the cutoff date, then compares that forecast with actual demand.
 */
public function backtestMedicine(
    Medicine $medicine,
    int $folds = 12
): array {
    $today = Carbon::now()->startOfDay();

    $observations = [];

    $latestCompletedCutoff =
        $today->copy()->subDays(31);

    for ($i = 0; $i < max(1, $folds); $i++) {

        $asOf = $latestCompletedCutoff
            ->copy()
            ->subMonthsNoOverflow($i);

        $targetStart =
            $asOf->copy()->addDay();

        $targetEndExclusive =
            $targetStart->copy()->addDays(30);

        if ($targetEndExclusive->gt($today)) {
            continue;
        }

        $forecast =
            $this->estimateMonthlyDemandAt(
                $medicine,
                $asOf
            );

        if (!$forecast['data_quality']
            || $forecast['data_quality'] === 'insufficient'
        ) {
            continue;
        }

        $actual =
            $this->actualDemandForPeriod(
                $medicine,
                $targetStart,
                $targetEndExclusive
            );

        $forecasted =
            (int) $forecast['estimated_demand'];

        $error =
            $forecasted - $actual;

        $absoluteError =
            abs($error);

        $absolutePercentageError =
            $actual > 0
                ? round(
                    ($absoluteError / $actual) * 100,
                    2
                )
                : null;

        $observations[] = [
            'as_of' =>
                $asOf->toDateString(),

            'target_start' =>
                $targetStart->toDateString(),

            'target_end' =>
                $targetEndExclusive
                    ->copy()
                    ->subDay()
                    ->toDateString(),

            'forecast' =>
                $forecasted,

            'actual' =>
                $actual,

            'error' =>
                $error,

            'absolute_error' =>
                $absoluteError,

            'absolute_percentage_error' =>
                $absolutePercentageError,

            'forecast_method' =>
                $forecast['forecast_method'],

            'confidence' =>
                $forecast['confidence'],

            'moving_average' =>
                $forecast['moving_average'],

            'trend_adjustment' =>
                $forecast['trend_adjustment'],
        ];
    }

    return [
        'observations' =>
            $observations,

        'metrics' =>
            self::computeAccuracyMetrics(
                $observations
            ),
    ];
}

/**
 * Calculate defense-ready forecasting metrics.
 */
public static function computeAccuracyMetrics(
    array $observations
): array {
    $count = count($observations);

    if ($count === 0) {
        return [
            'n' => 0,
            'mae' => null,
            'wape' => null,
            'accuracy' => null,
            'bias' => null,
            'total_actual' => 0,
            'total_absolute_error' => 0,
        ];
    }

    $totalActual =
        (int) array_sum(
            array_column(
                $observations,
                'actual'
            )
        );

    $totalAbsoluteError =
        (int) array_sum(
            array_column(
                $observations,
                'absolute_error'
            )
        );

    $signedErrors =
        array_sum(
            array_column(
                $observations,
                'error'
            )
        );

    $mae =
        $totalAbsoluteError / $count;

    $wape =
        $totalActual > 0
            ? ($totalAbsoluteError / $totalActual) * 100
            : null;

    return [
        'n' => $count,

        'mae' =>
            round($mae, 2),

        'wape' =>
            $wape !== null
                ? round($wape, 2)
                : null,

        'accuracy' =>
            $wape !== null
                ? round(
                    max(0, 100 - $wape),
                    2
                )
                : null,

        'bias' =>
            round(
                $signedErrors / $count,
                2
            ),

        'total_actual' =>
            $totalActual,

        'total_absolute_error' =>
            $totalAbsoluteError,
    ];
}

/**
 * Actual demand during the historical 30-day evaluation window.
 */
public function actualDemandForPeriod(
    Medicine $medicine,
    Carbon $from,
    Carbon $to
): int {
    $rows = $medicine
        ->dispensingRecords()
        ->where(
            'dispense_date',
            '>=',
            $from->toDateString()
        )
        ->where(
            'dispense_date',
            '<',
            $to->toDateString()
        )
        ->get([
            'id',
            'prescription_id',
            'dispense_date',
            'quantity_dispensed',
            'unfulfilled_quantity'
        ]);

    if ($rows->isEmpty()) {
        return 0;
    }

    $unmetById =
        self::resolveUnmetDemand(
            $rows->map(fn ($r) => [
                'id' => (int) $r->id,
                'prescription_id' =>
                    $r->prescription_id
                        ? (int) $r->prescription_id
                        : null,
                'unfulfilled' =>
                    (int) $r->unfulfilled_quantity,
            ])->all(),
            $this->cancelledPrescriptionIds(
                $rows
            )
        );

    return (int) $rows->sum(
        function ($row) use ($unmetById) {

            return
                (int) $row->quantity_dispensed
                + (
                    $unmetById[(int) $row->id]
                    ?? 0
                );
        }
    );
}

/**
 * Update one saved forecast after its target period has completed.
 */
public function evaluateSnapshotAccuracy(
    ForecastSnapshot $snapshot
): ForecastSnapshot {
    $today = Carbon::today();

    /*
     * Do not evaluate a forecast whose target period has not
     * completely ended yet.
     */
    if ($snapshot->target_end->gte($today)) {
        return $snapshot;
    }

    $actual = $this->actualDemandForPeriod(
        $snapshot->medicine,
        $snapshot->target_start->copy()->startOfDay(),
        $snapshot->target_end->copy()->addDay()->startOfDay()
    );

    $forecast = (int) $snapshot->forecasted_demand;

    $error = $forecast - $actual;

    $absoluteError = abs($error);

    $absolutePercentageError = $actual > 0
        ? round(($absoluteError / $actual) * 100, 2)
        : null;

    $snapshot->update([
        'actual_demand' => $actual,
        'error' => $error,
        'absolute_error' => $absoluteError,
        'absolute_percentage_error' => $absolutePercentageError,
        'evaluated_at' => now(),
    ]);

    return $snapshot->fresh();
}

    /** Prescriptions whose fulfilment outcome is "cancelled" (not real unmet demand). */
    private function cancelledPrescriptionIds($rows): array
    {
        $ids = $rows->pluck('prescription_id')->filter()->unique()->values()->all();
        if (empty($ids) || !class_exists(\App\Models\ConsultationPrescription::class)) {
            return [];
        }

        return \App\Models\ConsultationPrescription::whereIn('id', $ids)
            ->where('fulfillment_outcome', \App\Models\ConsultationPrescription::OUTCOME_CANCELLED)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Pure. Decide which dispensing records contribute unmet demand.
     * A follow-up dispense of a prescription's remainder is a NEW
     * record, so only the latest record per prescription counts its
     * unfulfilled quantity; earlier ones were re-requested and would be
     * double counted. Cancelled prescriptions never count.
     *
     * @param  array<int,array{id:int,prescription_id:?int,unfulfilled:int}>  $rows
     * @param  int[]  $cancelledPrescriptionIds
     * @return array<int,int>  record id => unmet quantity to count
     */
    public static function resolveUnmetDemand(array $rows, array $cancelledPrescriptionIds = []): array
    {
        $latest = [];
        foreach ($rows as $r) {
            $rx = $r['prescription_id'];
            if ($rx !== null && (!isset($latest[$rx]) || $r['id'] > $latest[$rx])) {
                $latest[$rx] = $r['id'];
            }
        }

        $cancelled = array_flip($cancelledPrescriptionIds);
        $out = [];
        foreach ($rows as $r) {
            $rx = $r['prescription_id'];
            $counts = $r['unfulfilled'] > 0
                && ($rx === null || ($latest[$rx] === $r['id'] && !isset($cancelled[$rx])));
            $out[$r['id']] = $counts ? $r['unfulfilled'] : 0;
        }

        return $out;
    }

    /**
     * Pure. Range and confidence for a forecast.
     *
     * @param  int[]  $window  Monthly demand, oldest -> newest.
     * @return array{range_low:?int, range_high:?int, sd:float, confidence:string, reasons:string[]}
     */
    public static function computeRangeAndConfidence(array $window, int $forecast, string $dataQuality): array
    {
        if ($dataQuality === 'insufficient') {
            return ['range_low' => null, 'range_high' => null, 'sd' => 0.0, 'confidence' => 'None',
                'reasons' => ['Not enough history to forecast.']];
        }

        $n = count($window);
        $active = count(array_filter($window, fn ($v) => $v > 0));
        $mean = $n > 0 ? array_sum($window) / $n : 0.0;

        $sd = 0.0;
        if ($n >= 2) {
            $ss = 0.0;
            foreach ($window as $v) {
                $ss += ($v - $mean) ** 2;
            }
            $sd = sqrt($ss / ($n - 1));
        }
        $cv = $mean > 0 ? $sd / $mean : null;

        $low = $high = null;
        if ($n >= 3) {
            $low = max(0, (int) round($forecast - self::RANGE_SD_MULTIPLIER * $sd));
            $high = (int) round($forecast + self::RANGE_SD_MULTIPLIER * $sd);
        }

        $reasons = [];
        if ($dataQuality !== 'sufficient') {
            $reasons[] = 'Less than 3 months of history.';
        }
        if ($n < self::CONFIDENCE_HIGH_MIN_MONTHS) {
            $reasons[] = "Only {$n} month(s) of history available (" . self::CONFIDENCE_HIGH_MIN_MONTHS . ' needed for High).';
        }
        if ($active < self::CONFIDENCE_HIGH_MIN_ACTIVE) {
            $reasons[] = "Only {$active} of the last {$n} month(s) had any demand (" . self::CONFIDENCE_HIGH_MIN_ACTIVE . ' needed for High).';
        }
        if ($cv === null) {
            $reasons[] = 'No demand recorded in the window.';
        } elseif ($cv > self::CONFIDENCE_HIGH_MAX_CV) {
            $reasons[] = 'Monthly demand varies widely (spread is ' . number_format($cv, 1) . 'x its average).';
        }

        if ($dataQuality !== 'sufficient' || $n < 3 || $active < self::CONFIDENCE_MEDIUM_MIN_ACTIVE
            || $cv === null || $cv > self::CONFIDENCE_MEDIUM_MAX_CV) {
            $confidence = 'Low';
        } elseif ($n >= self::CONFIDENCE_HIGH_MIN_MONTHS && $active >= self::CONFIDENCE_HIGH_MIN_ACTIVE
            && $cv <= self::CONFIDENCE_HIGH_MAX_CV) {
            $confidence = 'High';
        } else {
            $confidence = 'Medium';
        }

        return ['range_low' => $low, 'range_high' => $high, 'sd' => round($sd, 2),
            'confidence' => $confidence, 'reasons' => $reasons];
    }

    /**
     * Pure trend calculation (no database) so it can be unit-tested.
     *
     * @param  int[]  $window  Monthly totals, oldest -> newest, only for
     *                         months that actually existed.
     * @param  float  $movingAverage  The 3-month MA the trend adjusts.
     * @return array{status:string, slope:float, adjustment:float}
     *         status: applied | short_history | sparse
     */
    public static function computeTrend(array $window, float $movingAverage): array
    {
        $n = count($window);

        if ($n < self::TREND_MIN_MONTHS) {
            return ['status' => 'short_history', 'slope' => 0.0, 'adjustment' => 0.0];
        }

        $active = count(array_filter($window, fn ($v) => $v > 0));
        if ($active < self::TREND_MIN_ACTIVE_MONTHS) {
            return ['status' => 'sparse', 'slope' => 0.0, 'adjustment' => 0.0];
        }

        // Ordinary least-squares slope of y against x = 0..n-1.
        $xMean = ($n - 1) / 2;
        $yMean = array_sum($window) / $n;
        $sxy = 0.0;
        $sxx = 0.0;
        foreach (array_values($window) as $x => $y) {
            $sxy += ($x - $xMean) * ($y - $yMean);
            $sxx += ($x - $xMean) ** 2;
        }
        $slope = $sxx > 0 ? $sxy / $sxx : 0.0;

        $adjustment = $slope * self::TREND_LAG_MONTHS * self::TREND_DAMPING;
        $cap = $movingAverage * self::TREND_MAX_ADJUSTMENT_RATIO;
        $adjustment = max(-$cap, min($cap, $adjustment));

        return ['status' => 'applied', 'slope' => $slope, 'adjustment' => $adjustment];
    }

    /**
     * Is there enough history for a seasonal forecast? Never fabricates
     * one - it only reports whether the bar is met and what is missing.
     *
     * @param  \Illuminate\Support\Collection  $records  [date, qty] rows
     *         covering the last SEASONAL_MIN_HISTORY_MONTHS + 1 months.
     * @return array{ready:bool, span_months:int, active_months:int, required_span_months:int, required_active_months:int, message:string}
     */
    private function seasonalReadiness($records, Carbon $today): array
    {
        $windowStart = $today->copy()->subMonthsNoOverflow(self::SEASONAL_MIN_HISTORY_MONTHS);

        $inWindow = $records->filter(fn ($r) => $r['date']->gte($windowStart));
        $activeMonths = $inWindow->map(fn ($r) => $r['date']->format('Y-m'))->unique()->count();

        $earliest = $records->min('date');
        $spanMonths = $earliest ? (int) floor(abs($earliest->diffInMonths($today))) : 0;

        $ready = $spanMonths >= self::SEASONAL_MIN_HISTORY_MONTHS
            && $activeMonths >= self::SEASONAL_MIN_ACTIVE_MONTHS;

        $message = $ready
            ? 'Enough history exists to evaluate seasonal methods, but seasonal forecasting is not yet implemented. The current forecast is trend-adjusted only.'
            : 'Seasonal forecasting is not available: it needs at least '
                . self::SEASONAL_MIN_HISTORY_MONTHS . ' months of history (two full annual cycles) with dispensing in at least '
                . self::SEASONAL_MIN_ACTIVE_MONTHS . ' of those months. This medicine has ' . $spanMonths
                . ' month(s) of history and ' . $activeMonths . ' active month(s) in the last '
                . self::SEASONAL_MIN_HISTORY_MONTHS . '. Seasonal patterns are not modelled.';

        return [
            'ready' => $ready,
            'span_months' => $spanMonths,
            'active_months' => $activeMonths,
            'required_span_months' => self::SEASONAL_MIN_HISTORY_MONTHS,
            'required_active_months' => self::SEASONAL_MIN_ACTIVE_MONTHS,
            'message' => $message,
        ];
    }

    /**
     * Recent stock-out incident count for this medicine (contextual
     * information only - a stock-out is an availability event, not a
     * consumption quantity, so it never feeds into estimateMonthlyDemand).
     */
    public function getStockOutContext(Medicine $medicine, int $withinDays = 90): array
    {
        $logs = \App\Models\StockOutLog::where('medicine_id', $medicine->id)
            ->where('created_at', '>=', now()->subDays($withinDays))
            ->get();

        return [
            'incident_count' => $logs->count(),
            'total_patients_affected' => (int) $logs->sum('patients_affected'),
            'last_incident_at' => $logs->max('created_at'),
        ];
    }

    /**
     * Recent medicine request context (contextual information only -
     * a REQUESTED quantity is not the same as a DISPENSED quantity,
     * so this never feeds into estimateMonthlyDemand either).
     */
    public function getRequestContext(Medicine $medicine, int $withinDays = 90): array
    {
        if (!class_exists(\App\Models\MedicineRequest::class)) {
            return ['request_count' => 0, 'total_requested' => 0];
        }

        $requests = \App\Models\MedicineRequest::where('medicine_id', $medicine->id)
            ->where('created_at', '>=', now()->subDays($withinDays))
            ->get();

        return [
            'request_count' => $requests->count(),
            'total_requested' => (int) $requests->sum('quantity_requested'),
        ];
    }

    /**
     * Full evaluation for one medicine: demand forecast, usable stock,
     * status classification, and a reorder recommendation with an
     * explicit, documented formula (see class docblock).
     */
    public function evaluate(Medicine $medicine): array
    {
        $demand = $this->estimateMonthlyDemand($medicine);
        $estDemand = $demand['estimated_demand'];
        $usableStock = $this->stockService->usableStock($medicine);
        $expiredStock = $this->stockService->expiredStock($medicine);
        // Dynamic Reorder Level = Average Monthly Demand x Lead Time + Safety Stock.
        // Uses the trend-adjusted forecast as demand, so it
        // automatically recalculates whenever usage data changes -
        // replaces the old static threshold-of-10 for every medicine.
        $threshold = (int) round(($estDemand * self::LEAD_TIME_MONTHS) + self::SAFETY_STOCK_UNITS);

        // Insufficient data: don't fabricate a forecast for a medicine
        // that hasn't existed long enough to have real history.
        if ($demand['data_quality'] === 'insufficient') {
            $status = $usableStock <= 0 ? 'Critical' : 'Insufficient Data';
            $statusClass = $usableStock <= 0 ? 'text-red-600 font-bold' : 'text-gray-500';
            $recommendation = $usableStock <= 0
                ? 'No usable stock - restock recommended'
                : 'Insufficient historical data for a reliable forecast';

            return array_merge($demand, [
                'medicine' => $medicine,
                'usable_stock' => $usableStock,
                'expired_stock' => $expiredStock,
                'threshold' => $threshold,
                'status' => $status,
                'status_class' => $statusClass,
                'recommendation' => $recommendation,
                'suggested_reorder_qty' => 0,
                'suggested_reorder_range' => null,
                'rationale' => $this->buildRationale($demand, $usableStock, $expiredStock, $threshold, $status, true),
                'is_shortage' => false,
                'is_low' => $usableStock <= 0,
                'forecast_available' => false,
            ]);
        }

        $status = 'Stable Inventory';
        $statusClass = 'text-green-600';
        $recommendation = 'Maintain Current Stock';
        $suggestedQty = 0;

        if ($usableStock <= 0) {
            $status = 'Critical';
            $statusClass = 'text-red-600 font-bold';
            $suggestedQty = max(0, (int) round($estDemand * self::SAFETY_MULTIPLIER));
            $recommendation = 'No usable stock - restock immediately | Suggested: ' . $suggestedQty;
        } elseif ($estDemand > 0 && $usableStock < $estDemand) {
            $status = 'Possible Shortage';
            $statusClass = 'text-red-600 font-bold';
            $suggestedQty = max(0, (int) round(($estDemand * self::SAFETY_MULTIPLIER)) - $usableStock);
            $recommendation = 'Restock Immediately | Suggested: ' . $suggestedQty;
        } elseif ($usableStock < $threshold) {
            $status = 'Low Stock Alert';
            $statusClass = 'text-yellow-600';
            $recommendation = 'Monitor Inventory';
        }

        $reorderRange = null;
        if (in_array($status, ['Critical', 'Possible Shortage'], true) && $demand['range_low'] !== null) {
            $reorderRange = [
                max(0, (int) round($demand['range_low'] * self::SAFETY_MULTIPLIER) - $usableStock),
                max(0, (int) round($demand['range_high'] * self::SAFETY_MULTIPLIER) - $usableStock),
            ];
        }

        return array_merge($demand, [
            'medicine' => $medicine,
            'usable_stock' => $usableStock,
            'expired_stock' => $expiredStock,
            'threshold' => $threshold,
            'status' => $status,
            'status_class' => $statusClass,
            'recommendation' => $recommendation,
            'suggested_reorder_qty' => $suggestedQty,
            'suggested_reorder_range' => $reorderRange,
            'rationale' => $this->buildRationale($demand, $usableStock, $expiredStock, $threshold, $status, false),
            'is_shortage' => $status === 'Possible Shortage',
            'is_low' => in_array($status, ['Low Stock Alert', 'Critical'], true),
            'forecast_available' => true,
        ]);
    }

    /**
     * The "why" behind a status: the rule that fired, the inputs it used,
     * the confidence (with reasons) and any caveats. Shown identically
     * on the Forecast page and in AI Insights so staff can always see
     * what a recommendation rests on.
     *
     * @return array{rule:string, inputs:array<int,array{label:string,value:string}>, confidence:string, confidence_reasons:string[], caveats:string[]}
     */
    private function buildRationale(array $d, int $usable, int $expired, int $threshold, string $status, bool $insufficient): array
    {
        $est = $d['estimated_demand'];

        if ($insufficient && $status !== 'Critical') {
            $rule = 'Not enough dispensing history to forecast, so only the stock level is checked.';
        } else {
            $rule = match ($status) {
                'Critical' => 'Usable stock is 0, so the medicine cannot be dispensed.',
                'Possible Shortage' => "Usable stock ({$usable}) is below forecast demand for the next 30 days ({$est}).",
                'Low Stock Alert' => "Usable stock ({$usable}) is below the reorder level ({$threshold}). Reorder level = forecast demand ({$est}) x "
                    . self::LEAD_TIME_MONTHS . ' month lead time + ' . self::SAFETY_STOCK_UNITS . ' units safety stock.',
                default => "Usable stock ({$usable}) covers forecast demand ({$est}) and is at or above the reorder level ({$threshold}).",
            };
        }

        $inputs = [['label' => 'Usable stock', 'value' => (string) $usable]];
        if (!$insufficient) {
            $forecastText = (string) $est;
            if ($d['range_low'] !== null) {
                $forecastText .= " (expected range {$d['range_low']}-{$d['range_high']})";
            }
            $inputs[] = ['label' => 'Forecast demand, next 30 days', 'value' => $forecastText];
            $inputs[] = ['label' => 'Method', 'value' => $d['forecast_method']];
        }
        $inputs[] = ['label' => 'Reorder level', 'value' => (string) $threshold];
        if ($expired > 0) {
            $inputs[] = ['label' => 'Expired stock (not usable)', 'value' => (string) $expired];
        }

        $caveats = [];
        if (!$insufficient) {
            if ($d['trend_status'] === 'sparse') {
                $caveats[] = 'Trend not applied: too few months with any demand.';
            } elseif ($d['trend_status'] === 'short_history') {
                $caveats[] = 'Trend not applied: fewer than ' . self::TREND_MIN_MONTHS . ' months of history.';
            }
            if ($d['unmet_demand_3mo'] > 0) {
                $caveats[] = "Includes {$d['unmet_demand_3mo']} unit(s) of unmet demand from the last 3 months (requested but not dispensed).";
            }
            if ($d['confidence'] === 'Low' && in_array($status, ['Critical', 'Possible Shortage', 'Low Stock Alert'], true)) {
                $caveats[] = 'Low confidence: check recent dispensing before ordering.';
            }
        }
        if (!$d['seasonal']['ready']) {
            $caveats[] = 'Seasonal demand is not modelled (needs ' . self::SEASONAL_MIN_HISTORY_MONTHS . ' months of history).';
        }

        return [
            'rule' => $rule,
            'inputs' => $inputs,
            'confidence' => $d['confidence'],
            'confidence_reasons' => $d['confidence_reasons'],
            'caveats' => $caveats,
        ];
    }

    /**
     * AI INSIGHTS - decision-support only, generated purely from the
     * SAME data this service already computes (evaluate(), expiringSoon()).
     * No invented numbers, no supplier/lead-time data (not tracked by
     * this system), no medical claims. This is rule-based analysis
     * over real data, not a machine-learning model - labeled "AI
     * Insights" in the UI as an AI-assisted analysis LAYER on top of
     * genuine data, not as a claim that ML is being used internally.
     *
     * @return array<int, array{severity:string, medicine_name:string, title:string, detail:string, medicine_id:int}>
     */
    public function generateInsights(): array
    {
        $insights = [];
        $evals = [];
        $medicines = Medicine::all();

        foreach ($medicines as $medicine) {
            $eval = $this->evaluate($medicine);
            $evals[$medicine->id] = $eval;
            $demand = $eval['estimated_demand'];
            $stock = $eval['usable_stock'];
            $threshold = $eval['threshold'];
            $name = $medicine->name;

            // ---- Insufficient data: say so plainly, no fabricated insight ----
            if ($eval['data_quality'] === 'insufficient') {
                if ($stock <= 0) {
                    $insights[] = $this->makeInsight('critical', $medicine, $name . ' has no usable stock',
                        'No usable stock on hand, and there is not yet enough dispensing history (' . $eval['months_available'] . ' month(s) available) to forecast demand reliably. Restocking should be evaluated manually.');
                } else {
                    $insights[] = $this->makeInsight('info', $medicine, $name . ' has insufficient data for a forecast',
                        'Only ' . $eval['months_available'] . ' month(s) of dispensing history are available - a reliable 3-month moving-average forecast is not yet possible. Current usable stock is ' . $stock . '.');
                }
                continue;
            }

            // ---- CRITICAL: projected stock-out (demand exceeds usable stock) ----
            if ($demand > 0 && $stock < $demand) {
                $daysOfStockLeft = $demand > 0 ? (int) round(($stock / $demand) * 30) : null;
                $insights[] = $this->makeInsight('critical', $medicine, $name . ' is projected to run short before next restock',
                    "Estimated monthly demand ({$demand}) exceeds current usable stock ({$stock})."
                    . ($daysOfStockLeft !== null ? " At this pace, usable stock may be depleted in approximately {$daysOfStockLeft} day(s)." : '')
                    . ' Suggested reorder quantity: ' . $eval['suggested_reorder_qty'] . '.');
            } elseif ($stock <= 0) {
                $insights[] = $this->makeInsight('critical', $medicine, $name . ' has no usable stock',
                    'Usable stock is currently 0. Suggested reorder quantity: ' . $eval['suggested_reorder_qty'] . '.');
            }

            // ---- CRITICAL: near-expiry stock likely to expire before it's used ----
            $expiringSoonQty = $this->stockService->expiringSoon($medicine, 30);
            if ($expiringSoonQty > 0 && $demand > 0 && $expiringSoonQty > $demand) {
                $insights[] = $this->makeInsight('critical', $medicine, $expiringSoonQty . ' unit(s) of ' . $name . ' may expire before they can be used',
                    "Estimated monthly demand is only {$demand}, but {$expiringSoonQty} unit(s) are expiring within 30 days. Consider prioritizing this batch for dispensing, or reviewing for redistribution.");
            }

            // ---- WARNING: below reorder threshold (not yet a shortage) ----
            if ($eval['status'] === 'Low Stock Alert') {
                $insights[] = $this->makeInsight('warning', $medicine, $name . ' has dropped below its reorder point',
                    "Usable stock ({$stock}) is below the reorder level ({$threshold}). Estimated monthly demand is {$demand}.");
            }

            // ---- WARNING/INFO: demand change vs previous period ----
            if ($eval['data_quality'] === 'sufficient' && $eval['period2'] > 0) {
                $change = (($eval['period1'] - $eval['period2']) / $eval['period2']) * 100;
                $changeRounded = (int) round($change);

                if ($changeRounded >= 30) {
                    $insights[] = $this->makeInsight('warning', $medicine, 'Demand for ' . $name . ' is increasing significantly',
                        "Demand rose from {$eval['period2']} to {$eval['period1']} unit(s) over the last two 1-month periods (+{$changeRounded}%). Current usable stock is {$stock}.");
                } elseif (abs($changeRounded) >= 15) {
                    $direction = $changeRounded > 0 ? 'increased' : 'decreased';
                    $insights[] = $this->makeInsight('info', $medicine, "Demand for {$name} has {$direction} {$changeRounded}% this period",
                        "Demand went from {$eval['period2']} to {$eval['period1']} unit(s) over the last two 1-month periods.");
                }
            }

            // ---- POSITIVE: comfortably stocked, stable ----
            if ($eval['status'] === 'Stable Inventory' && $threshold > 0 && $stock >= $threshold * 1.5) {
                $insights[] = $this->makeInsight('positive', $medicine, $name . ' is comfortably stocked',
                    "Usable stock ({$stock}) is well above the reorder level ({$threshold}), and estimated monthly demand ({$demand}) appears stable. No action needed.");
            }
        }

        foreach ($insights as &$ins) {
            $e = $evals[$ins['medicine_id']] ?? null;
            $ins['confidence'] = $e['confidence'] ?? 'None';
            $ins['why'] = $e['rationale']['rule'] ?? '';
            $ins['caveats'] = $e['rationale']['caveats'] ?? [];
        }
        unset($ins);

        // Critical first, then warning, info, positive.
        $order = ['critical' => 0, 'warning' => 1, 'info' => 2, 'positive' => 3];
        usort($insights, fn($a, $b) => $order[$a['severity']] <=> $order[$b['severity']]);

        return $insights;
    }

    private function makeInsight(string $severity, Medicine $medicine, string $title, string $detail): array
    {
        return [
            'severity' => $severity,
            'medicine_id' => $medicine->id,
            'medicine_name' => $medicine->name,
            'title' => $title,
            'detail' => $detail,
        ];
    }
}
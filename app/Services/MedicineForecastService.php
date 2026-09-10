<?php

namespace App\Services;

use App\Models\Medicine;
use Carbon\Carbon;

/**
 * MedicineForecastService
 * ========================
 * Single source of truth for demand forecasting and restock
 * recommendations. This is a MOVING-AVERAGE statistical forecast,
 * not a machine-learning model - described accurately in any
 * thesis/documentation as "automated demand forecasting using a
 * 3-month moving average," not as AI/ML prediction.
 *
 * METHOD: simple 3-month moving average of dispensing history.
 *   estimated_monthly_demand = (month1 + month2 + month3) / 3
 * All three months are ALWAYS included, even if one had zero
 * dispensing - a quiet month is real data, not missing data, so it
 * must not be dropped from the average.
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

    public function __construct(
        private InventoryStockService $stockService
    ) {
    }

    /**
     * Compute the 3-month moving average estimated monthly demand for
     * a medicine. Always divides by 3, regardless of how many of those
     * months had zero dispensing - a zero-usage month is still a real,
     * counted data point, AS LONG AS the medicine actually existed
     * during that month. A medicine added to the system 3 weeks ago
     * does not have "3 months of zero history" before that - it simply
     * has no data for those months, which is a different situation.
     *
     * @return array{period1:int, period2:int, period3:int, estimated_demand:int, has_history: bool, data_quality: string, months_available: int}
     */
    public function estimateMonthlyDemand(Medicine $medicine): array
    {
        $now = Carbon::now();
        $oneMonthAgo = $now->copy()->subMonth();
        $twoMonthsAgo = $now->copy()->subMonths(2);
        $threeMonthsAgo = $now->copy()->subMonths(3);

        $records = $medicine->dispensingRecords()
            ->where('dispense_date', '>=', $threeMonthsAgo)
            ->get();

        $period1 = (int) $records->where('dispense_date', '>=', $oneMonthAgo)
            ->sum('quantity_dispensed');

        $period2 = (int) $records->where('dispense_date', '>=', $twoMonthsAgo)
            ->where('dispense_date', '<', $oneMonthAgo)
            ->sum('quantity_dispensed');

        $period3 = (int) $records->where('dispense_date', '>=', $threeMonthsAgo)
            ->where('dispense_date', '<', $twoMonthsAgo)
            ->sum('quantity_dispensed');

        $hasHistory = $records->isNotEmpty();

        // How many of the 3 monthly periods actually existed for this
        // medicine (i.e. the medicine was already in the system)?
        $medicineAgeInDays = $medicine->created_at
            ? $medicine->created_at->diffInDays($now)
            : 0;
        $monthsAvailable = min(3, (int) floor($medicineAgeInDays / 30));

        if ($monthsAvailable >= 3) {
            $dataQuality = 'sufficient';
        } elseif ($monthsAvailable >= 1) {
            $dataQuality = 'partial';
        } else {
            $dataQuality = 'insufficient';
        }

        if ($dataQuality === 'sufficient') {
            // Always divide by 3 - a quiet month counts as zero, not missing.
            $estimatedDemand = (int) round(($period1 + $period2 + $period3) / 3);
        } elseif ($dataQuality === 'partial' && $hasHistory) {
            // Average only over the months that actually existed, so a
            // 1-month-old medicine isn't diluted by two months that
            // never happened.
            $estimatedDemand = (int) round(($period1 + $period2 + $period3) / max(1, $monthsAvailable));
        } else {
            // No real history to forecast from at all.
            $estimatedDemand = 0;
        }

        return [
            'period1' => $period1,
            'period2' => $period2,
            'period3' => $period3,
            'estimated_demand' => $estimatedDemand,
            'has_history' => $hasHistory,
            'data_quality' => $dataQuality,
            'months_available' => $monthsAvailable,
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
        $threshold = (int) ($medicine->threshold ?? 10);

        // Insufficient data: don't fabricate a forecast for a medicine
        // that hasn't existed long enough to have real history.
        if ($demand['data_quality'] === 'insufficient') {
            $status = $usableStock <= 0 ? 'Critical' : 'Insufficient Data';
            $statusClass = $usableStock <= 0 ? 'text-red-600 font-bold' : 'text-gray-500';
            $recommendation = $usableStock <= 0
                ? 'No usable stock - restock recommended'
                : 'Insufficient historical data for a reliable 3-month forecast';

            return array_merge($demand, [
                'medicine' => $medicine,
                'usable_stock' => $usableStock,
                'expired_stock' => $expiredStock,
                'threshold' => $threshold,
                'status' => $status,
                'status_class' => $statusClass,
                'recommendation' => $recommendation,
                'suggested_reorder_qty' => 0,
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

        return array_merge($demand, [
            'medicine' => $medicine,
            'usable_stock' => $usableStock,
            'expired_stock' => $expiredStock,
            'threshold' => $threshold,
            'status' => $status,
            'status_class' => $statusClass,
            'recommendation' => $recommendation,
            'suggested_reorder_qty' => $suggestedQty,
            'is_shortage' => $status === 'Possible Shortage',
            'is_low' => in_array($status, ['Low Stock Alert', 'Critical'], true),
            'forecast_available' => true,
        ]);
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
        $medicines = Medicine::all();

        foreach ($medicines as $medicine) {
            $eval = $this->evaluate($medicine);
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
                        "Dispensing rose from {$eval['period2']} to {$eval['period1']} unit(s) over the last two 1-month periods (+{$changeRounded}%). Current usable stock is {$stock}.");
                } elseif (abs($changeRounded) >= 15) {
                    $direction = $changeRounded > 0 ? 'increased' : 'decreased';
                    $insights[] = $this->makeInsight('info', $medicine, "Demand for {$name} has {$direction} {$changeRounded}% this period",
                        "Dispensing went from {$eval['period2']} to {$eval['period1']} unit(s) over the last two 1-month periods.");
                }
            }

            // ---- POSITIVE: comfortably stocked, stable ----
            if ($eval['status'] === 'Stable Inventory' && $threshold > 0 && $stock >= $threshold * 1.5) {
                $insights[] = $this->makeInsight('positive', $medicine, $name . ' is comfortably stocked',
                    "Usable stock ({$stock}) is well above the reorder level ({$threshold}), and estimated monthly demand ({$demand}) appears stable. No action needed.");
            }
        }

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
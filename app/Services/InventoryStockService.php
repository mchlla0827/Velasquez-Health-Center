<?php

namespace App\Services;

use App\Models\Medicine;

/**
 * InventoryStockService
 * ======================
 * Single source of truth for stock calculations. Batches are the
 * authoritative record - this service always computes directly from
 * Batch rows rather than trusting any cached/legacy column.
 *
 * KEY DISTINCTION (this is the fix for the expired-stock bug):
 * - TOTAL STOCK   = every remaining unit across all batches, expired or not.
 *                   Informational only - NEVER use this for dispensing,
 *                   low-stock alerts, or forecasting decisions.
 * - USABLE STOCK  = remaining units in batches that are NOT expired.
 *                   This is what actually matters for every operational
 *                   decision (can we dispense this? is it low? is the
 *                   forecast demand covered?).
 * - EXPIRED STOCK = remaining units sitting in expired batches. This is
 *                   stock that physically exists but cannot be dispensed.
 */
class InventoryStockService
{
    /** Total physical stock across ALL batches, expired or not. Informational only. */
    public function totalStock(Medicine $medicine): int
    {
        return (int) $medicine->batches()->sum('quantity');
    }

    /** Usable stock: non-expired batches only. Use this for every operational decision. */
    public function usableStock(Medicine $medicine): int
    {
        return (int) $medicine->batches()
            ->where('expiry_date', '>=', now()->toDateString())
            ->sum('quantity');
    }

    /** Expired stock sitting on the shelf, unusable but physically present. */
    public function expiredStock(Medicine $medicine): int
    {
        return (int) $medicine->batches()
            ->where('expiry_date', '<', now()->toDateString())
            ->sum('quantity');
    }

    /** Usable stock expiring within the given number of days (default 180 / ~6 months). */
    public function expiringSoon(Medicine $medicine, int $withinDays = 180): int
    {
        return (int) $medicine->batches()
            ->where('expiry_date', '>=', now()->toDateString())
            ->where('expiry_date', '<=', now()->addDays($withinDays)->toDateString())
            ->sum('quantity');
    }

    /**
     * Stock status based on USABLE stock vs the medicine's own configured
     * threshold - never a hardcoded value duplicated per-screen.
     *
     * CRITICAL: usable stock <= half the threshold
     * LOW:      usable stock <= threshold
     * NORMAL:   otherwise
     */
    public function status(Medicine $medicine): string
    {
        $usable = $this->usableStock($medicine);
        $threshold = (int) ($medicine->threshold ?? 10);

        if ($usable <= 0) {
            return 'OUT_OF_STOCK';
        }
        if ($usable <= (int) floor($threshold / 2)) {
            return 'CRITICAL';
        }
        if ($usable <= $threshold) {
            return 'LOW';
        }
        return 'NORMAL';
    }

    /**
     * Full breakdown for a medicine - use this wherever a screen needs to
     * show more than one of these numbers, to avoid running the queries
     * multiple times.
     */
    public function breakdown(Medicine $medicine): array
    {
        return [
            'total_stock' => $this->totalStock($medicine),
            'usable_stock' => $this->usableStock($medicine),
            'expired_stock' => $this->expiredStock($medicine),
            'status' => $this->status($medicine),
        ];
    }

    /**
     * Keep the legacy `stock` / `current_stock` columns in sync with the
     * real, USABLE stock (not total - this was the bug: the old sync
     * method summed every batch including expired ones). Several older
     * screens still read these columns directly; this keeps them honest
     * until those screens are migrated to call this service directly.
     *
     * Also runs automatic stock-out detection/resolution - since this
     * method already runs after every inventory-affecting action
     * (dispense, stock-in, corrections), it's the single natural place
     * to keep stock-out status accurate without duplicating the check
     * in every controller.
     */
    public function syncLegacyColumns(Medicine $medicine): int
    {
        $usable = $this->usableStock($medicine);

        $medicine->forceFill([
            'stock' => $usable,
            'current_stock' => $usable,
        ])->save();

        $this->checkAndUpdateStockOutStatus($medicine, $usable);

        return $usable;
    }

    /**
     * Automatically create or resolve a stock-out record based on
     * current usable stock. No manual "Log Stock-Out" action needed -
     * this is the sole source of stock-out lifecycle changes going
     * forward. Prevents duplicate active records for the same medicine.
     */
    public function checkAndUpdateStockOutStatus(\App\Models\Medicine $medicine, ?int $usableStock = null): void
    {
        $usableStock = $usableStock ?? $this->usableStock($medicine);

        $activeStockOut = \App\Models\StockOutLog::where('medicine_id', $medicine->id)
            ->where('status', \App\Models\StockOutLog::STATUS_ACTIVE)
            ->first();

        if ($usableStock <= 0) {
            // Just ran out - open a new active stock-out record, unless
            // one is already active for this medicine.
            if (!$activeStockOut) {
                \App\Models\StockOutLog::create([
                    'medicine_id' => $medicine->id,
                    'status' => \App\Models\StockOutLog::STATUS_ACTIVE,
                    'usable_stock_at_detection' => $usableStock,
                    'quantity_needed' => 0,
                    'patients_affected' => 0,
                    'duration_days' => 0,
                ]);
            }
        } else {
            // Stock is available again - resolve the active record if one exists.
            if ($activeStockOut) {
                $activeStockOut->update([
                    'status' => \App\Models\StockOutLog::STATUS_RESOLVED,
                    'resolved_at' => now(),
                ]);
            }
        }
    }
}
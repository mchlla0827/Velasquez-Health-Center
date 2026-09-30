<?php

namespace App\Services;

use App\Models\Batch;
use Illuminate\Support\Facades\DB;

/**
 * Issues sequential batch numbers in the format BATCH-YYYY-NNNN
 * (e.g. BATCH-2026-0001, BATCH-2026-0002 ...). The counter restarts each year.
 *
 * - next(): issues and reserves the next number. MUST be called inside a
 *   DB::transaction() so the counter row stays locked until the batch is saved.
 * - peek(): read-only preview for the Stock-In form. Reserves nothing.
 */
class BatchNumberService
{
    public const PREFIX = 'BATCH';
    public const PAD    = 4;

    public function next(?int $year = null): string
    {
        $year = $year ?? (int) now()->year;

        // Create the year's counter if missing, seeded from any existing batches.
        DB::table('batch_sequences')->insertOrIgnore([
            'year'        => $year,
            'last_number' => $this->highestExisting($year),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Lock the counter row until the surrounding transaction commits.
        $row = DB::table('batch_sequences')->where('year', $year)->lockForUpdate()->first();

        $number = max((int) $row->last_number, $this->highestExisting($year)) + 1;

        // Defensive: never hand out a number that is already in use.
        while (Batch::where('batch_number', $this->format($year, $number))->exists()) {
            $number++;
        }

        DB::table('batch_sequences')->where('year', $year)->update([
            'last_number' => $number,
            'updated_at'  => now(),
        ]);

        return $this->format($year, $number);
    }

    public function peek(?int $year = null): string
    {
        $year = $year ?? (int) now()->year;

        $last = (int) (DB::table('batch_sequences')->where('year', $year)->value('last_number') ?? 0);

        return $this->format($year, max($last, $this->highestExisting($year)) + 1);
    }

    private function format(int $year, int $number): string
    {
        return sprintf('%s-%d-%s', self::PREFIX, $year, str_pad((string) $number, self::PAD, '0', STR_PAD_LEFT));
    }

    /** Highest numeric suffix among existing BATCH-<year>-<digits> batch numbers. */
    private function highestExisting(int $year): int
    {
        $max = 0;
        $pattern = '/^' . self::PREFIX . '-' . $year . '-(\d+)$/';

        Batch::where('batch_number', 'like', self::PREFIX . '-' . $year . '-%')
            ->pluck('batch_number')
            ->each(function ($bn) use ($pattern, &$max) {
                if (preg_match($pattern, $bn, $m)) {
                    $max = max($max, (int) $m[1]);
                }
            });

        return $max;
    }
}
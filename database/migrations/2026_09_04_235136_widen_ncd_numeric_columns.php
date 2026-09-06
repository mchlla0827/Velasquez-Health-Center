<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * whr, waist, hip, and bmi were defined with a decimal precision too
     * narrow for real-world input (e.g. decimal(3,2) maxes out at 9.99),
     * causing "Out of range value" errors on values as ordinary as 34.
     * Widen them so normal clinical measurements never overflow.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `ncd_assessments` MODIFY `whr` DECIMAL(6,2) NULL");
        DB::statement("ALTER TABLE `ncd_assessments` MODIFY `waist` DECIMAL(6,2) NULL");
        DB::statement("ALTER TABLE `ncd_assessments` MODIFY `hip` DECIMAL(6,2) NULL");
        DB::statement("ALTER TABLE `ncd_assessments` MODIFY `bmi` DECIMAL(6,2) NULL");
    }

    public function down(): void
    {
        // Intentionally not reverted to the narrower precision - that
        // precision was the bug, not a feature worth restoring.
    }
};
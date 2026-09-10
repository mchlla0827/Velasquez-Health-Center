<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Links prescriptions to real Medicine records (so inventory can
     * actually be checked against what was prescribed) and tracks
     * fulfillment status/quantities.
     */
    public function up(): void
    {
        Schema::table('consultation_prescriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('consultation_prescriptions', 'medicine_id')) {
                $table->foreignId('medicine_id')->nullable()->after('consultation_id')
                    ->constrained('medicines')->nullOnDelete();
            }
            if (!Schema::hasColumn('consultation_prescriptions', 'status')) {
                $table->string('status', 30)->default('pending')->after('quantity');
            }
            if (!Schema::hasColumn('consultation_prescriptions', 'quantity_dispensed')) {
                $table->unsignedInteger('quantity_dispensed')->default(0)->after('status');
            }
            if (!Schema::hasColumn('consultation_prescriptions', 'unfulfilled_quantity')) {
                $table->unsignedInteger('unfulfilled_quantity')->default(0)->after('quantity_dispensed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultation_prescriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('medicine_id');
            $table->dropColumn(['status', 'quantity_dispensed', 'unfulfilled_quantity']);
        });
    }
};
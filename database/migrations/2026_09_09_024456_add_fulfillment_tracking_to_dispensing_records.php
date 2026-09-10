<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispensing_records', function (Blueprint $table) {
            if (!Schema::hasColumn('dispensing_records', 'prescription_id')) {
                $table->foreignId('prescription_id')->nullable()->after('medicine_id')
                    ->constrained('consultation_prescriptions')->nullOnDelete();
            }
            if (!Schema::hasColumn('dispensing_records', 'status')) {
                $table->string('status', 30)->default('fully_dispensed')->after('quantity_dispensed');
            }
            if (!Schema::hasColumn('dispensing_records', 'unfulfilled_quantity')) {
                $table->unsignedInteger('unfulfilled_quantity')->default(0)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dispensing_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prescription_id');
            $table->dropColumn(['status', 'unfulfilled_quantity']);
        });
    }
};
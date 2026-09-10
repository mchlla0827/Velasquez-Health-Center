<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_prescriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('consultation_prescriptions', 'fulfillment_outcome')) {
                $table->string('fulfillment_outcome', 30)->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultation_prescriptions', function (Blueprint $table) {
            $table->dropColumn('fulfillment_outcome');
        });
    }
};
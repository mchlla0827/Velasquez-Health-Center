<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispensing_records', function (Blueprint $table) {
            $table->string('status')->default('ACTIVE')->after('dispensed_by');
            $table->timestamp('voided_at')->nullable()->after('status');
            $table->string('voided_by')->nullable()->after('voided_at');
        });
    }

    public function down(): void
    {
        Schema::table('dispensing_records', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'voided_at',
                'voided_by',
            ]);
        });
    }
};
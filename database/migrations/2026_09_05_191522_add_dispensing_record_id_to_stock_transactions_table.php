<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->foreignId('dispensing_record_id')
                ->nullable()
                ->after('medicine_id')
                ->constrained('dispensing_records')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['dispensing_record_id']);
            $table->dropColumn('dispensing_record_id');
        });
    }
};
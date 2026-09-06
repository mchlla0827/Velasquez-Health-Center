<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->integer('patients_affected')->nullable()->change();
            $table->integer('duration')->nullable()->change();
        });

        DB::statement("ALTER TABLE stock_transactions MODIFY type ENUM('IN', 'OUT', 'STOCKOUT_INCIDENT') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stock_transactions MODIFY type ENUM('IN', 'OUT') NOT NULL");

        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->integer('patients_affected')->default(0)->nullable(false)->change();
            $table->integer('duration')->default(1)->nullable(false)->change();
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('stock_transactions', function (Blueprint $table) {
        $table->integer('patients_affected')->default(0)->after('quantity');
        $table->integer('duration')->default(1)->after('patients_affected');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            //
        });
    }
};

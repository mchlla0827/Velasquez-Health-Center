<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            // ✅ MODIFIED: Only add if NOT exists, or just MODIFY it
            if (!Schema::hasColumn('stock_transactions', 'type')) {
                $table->enum('type', ['IN', 'OUT'])->after('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transactions', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
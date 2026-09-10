<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The original stock_out_logs table was manual-entry only.
     * This adds automatic active/resolved lifecycle tracking - going
     * forward, stock-outs are created and resolved automatically based
     * on usable stock crossing zero, and "affected patients" is
     * computed live from dispensing records rather than typed in.
     * Old manual columns are kept (nullable) for historical records.
     */
    public function up(): void
    {
        Schema::table('stock_out_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_out_logs', 'status')) {
                $table->string('status', 20)->default('active')->after('medicine_id');
            }
            if (!Schema::hasColumn('stock_out_logs', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('stock_out_logs', 'usable_stock_at_detection')) {
                $table->integer('usable_stock_at_detection')->default(0)->after('resolved_at');
            }
        });

        Schema::table('stock_out_logs', function (Blueprint $table) {
            $table->unsignedInteger('quantity_needed')->default(0)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('stock_out_logs', function (Blueprint $table) {
            $table->dropColumn(['status', 'resolved_at', 'usable_stock_at_detection']);
        });
    }
};
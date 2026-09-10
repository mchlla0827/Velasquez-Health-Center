<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A stock-out is an availability event ("we needed 20 units and
     * didn't have them"), not a dispensing transaction ("20 units were
     * given to a patient"). It was previously logged as a fake
     * StockTransaction with type='OUT', which silently inflated every
     * usage/consumption metric built on that table. This table gives
     * stock-out incidents their own clean, dedicated record.
     */
    public function up(): void
    {
        Schema::create('stock_out_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->unsignedInteger('quantity_needed');
            $table->unsignedInteger('patients_affected')->default(0);
            $table->unsignedInteger('duration_days')->default(1);
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['medicine_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_out_logs');
    }
};
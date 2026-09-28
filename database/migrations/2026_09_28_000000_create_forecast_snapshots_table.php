<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecast_snapshots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->cascadeOnDelete();

            // Date when the forecast was generated
            $table->date('forecast_date');

            // The future period being forecasted
            $table->date('target_start');
            $table->date('target_end');

            // Forecast information
            $table->unsignedInteger('forecasted_demand');
            $table->unsignedInteger('suggested_reorder_qty')->default(0);
            $table->unsignedInteger('usable_stock')->default(0);

            $table->string('forecast_method')->nullable();
            $table->string('confidence')->nullable();

            $table->integer('moving_average')->nullable();
            $table->decimal('trend_adjustment', 10, 2)->nullable();

            // Filled later when the target period is complete
            $table->unsignedInteger('actual_demand')->nullable();
            $table->integer('error')->nullable();
            $table->unsignedInteger('absolute_error')->nullable();
            $table->decimal('absolute_percentage_error', 10, 2)->nullable();
            $table->timestamp('evaluated_at')->nullable();

            // Human review
            $table->string('review_decision')->nullable();
            $table->unsignedInteger('reviewed_quantity')->nullable();
            $table->text('review_reason')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // One saved forecast per medicine per day
            $table->unique(
                ['medicine_id', 'forecast_date'],
                'forecast_snapshots_medicine_date_unique'
            );

            $table->index(['target_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_snapshots');
    }
};
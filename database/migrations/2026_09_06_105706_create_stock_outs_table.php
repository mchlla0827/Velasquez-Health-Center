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
    Schema::create('stock_outs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
        $table->integer('quantity_needed');
        $table->integer('patients_affected');
        $table->integer('duration');
        $table->timestamp('date');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};

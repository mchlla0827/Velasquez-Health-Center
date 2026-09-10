<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->cascadeOnDelete();
            $table->string('medicine_name');
            $table->string('dosage', 100)->nullable();
            $table->string('frequency', 100)->nullable();
            $table->string('duration', 100)->nullable();
            $table->string('quantity', 50)->nullable();
            $table->string('unit', 50)->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_prescriptions');
    }
};
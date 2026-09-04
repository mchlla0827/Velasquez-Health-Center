<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('consultation_id')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attended_by')->constrained('users');
            $table->enum('consultation_type', ['general', 'ncd_risk_assessment', 'ncd_follow_up', 'follow_up_recheck']);
            $table->dateTime('consultation_date');
            $table->string('reason_for_visit')->nullable();
            $table->text('chief_complaint')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->unsignedSmallInteger('pulse_rate')->nullable();
            $table->unsignedSmallInteger('respiratory_rate')->nullable();
            $table->decimal('oxygen_saturation', 5, 2)->nullable();
            $table->decimal('weight', 6, 2)->nullable();
            $table->decimal('height', 6, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->text('assessment')->nullable();
            $table->text('treatment')->nullable();
            $table->text('medicines')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->json('details')->nullable();
            $table->foreignId('ncd_assessment_id')->nullable()->constrained('ncd_assessments')->nullOnDelete();
            $table->foreignId('previous_consultation_id')->nullable()->constrained('consultations')->nullOnDelete();
            $table->timestamps();
            $table->index(['patient_id', 'consultation_date']);
        });
    }

    public function down(): void { Schema::dropIfExists('consultations'); }
};
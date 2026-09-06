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

            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('attended_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('consultation_type', 30);
            // general | ncd_risk_assessment | ncd_followup | followup_recheck

            $table->date('consultation_date');
            $table->string('reason_for_visit', 255)->nullable();
            $table->text('chief_complaint')->nullable();

            // Vital Signs (shared across all consultation types)
            $table->string('vital_bp', 20)->nullable();
            $table->string('vital_temp', 10)->nullable();
            $table->string('vital_pulse', 10)->nullable();
            $table->string('vital_resp_rate', 10)->nullable();
            $table->string('vital_o2sat', 10)->nullable();
            $table->decimal('vital_weight', 5, 2)->nullable();
            $table->decimal('vital_height', 5, 2)->nullable();
            $table->decimal('vital_bmi', 5, 2)->nullable();

            // Common clinical fields (shown on timeline cards + review screen)
            $table->text('assessment_diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->text('medicines')->nullable();
            $table->text('health_advice')->nullable();
            $table->text('referral')->nullable();
            $table->string('status_classification', 100)->nullable();

            $table->date('follow_up_date')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('related_ncd_assessment_id')->nullable()
                ->constrained('ncd_assessments')->nullOnDelete();
            $table->foreignId('related_consultation_id')->nullable()
                ->constrained('consultations')->nullOnDelete();

            $table->json('details')->nullable();

            $table->timestamps();

            $table->index(['patient_id', 'consultation_date']);
            $table->index('consultation_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
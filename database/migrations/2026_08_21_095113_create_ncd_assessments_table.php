<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ncd_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessed_by')->nullable()->constrained('users');

             $table->string('health_facility')->nullable();
                $table->date('assessment_date')->nullable();
                $table->string('family_no')->nullable();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('id_no')->nullable();
                $table->string('address')->nullable();
                $table->string('barangay')->nullable();
                $table->string('telepono')->nullable();
                $table->date('birthday')->nullable();
                $table->integer('edad')->nullable();
                $table->string('kasarian')->nullable();
                $table->string('estadocivil')->nullable();
                $table->string('relihiyon')->nullable();
                $table->string('educational_attainment')->nullable();


            // === PART II: Past Medical History ===
            $table->boolean('is_diabetic')->default(false);
            $table->string('is_diabetic_year')->nullable();
            $table->string('is_diabetic_meds')->nullable();
            $table->boolean('risk_dm')->default(false);

            $table->boolean('is_hypertensive')->default(false);
            $table->string('is_hypertensive_year')->nullable();
            $table->string('is_hypertensive_meds')->nullable();
            $table->boolean('risk_hpn')->default(false);

            $table->boolean('has_copd')->default(false);
            $table->string('has_copd_year')->nullable();
            $table->string('has_copd_meds')->nullable();
            $table->boolean('risk_copd')->default(false);

            $table->boolean('has_cancer')->default(false);
            $table->string('cancer_site_condition')->nullable();
            $table->string('cancer_year')->nullable();
            $table->string('cancer_meds')->nullable();
            $table->boolean('risk_cancer')->default(false);

            $table->boolean('has_eye_disease')->default(false);
            $table->string('eye_year')->nullable();
            $table->string('eye_meds')->nullable();

            // Chest Pain / Angina Q2.1–2.8
            $table->string('cp1')->nullable();
            $table->string('cp2')->nullable();
            $table->string('cp3')->nullable();
            $table->string('cp4')->nullable();
            $table->string('cp5')->nullable();
            $table->string('cp6')->nullable();
            $table->string('cp7')->nullable();
            $table->string('cp8')->nullable();

            // === PART III: Risk Factors — Nutrition ===
            $table->boolean('r_diet')->default(false);
            $table->boolean('r_salt')->default(false);

            $table->string('diet_gulay')->nullable();
            $table->string('diet_prutas')->nullable();
            $table->string('diet_isda')->nullable();
            $table->string('diet_karne')->nullable();
            $table->string('diet_processed_food')->nullable();
            $table->string('diet_maalat')->nullable();
            $table->string('diet_matatamis')->nullable();
            $table->string('diet_mamantika')->nullable();

            // Alcohol
            $table->string('alc_u')->nullable();
            $table->string('alc_q')->nullable();
            $table->json('alc_t')->nullable();
            $table->string('amt_b')->nullable();
            $table->string('amt_w')->nullable();
            $table->string('amt_s')->nullable();
            $table->string('alc_f')->nullable();
            $table->string('alc_b')->nullable();
            $table->boolean('r_binge')->default(false);

            // === PART IV: Risk Screening ===
            $table->decimal('w', 5, 2)->nullable();
            $table->decimal('h', 5, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->string('bmi_s')->nullable();
            $table->boolean('r_over')->default(false);
            $table->boolean('r_obese')->default(false);

            $table->decimal('waist', 5, 2)->nullable();
            $table->decimal('hip', 5, 2)->nullable();
            $table->decimal('whr', 4, 3)->nullable();
            $table->string('whr_s')->nullable();
            $table->boolean('r_whr')->default(false);

            $table->decimal('fbs', 5, 2)->nullable();
            $table->decimal('vn', 5, 2)->nullable();
            $table->string('fbs_s')->nullable();
            $table->boolean('r_predm')->default(false);

            $table->string('rbs_s')->nullable();
            $table->boolean('s_pol')->default(false);
            $table->boolean('s_wgt')->default(false);
            $table->boolean('r_dm_f')->default(false);

            $table->string('bp_l')->nullable();
            $table->string('bp_r')->nullable();
            $table->string('bp_b')->nullable();
            $table->string('bp_s')->nullable();
            $table->boolean('r_hpn_f')->default(false);

            $table->decimal('chol', 5, 2)->nullable();
            $table->string('ch_s')->nullable();
            $table->boolean('r_chol')->default(false);

            $table->string('pro')->nullable();
            $table->string('ket')->nullable();
            $table->boolean('r_pro')->default(false);

            $table->string('rp')->nullable();
            $table->boolean('r_30')->default(false);

            $table->string('cs')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ncd_assessments');
    }
};
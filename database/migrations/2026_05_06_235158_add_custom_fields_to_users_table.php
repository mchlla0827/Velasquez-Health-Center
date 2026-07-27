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
        Schema::table('patients', function (Blueprint $table) {
            // Part II: Additional Past Med History
            $table->string('has_cancer', 10)->nullable();
            $table->string('cancer_site')->nullable();
            $table->string('has_copd', 10)->nullable();
            $table->string('has_eye_disease', 10)->nullable();
            $table->string('has_chest_pain', 10)->nullable();

            // Part III A: Additional Family History
            $table->string('fam_heart_disease', 10)->nullable();
            $table->string('fam_stroke', 10)->nullable();
            $table->string('fam_cancer', 10)->nullable();
            $table->string('fam_copd', 10)->nullable();
            $table->string('fam_kidney_disease', 10)->nullable();

            // Part III B: Modifiable Lifestyle Risk Factors
            $table->string('risk_nutrition')->nullable();
            $table->string('risk_alcohol')->nullable();
            $table->string('risk_activity')->nullable();
            $table->string('risk_smoking')->nullable();
            $table->string('risk_stress')->nullable();

            // Part IV: Baseline Risk Screening (Vitals)
            $table->string('screen_height', 20)->nullable();
            $table->string('screen_weight', 20)->nullable();
            $table->string('screen_bmi', 20)->nullable();
            $table->string('screen_waist', 20)->nullable();
            $table->string('screen_bp', 30)->nullable();
            $table->string('screen_sugar', 30)->nullable();
            $table->string('screen_cholesterol', 30)->nullable();
            $table->string('screen_urine_protein', 30)->nullable();
            $table->string('screen_urine_ketones', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'has_cancer', 'cancer_site', 'has_copd', 'has_eye_disease', 'has_chest_pain',
                'fam_heart_disease', 'fam_stroke', 'fam_cancer', 'fam_copd', 'fam_kidney_disease',
                'risk_nutrition', 'risk_alcohol', 'risk_activity', 'risk_smoking', 'risk_stress',
                'screen_height', 'screen_weight', 'screen_bmi', 'screen_waist', 'screen_bp', 
                'screen_sugar', 'screen_cholesterol', 'screen_urine_protein', 'screen_urine_ketones'
            ]);
        });
    }
};

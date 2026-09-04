<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The ncd_assessments table was created with ~60 VARCHAR(255) columns.
     * In utf8mb4 that is 1,020 bytes each, which pushes the table against
     * MySQL's hard 65,535-byte row limit and blocks any new columns.
     *
     * Most of those columns only ever hold tiny values ("Yes", "No", "120/80",
     * a year, a short status label). This migration right-sizes them first,
     * which frees roughly 44KB, then adds the columns that were still missing.
     */
    public function up(): void
    {
        // 1. Shrink oversized columns to realistic lengths
        $resize = [
            'cp1' => 5, 'cp2' => 5, 'cp3' => 5, 'cp4' => 5,
            'cp5' => 5, 'cp6' => 5, 'cp7' => 5, 'cp8' => 5,
            'diet_gulay' => 5, 'diet_prutas' => 5, 'diet_isda' => 5,
            'diet_karne' => 5, 'diet_processed_food' => 5,
            'diet_maalat' => 5, 'diet_matatamis' => 5, 'diet_mamantika' => 5,
            'is_diabetic_year' => 10, 'is_hypertensive_year' => 10,
            'has_copd_year' => 10, 'cancer_year' => 10, 'eye_year' => 10,
            'alc_u' => 10, 'alc_q' => 30, 'amt_b' => 30, 'amt_w' => 30,
            'amt_s' => 30, 'alc_f' => 30, 'alc_b' => 20,
            'bmi_s' => 20, 'whr_s' => 20, 'fbs_s' => 20, 'rbs_s' => 20,
            'bp_s' => 20, 'ch_s' => 30,
            'pro' => 5, 'ket' => 5, 'rp' => 15, 'cs' => 10,
            'bp_l' => 15, 'bp_r' => 15, 'bp_b' => 15,
            'kasarian' => 10, 'estadocivil' => 20, 'telepono' => 20,
            'id_no' => 30, 'family_no' => 30,
        ];

        foreach ($resize as $column => $length) {
            if (Schema::hasColumn('ncd_assessments', $column)) {
                DB::statement("ALTER TABLE `ncd_assessments` MODIFY `{$column}` VARCHAR({$length}) NULL");
            }
        }

        // 2. Add the columns that are still missing
        $strings = [
            'occupation' => 100,
            'designation' => 100,
            'fam_other' => 150,
            'has_exercise' => 20,
            'exercise_type' => 40,
            'smoke_status' => 20,
            'smoke_sticks_per_day' => 20,
            'smoke_quit_duration' => 30,
            'smoke_100_sticks' => 5,
            'smoke_exposed' => 5,
            'stress_frequent' => 5,
            'stress_cause' => 150,
            'stress_affects_life' => 5,
        ];

        $booleans = [
            'fam_hypertension', 'fam_heart_disease', 'fam_stroke', 'fam_diabetes',
            'fam_cancer', 'fam_lung_disease', 'fam_kidney_disease',
            'risk_activity', 'risk_smoking_history', 'risk_smoker', 'risk_stress',
            's_pdp', 's_pph', 'r_hpn_pre', 'r_ket',
        ];

        Schema::table('ncd_assessments', function (Blueprint $table) use ($strings, $booleans) {
            foreach ($strings as $col => $len) {
                if (!Schema::hasColumn('ncd_assessments', $col)) {
                    $table->string($col, $len)->nullable();
                }
            }

            foreach ($booleans as $col) {
                if (!Schema::hasColumn('ncd_assessments', $col)) {
                    $table->boolean($col)->default(false);
                }
            }

            if (!Schema::hasColumn('ncd_assessments', 'sign_date')) {
                $table->date('sign_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        $columns = [
            'occupation', 'designation', 'fam_other', 'has_exercise', 'exercise_type',
            'smoke_status', 'smoke_sticks_per_day', 'smoke_quit_duration',
            'smoke_100_sticks', 'smoke_exposed', 'stress_frequent', 'stress_cause',
            'stress_affects_life', 'fam_hypertension', 'fam_heart_disease', 'fam_stroke',
            'fam_diabetes', 'fam_cancer', 'fam_lung_disease', 'fam_kidney_disease',
            'risk_activity', 'risk_smoking_history', 'risk_smoker', 'risk_stress',
            's_pdp', 's_pph', 'r_hpn_pre', 'r_ket', 'sign_date',
        ];

        Schema::table('ncd_assessments', function (Blueprint $table) use ($columns) {
            foreach ($columns as $col) {
                if (Schema::hasColumn('ncd_assessments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
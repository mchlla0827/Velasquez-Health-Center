<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id', 50)->unique();
            
            // --- BASIC INFORMATION ---
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            
            $table->text('mother_last')->nullable();
            $table->text('mother_first')->nullable();
            $table->text('mother_middle')->nullable();
            $table->text('father_last')->nullable();
            $table->text('father_first')->nullable();
            $table->text('father_middle')->nullable();
            $table->text('address');
            
            $table->string('barangay', 100);
            $table->string('family_number', 50)->nullable();
            $table->string('contact_number', 50);
            $table->text('email')->nullable();
            
            $table->date('dob');
            $table->string('age', 10)->nullable();
            $table->string('pob', 100)->nullable();
            $table->string('gender', 20);
            $table->string('civil_status', 50)->nullable();
            $table->string('osca_pwd_no', 50)->nullable();
            $table->string('four_ps_no', 50)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('educational_attainment', 100)->nullable();
            
            // --- PHILHEALTH ---
            $table->string('philhealth', 50)->default('none');
            $table->string('philhealth_no_member', 50)->nullable();
            $table->string('philhealth_no_dependent', 50)->nullable();
            $table->text('philhealth_member_name')->nullable();
            $table->date('philhealth_member_dob')->nullable();

            // --- MEDICAL HISTORY ---
            $table->string('is_hypertensive', 10)->nullable();
            $table->string('is_diabetic', 10)->nullable();
            $table->string('has_asthma', 10)->nullable();
            $table->string('has_heart_disease', 10)->nullable();
            $table->string('has_tb_history', 10)->nullable();
            $table->text('other_conditions')->nullable();
            $table->text('food_allergies')->nullable();
            $table->text('drug_allergies')->nullable();
            $table->text('other_allergies')->nullable();
            $table->string('fam_hypertension', 10)->nullable();
            $table->string('fam_diabetes', 10)->nullable();
            $table->text('fam_other')->nullable();

            // --- TRACKING TOGGLES ---
            $table->string('tracking_immunization', 10)->nullable()->default('No');
            $table->string('tracking_maternal', 10)->nullable()->default('No');

            // --- IMMUNIZATION TRACKING FIELDS (ALL TEXT) ---
            $table->text('vac_bcg')->nullable();
            $table->text('vac_hepa')->nullable();
            $table->text('vac_penta1')->nullable();
            $table->text('vac_opv1')->nullable();
            $table->text('vac_pcv1')->nullable();
            $table->text('vac_ipv1')->nullable();
            $table->text('vac_ipv2')->nullable();
            $table->text('vac_penta2')->nullable();
            $table->text('vac_opv2')->nullable();
            $table->text('vac_pcv2')->nullable();
            $table->text('vac_penta3')->nullable();
            $table->text('vac_opv3')->nullable();
            $table->text('vac_pcv3')->nullable();
            $table->text('vac_mr1')->nullable();
            $table->text('vac_mmr1')->nullable();
            $table->text('vac_mmr2')->nullable();
            $table->text('vac_hpv1')->nullable();
            $table->text('vac_hpv2')->nullable();
            $table->text('vac_flu')->nullable();
            $table->text('vac_pneumo')->nullable();
            $table->text('vac_td')->nullable();

            // --- MATERNAL & CHILD HEALTH FIELDS (ALL TEXT) ---
            $table->text('mat_nbs')->nullable();
            $table->text('mat_nbs_date')->nullable();
            $table->text('mat_nbs_result')->nullable();
            $table->text('mat_hearing')->nullable();
            $table->text('mat_hearing_date')->nullable();
            $table->text('mat_hearing_result')->nullable();
            $table->text('mat_birth_order')->nullable();
            $table->text('mat_birth_length')->nullable();
            $table->text('mat_birth_weight')->nullable();
            
            $table->text('mat_delivery_type')->nullable();
            $table->text('mat_feeding_type')->nullable();
            $table->text('mat_attendant')->nullable();
            $table->text('mat_delivery_place')->nullable();
            
            $table->text('mat_vit_a_dose')->nullable();
            $table->text('mat_vit_a_date')->nullable();
            $table->text('mat_deworming_1')->nullable();
            $table->text('mat_deworming_2')->nullable();
            
            $table->text('ob_g')->nullable();
            $table->text('ob_p_t')->nullable();
            $table->text('ob_p_p')->nullable();
            $table->text('ob_p_a')->nullable();
            $table->text('ob_p_l')->nullable();
            $table->text('ob_menarche')->nullable();
            $table->text('ob_pmp')->nullable();
            $table->text('ob_lmp')->nullable();
            $table->text('ob_edc')->nullable();
            $table->text('ob_tt_status')->nullable();
            
            $table->text('ob_td1')->nullable();
            $table->text('ob_td2')->nullable();
            $table->text('ob_td3')->nullable();
            $table->text('ob_td4')->nullable();
            $table->text('ob_td5')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
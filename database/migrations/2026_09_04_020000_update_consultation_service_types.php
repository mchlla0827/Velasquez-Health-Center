<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE consultations MODIFY consultation_type ENUM('general', 'ncd_risk_assessment', 'ncd_follow_up', 'follow_up_recheck', 'consultation', 'maintenance', 'ecg', 'ultrasound', 'dental', 'laboratory', 'counseling_adolescent') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE consultations MODIFY consultation_type ENUM('general', 'ncd_risk_assessment', 'ncd_follow_up', 'follow_up_recheck') NOT NULL");
    }
};
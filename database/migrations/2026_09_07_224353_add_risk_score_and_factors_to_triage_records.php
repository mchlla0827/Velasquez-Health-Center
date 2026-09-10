<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('triage_records', function (Blueprint $table) {
            if (!Schema::hasColumn('triage_records', 'risk_score')) {
                $table->unsignedInteger('risk_score')->nullable()->after('risk_level');
            }
            if (!Schema::hasColumn('triage_records', 'risk_factors')) {
                $table->json('risk_factors')->nullable()->after('risk_score');
            }
        });
    }

    public function down(): void
    {
        Schema::table('triage_records', function (Blueprint $table) {
            $table->dropColumn(['risk_score', 'risk_factors']);
        });
    }
};
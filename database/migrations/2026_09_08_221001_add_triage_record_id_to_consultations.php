<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            if (!Schema::hasColumn('consultations', 'triage_record_id')) {
                $table->foreignId('triage_record_id')->nullable()
                    ->after('patient_id')
                    ->constrained('triage_records')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('triage_record_id');
        });
    }
};
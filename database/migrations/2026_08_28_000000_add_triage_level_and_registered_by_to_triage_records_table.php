<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('triage_records', function (Blueprint $table) {
            $table->string('triage_level')->nullable()->after('risk_level');
            $table->foreignId('registered_by')
                ->nullable()
                ->after('triage_level')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('triage_records', function (Blueprint $table) {
            $table->dropForeign(['registered_by']);
            $table->dropColumn(['triage_level', 'registered_by']);
        });
    }
};

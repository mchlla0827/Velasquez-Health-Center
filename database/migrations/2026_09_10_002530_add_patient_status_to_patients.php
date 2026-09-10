<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'patient_status')) {
                $table->string('patient_status', 20)->default('active')->after('id');
            }
            if (!Schema::hasColumn('patients', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('patient_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['patient_status', 'archived_at']);
        });
    }
};
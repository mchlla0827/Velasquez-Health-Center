<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'role')) {
                $table->string('role')->nullable()->after('user_name');
            }
            if (!Schema::hasColumn('activity_logs', 'module')) {
                $table->string('module')->nullable()->after('action');
            }
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['role', 'module']);
        });
    }
};
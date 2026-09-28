<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'contact_number')) {
                $table->string('contact_number', 50)->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'is_physician_in_charge')) {
                $table->boolean('is_physician_in_charge')->default(false)->after('contact_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['contact_number', 'is_physician_in_charge']);
        });
    }
};
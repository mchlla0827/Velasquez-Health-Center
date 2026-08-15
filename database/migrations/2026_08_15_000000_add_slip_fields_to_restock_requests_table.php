<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restock_requests', function (Blueprint $table) {
            $table->string('responsibility_center_code')->nullable()->after('reason');
            $table->string('ris_number')->nullable()->after('responsibility_center_code');
            $table->date('date_prepared')->nullable()->after('ris_number');
            $table->string('unit')->nullable()->after('date_prepared');
            $table->string('batch')->nullable()->after('unit');
            $table->date('expiry')->nullable()->after('batch');
        });
    }

    public function down(): void
    {
        Schema::table('restock_requests', function (Blueprint $table) {
            $table->dropColumn([
                'responsibility_center_code',
                'ris_number',
                'date_prepared',
                'unit',
                'batch',
                'expiry',
            ]);
        });
    }
};

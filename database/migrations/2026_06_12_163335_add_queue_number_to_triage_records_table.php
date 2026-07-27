<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('triage_records', function (Blueprint $table) {
            $table->integer('queue_number')->default(0)->after('id');
        });
    }

    public function down()
    {
        Schema::table('triage_records', function (Blueprint $table) {
            $table->dropColumn('queue_number');
        });
    }
};
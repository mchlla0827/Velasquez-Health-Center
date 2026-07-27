<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
   {
       Schema::table('patients', function (Blueprint $table) {
           $table->boolean('in_queue')->default(false);
           $table->string('risk_level')->default('low'); // low, medium, high
           $table->string('reason')->nullable();
       });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            //
        });
    }
};

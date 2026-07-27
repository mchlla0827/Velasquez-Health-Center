<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('dispensing_records', function (Blueprint $table) {
        $table->id();

        // ✅ NEW: Link to Patient Record Number
        $table->string('patient_ptn'); 

        $table->string('family_no');
        $table->string('barangay');
        $table->date('dispense_date');
        $table->string('patient_name');
        $table->integer('age');
        $table->enum('sex', ['Male','Female']);
        $table->text('address');
        $table->string('philhealth_no')->nullable();
        $table->text('diagnosis');

        $table->foreignId('medicine_id')->constrained();
        $table->integer('quantity_dispensed');
        $table->string('unit');
        $table->string('dispensed_by');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispensing_records');
    }
};

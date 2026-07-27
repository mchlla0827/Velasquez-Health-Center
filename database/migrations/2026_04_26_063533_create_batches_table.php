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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();

            // link to medicines table
            $table->foreignId('medicine_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('batch_number');
            $table->date('expiry_date');
            $table->integer('quantity');

            $table->text('remarks')->nullable();
            $table->string('tracking_immunization')->default('No'); 
            $table->string('tracking_maternal')->default('No');

            $table->timestamps();
            $table->unique(['medicine_id', 'batch_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};

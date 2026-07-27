<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('dosage_strength')->nullable();
            $table->string('unit')->nullable();
            
            // ✅ TAMA NA PANGALAN: threshold (hindi low_stock_threshold)
            $table->integer('threshold')->default(10); 
            
            // ✅ IDINAGDAG ANG MGA KULANG NA COLUMN GAMIT NG SYSTEM
            $table->integer('stock')->default(0); 
            $table->integer('current_stock')->default(0); 
            
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicines');
    }
};
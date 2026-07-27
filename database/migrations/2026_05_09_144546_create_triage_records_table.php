<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('triage_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade'); // Links to your patients table
            $table->string('service_type')->nullable();
            $table->string('risk_level')->default('Low');
            $table->string('temp')->nullable();
            $table->string('bp')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->text('symptoms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triage_records');
    }
};
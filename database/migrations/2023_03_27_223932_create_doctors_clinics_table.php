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
        Schema::create('doctors_clinics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('doctor_id')->unsigned();
            $table->unsignedBiginteger('clinic_id')->unsigned();
        });

        Schema::table('doctors_clinics', function (Blueprint $table) {
            $table->foreign('doctor_id')->references('id')
                ->on('doctors')->onDelete('cascade');
            $table->foreign('clinic_id')->references('id')
                ->on('clinics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors_clinics');
    }
};

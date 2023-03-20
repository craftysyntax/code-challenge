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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('clinic_id');
        });

        Schema::dropIfExists('clinics');
    }
};

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
        Schema::create('application_form', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institutionId')->nullable();
            $table->foreignId('programId')->nullable();
            $table->string('type', 255)->nullable();
            $table->string('academicYear', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->boolean('isApproved')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_form');
    }
};

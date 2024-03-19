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
        Schema::create('institution_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institutionId')->nullable();
            $table->foreignId('programId')->nullable();
            $table->boolean('isActive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_program');
    }
};

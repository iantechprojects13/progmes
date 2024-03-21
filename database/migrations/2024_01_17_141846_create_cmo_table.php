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
        Schema::create('cmo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplineId')->nullable();
            $table->foreignId('programId')->nullable();
            $table->foreignId('createdBy')->nullable();
            $table->string('number')->nullable();
            $table->string('series')->nullable();
            $table->string('version')->nullable();
            $table->string('status')->nullable();
            $table->boolean('isActive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cmo');
    }
};

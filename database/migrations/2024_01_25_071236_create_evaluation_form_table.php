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
        Schema::create('evaluation_form', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institutionProgramId')->nullable();
            $table->foreignId('disciplineId')->nullable();
            $table->foreignId('cmoId')->nullable();
            $table->string('effectivity')->nullable();
            $table->date('evaluationDate')->nullable();
            $table->date('submissionDate')->nullable();
            $table->string('programHead')->nullable();
            $table->string('evaluator')->nullable();
            $table->string('conforme')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_form');
    }
};

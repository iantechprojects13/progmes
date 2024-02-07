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
        Schema::create('evaluation_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluationFormId')->nullable();
            $table->foreignId('criteriaId')->nullable();
            $table->string('actualSituation', 5000)->nullable();
            $table->string('findings', 5000)->nullable();
            $table->string('recommendations', 5000)->nullable();
            $table->string('selfEvaluationStatus')->nullable();
            $table->string('evaluationStatus')->nullable();
            $table->string('evidenceId')->nullable();
            $table->string('evidenceLink')->nullable();
            $table->boolean('canUploadEvidence')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_item');
    }
};

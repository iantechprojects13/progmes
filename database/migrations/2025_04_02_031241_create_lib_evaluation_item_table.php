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
        Schema::create('lib_evaluation_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('libEvaluationFormId')->nullable();
            $table->foreignId('libCriteriaId')->nullable();
            $table->string('actualSituation', 5000)->nullable();
            $table->string('findings', 5000)->nullable();
            $table->string('recommendations', 5000)->nullable();
            $table->string('selfEvaluationStatus')->nullable();
            $table->string('evaluationStatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_evaluation_item');
    }
};

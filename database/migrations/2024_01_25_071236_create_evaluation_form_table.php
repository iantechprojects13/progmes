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
            $table->date('archivedDate')->nullable();
            $table->string('conforme', 255)->nullable();
            $table->string('conformeTitle', 255)->nullable();
            $table->string('evaluatedBy', 255)->nullable();
            $table->string('evaluatedByTitle', 255)->nullable();
            $table->string('reviewedBy', 255)->nullable();
            $table->string('reviewedByTitle', 255)->nullable();
            $table->string('notedBy', 255)->nullable();
            $table->string('notedByTitle', 255)->nullable();
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

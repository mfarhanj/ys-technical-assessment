<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->text('question_text');
            $table->string('type'); // multiple_choice | open_text
            $table->unsignedSmallInteger('order')->default(0);
            $table->unsignedInteger('marks')->default(1);
            // For multiple_choice: options and correct answer stored as JSON
            $table->json('options')->nullable(); // ['A' => '...', 'B' => '...', ...]
            $table->string('correct_answer')->nullable(); // 'A' or answer key for MC; null for open_text (manual marking)
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

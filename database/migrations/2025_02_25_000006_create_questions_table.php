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
            $table->string('type');
            $table->unsignedSmallInteger('order')->default(0);
            $table->unsignedInteger('marks')->default(1);
            $table->json('options')->nullable();
            $table->string('correct_answer')->nullable();
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

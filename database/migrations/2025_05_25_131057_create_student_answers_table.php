<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_quiz_attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('answer_option_id')->nullable();
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->unsignedInteger('points_awarded')->nullable();
            $table->timestamps();

            $table->foreign('student_quiz_attempt_id', 'sqa_id_foreign')->references('id')->on('student_quiz_attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('answer_option_id')->references('id')->on('answer_options')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
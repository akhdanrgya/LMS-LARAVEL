<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->between(1, 5);
            $table->text('review')->nullable();
            $table->timestamps();
            
            $table->unique(['course_id', 'user_id']); // Satu user hanya bisa rating sekali per course
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_ratings');
    }
};
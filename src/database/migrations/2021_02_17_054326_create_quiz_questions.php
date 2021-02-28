<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // quiz_question
        // id | code | animal | video | question | options
        // 1  | house-01 | horse | http://google.. | choose behavior and its corresponding interpretations | 
        // 2  | cat-01 | cat | http://google.. | choose behavior and its corresponding interpretations | 
       
        // INSERT INTO quiz_questions(code, video, question)
        // VALUES ('horse001', 'https://www.youtube.com/watch?v=8bgnG4Ni9Jo', 'Please indicate behaviors and interpretations')

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('animal');
            $table->string('video')->nullable();
            $table->string('question')->nullable();
            $table->timestamps();
            $table->jsonb('options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('quiz_questions');
        Schema::enableForeignKeyConstraints();
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttemptQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // attempt_question
        // id | attempt_id | quiz_question_id | datetime

        Schema::create('attempt_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('quiz_question_id');
            $table->timestamps();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('attempt_id')->references('id')->on('attempts')->onDelete('cascade');
            $table->foreign('quiz_question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
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
        Schema::dropIfExists('attempt_questions');
        Schema::enableForeignKeyConstraints();
    }
}

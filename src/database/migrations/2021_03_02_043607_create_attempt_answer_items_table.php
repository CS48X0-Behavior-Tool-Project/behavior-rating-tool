<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttemptAnswerItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempt_answer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_quiz_id');
            $table->jsonb('behavior_answers')->nullable();          // store a list of answers
            $table->jsonb('interpretation_answers')->nullable();    // store a list of answers
            $table->timestamps();
            $table->jsonb('options')->nullable();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('attempt_quiz_id')->references('id')->on('attempt_quizzes')->onDelete('cascade');
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
        Schema::dropIfExists('attempt_answer_items');
        Schema::enableForeignKeyConstraints();
    }
}
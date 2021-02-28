<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestionOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // quiz_question_option
        // id | quiz_question_id | type | title | marking_scheme | is_solution | options
        // 1 | 1 | behavior | kicking | 1 | t |
        // 2 | 1 | behavior | smiling | 1 | f | 
        // 3 | 1 | interpretation | angry | 1 | t | 
        // 4 | 1 | interpretation | happy | 1 | f | 

        Schema::create('quiz_question_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_question_id');
            $table->string('type');
            $table->string('title');
            $table->integer('marking_scheme')->nullable();
            $table->boolean('is_solution')->default(0);
            $table->timestamps();
            $table->jsonb('options')->nullable();

            //FOREIGN KEY CONSTRAINTS
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
        Schema::dropIfExists('quiz_question_options');
        Schema::enableForeignKeyConstraints();
    }
}

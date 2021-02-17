<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentQuizzes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // student_quiz
        // id | student_id | attemt_id | scores | options

        Schema::create('student_quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');   // users -> id
            $table->unsignedBigInteger('attemt_id');   
            $table->integer('scores')->nullable();   
            $table->timestamps();
            $table->jsonb('options')->nullable();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('attemt_id')->references('id')->on('attempts')->onDelete('cascade');
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
        Schema::dropIfExists('student_quizzes');
        Schema::enableForeignKeyConstraints();
    }
}

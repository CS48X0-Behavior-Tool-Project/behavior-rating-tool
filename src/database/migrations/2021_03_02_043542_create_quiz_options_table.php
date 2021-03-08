<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->string('type');
            $table->string('title');
            $table->integer('marking_scheme')->nullable();
            $table->boolean('is_solution')->default(0);
            $table->timestamps();
            $table->jsonb('options')->nullable();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
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
        Schema::dropIfExists('quiz_options');
        Schema::enableForeignKeyConstraints();
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttempts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // attempt
        // id | student_id | datetime | options
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');   // users -> id
            $table->timestamps();
            $table->jsonb('options')->nullable();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('attempts');
        Schema::enableForeignKeyConstraints();
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('degree');
            $table->string('program');
            $table->string('board');
            $table->string('institute');
            $table->tinyInteger('student_here'); 
            $table->string('year')->nullable(); // student_here == 0 ? graduation_year : started_year
            $table->string('month')->nullable(); // student_here == 0 ? graduation_month : started_month
            $table->string('marks_unit')->nullable(); 
            $table->string('marks')->nullable();
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('education');
    }
}

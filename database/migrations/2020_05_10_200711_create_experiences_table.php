<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('organization_name');
            // $table->string('organization_nature');
            $table->string('job_location');
            $table->string('job_title');
            $table->integer('job_category_id'); 
            $table->tinyInteger('working_here'); 
            $table->string('start_year')->nullable();
            $table->string('start_month')->nullable();
            $table->string('end_year')->nullable();
            $table->string('end_month')->nullable();
            $table->text('duties_responsibilities')->nullable(); 
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
        Schema::dropIfExists('experiences');
    }
}

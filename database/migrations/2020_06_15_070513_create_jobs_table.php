<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('job_title');
            $table->string('slug');
            $table->string('job_category_id');
            $table->string('skill_ids');
            $table->integer('no_of_vacancy');
            $table->string('job_level');                // looking_for in jobseerker job preferences
            $table->string('employment_type')->nullable();
            $table->dateTime('deadline');
            $table->string('location_id');
            $table->tinyInteger('salary_type')->nullable();
            $table->string('min_salary')->nullable();
            $table->string('max_salary')->nullable();
            $table->string('image')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('messenger_link')->nullable();
            $table->string('viber_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->tinyInteger('education_level')->nullable();
            $table->tinyInteger('experience_type')->nullable();
            $table->string('experience_year')->nullable();
            $table->text('job_description')->nullable();
            $table->text('benefits')->nullable();
            $table->tinyInteger('display')->default(0);
            $table->tinyInteger('featured')->default(0);
            $table->integer('views')->default(0);
            $table->integer('applied')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}

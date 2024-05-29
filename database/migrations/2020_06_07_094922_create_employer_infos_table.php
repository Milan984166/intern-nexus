<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployerInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employer_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('organization_name');
            $table->string('slug');
            $table->string('email');
            $table->string('address');
            $table->string('phone');
            $table->string('image')->nullable();
            $table->string('category_id');
            $table->string('ownership_type')->nullable();
            $table->string('organization_size')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->text('about');
            $table->string('cp_name')->nullable();
            $table->string('cp_email')->nullable();
            $table->string('cp_designation')->nullable();
            $table->string('cp_contact')->nullable();
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
        Schema::dropIfExists('employer_infos');
    }
}

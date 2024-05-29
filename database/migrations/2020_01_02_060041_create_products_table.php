<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->integer('order_item');
            $table->string('image')->nullable();
            $table->text('categories');
            $table->string('sku');
            $table->text('barrels');
            $table->tinyInteger('display');
            $table->tinyInteger('featured');
            $table->tinyInteger('stock')->default('0');
            $table->integer('originalPrice');
            $table->integer('discountedPrice');
            $table->text('short_content')->nullable();
            $table->longText('long_content')->nullable();
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

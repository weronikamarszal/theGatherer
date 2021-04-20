<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValueStringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('value_strings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('object_id')->unsigned();
            $table->foreign('object_id')->references('id')->on('objects');
            $table->integer('attribute_id')->unsigned();
            $table->foreign('attribute_id')->references('id')->on('object_attributes');
            $table->string('value','255');
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
        Schema::dropIfExists('value_strings');
    }
}

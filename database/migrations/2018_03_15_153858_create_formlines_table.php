<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formlines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('formhub_id');
            $table->string('column_name');
            $table->integer('component_type')->nullable();
            $table->integer('data_type')->nullable();
            $table->integer('nullable')->nullable();
            //$table->integer('language_select_id')->nullable();
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
        Schema::dropIfExists('formlines');
    }
}

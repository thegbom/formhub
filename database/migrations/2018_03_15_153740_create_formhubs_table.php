<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormhubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formhubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_name');
            $table->string('form_name');
            $table->string('post_url')->nullable();
            $table->string('get_url')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('default_language');//no_nb
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
        Schema::dropIfExists('formhubs');
    }
}

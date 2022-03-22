<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelTwosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_two', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('level_one_id');
            //$table->foreign('level_one_id')->references('id')->on('level_one')->onDelete('cascade');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('level_two');
        Schema::enableForeignKeyConstraints();
    }
}

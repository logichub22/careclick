<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelThreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_three', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('level_two_id');
            //$table->foreign('level_two_id')->references('id')->on('level_two')->onDelete('cascade');
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
        Schema::dropIfExists('level_three');
        Schema::enableForeignKeyConstraints();
    }
}

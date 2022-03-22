<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministrativeRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrative_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id');
            //$table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->string('level_one');
            $table->string('level_two');
            $table->string('level_three')->nullable();
            $table->string('level_four')->nullable();
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
        Schema::dropIfExists('administrative_regions');
        Schema::enableForeignKeyConstraints();
    }
}

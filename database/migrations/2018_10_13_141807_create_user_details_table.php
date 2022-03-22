<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('org_id')->nullable();
            $table->integer('gender')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('marital_status')->nullable();
            $table->integer('doc_type');
            $table->string('doc_no');
            $table->date('dob')->nullable();
            $table->integer('country')->nullable();
            $table->integer('residence')->nullable();
            $table->string('occupation')->nullable();
            $table->integer('income')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('address')->nullable();
            //$table->string('identification_document')->nullable();
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
        Schema::dropIfExists('user_details');
        Schema::enableForeignKeyConstraints();
    }
}

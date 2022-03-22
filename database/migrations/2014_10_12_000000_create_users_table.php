<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('other_names', 45);
            $table->string('customer_username')->nullable();
            $table->string('email', 45)->unique();
            $table->string('msisdn')->unique()->nullable();
            // $table->string('pin')->default('0000');
            $table->string('account_no')->nullable();
            $table->string('password');
            $table->boolean('status')->default(0);
            $table->boolean('verified')->default(0);
            $table->string('token', 65)->nullable();
            $table->string('avatar')->default('avatar.png');
            $table->string('identification_document')->nullable();
            $table->boolean('account_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

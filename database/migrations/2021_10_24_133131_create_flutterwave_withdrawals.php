<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlutterwaveWithdrawals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flutterwave_withdrawals', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->integer('fw_id')->comment('Flutterwave transaction id');
            $table->integer('user_id')->nullable();
            $table->integer('org_id')->nullable();
            $table->string('account_name', 50);
            $table->string('account_number', 25);
            $table->string('bank', 10);
            $table->string('currency', 10);
            $table->float('fee', 20, 2)->default(0.00);
            $table->string('reference', 50);
            $table->string('narration')->nullable();
            $table->text('meta')->nullable();
            $table->string('status', 50);
            $table->string('message');
            $table->float('amount');
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
        Schema::table('flutterwave_withdrawals', function (Blueprint $table) {
            //
        });
    }
}

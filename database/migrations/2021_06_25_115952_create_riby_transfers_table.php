<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRibyTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riby_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fw_id')->comment('Flutterwave transaction id');
            $table->string('account_no', 20);
            $table->string('bank_code', 10);
            $table->string('currency', 10);
            $table->float('amount', 20, 2);
            $table->float('fee', 20, 2)->default(0.00);
            $table->string('reference');
            $table->string('narration')->nullable();
            $table->text('meta')->nullable();
            $table->integer('loan_request_id');
            $table->string('status', 50);
            $table->string('message');
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
        Schema::dropIfExists('riby_transfers');
    }
}

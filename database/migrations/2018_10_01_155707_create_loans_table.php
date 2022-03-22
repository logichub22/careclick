<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('loan_title')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('org_id')->nullable();
            $table->integer('loan_package_id');
            $table->float('amount', 20, 2);
            $table->string('currency')->default("NGN");
            $table->integer('borrower_credit_score');
            $table->integer('status')->comment('0-pending\n1-approved\n2-declined\n3-paid\n4-defaulted')->default(0);
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
        Schema::dropIfExists('loans');
    }
}

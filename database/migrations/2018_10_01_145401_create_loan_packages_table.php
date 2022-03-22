<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->string('repayment_plan');
            $table->integer('min_credit_score');
            // $table->integer('max_credit_score');
            $table->float('min_amount', 20, 2);
            $table->float('max_amount', 20, 2);
            $table->float('interest_rate', 20, 2);
            $table->string('currency')->default("NGN");
            $table->boolean('insured')->default(false);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('loan_packages');
    }
}

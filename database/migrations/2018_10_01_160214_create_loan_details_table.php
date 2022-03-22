<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loan_id')->unsigned();
            $table->string('package_name');
            $table->float('principal_due', 20, 2);
            // $table->float('principal_paid', 20, 2)->default(0);
            // $table->float('principal_balance', 20, 2);
            $table->string('interest_charge_frequency');
            $table->float('interest_due', 20, 2);
            // $table->float('interest_paid', 20, 2)->default(0);
            // $table->float('interest_balance', 20, 2)->default(0);
            $table->float('amount_payable', 20, 2);
            $table->float('balance', 20, 2);
            $table->float('penalty_due', 20, 2)->default(0);
            $table->float('charge_per_installment', 20, 2);
            $table->date('next_payment_date')->nullable();
            $table->date('payback_date');
            $table->string('no_of_installments');
            $table->text('loan_schedule')->nullable();
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
        Schema::dropIfExists('loan_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCreditScoreDatatypeAndLoanSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            // $table->float('borrower_credit_score', 10, 2)->change();
        });
        
        Schema::table('loan_details', function (Blueprint $table) {
            // $table->text('loan_schedule')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            // $table->integer('borrower_credit_score')->change();
        });
        
        Schema::table('loan_details', function (Blueprint $table) {
            // $table->dropColumn('loan_schedule');
        });
    }
}

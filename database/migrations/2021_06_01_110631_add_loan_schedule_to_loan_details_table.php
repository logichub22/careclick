<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoanScheduleToLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('loan_details', 'loan_schedule')) {
            Schema::table('loan_details', function (Blueprint $table) {
                $table->text('loan_schedule')->nullable()->after('no_of_installments');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('loan_details', 'loan_schedule')) {
            Schema::table('loan_details', function (Blueprint $table) {
                $table->dropColumn('loan_schedule');
            });
        }
    }
}

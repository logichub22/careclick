<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserDataToRibyLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('riby_loans', function (Blueprint $table) {
            $table->string('applicant_name')->after('application_id');
            $table->string('applicant_email')->nullable()->after('applicant_name');
            $table->string('applicant_phone_number')->nullable()->after('applicant_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('riby_loans', function (Blueprint $table) {
            $table->dropColumn(['applicant_name', 'applicant_email', 'applicant_phone_number']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalStageToRibyLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('riby_loans', function (Blueprint $table) {
            $table->integer('approval_stage')->default(0)->after('package_name');
            $table->integer('first_approval_by')->nullable()->after('approval_stage');
            $table->string('first_approval_time')->nullable()->after('first_approval_by');
            $table->integer('second_approval_by')->nullable()->after('first_approval_time');
            $table->string('second_approval_time')->nullable()->after('second_approval_by');
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
            $table->dropColumn(['approval_stage', 'first_approval_by', 'first_approval_time', 'second_approval_by', 'second_approval_time']);
        });
    }
}

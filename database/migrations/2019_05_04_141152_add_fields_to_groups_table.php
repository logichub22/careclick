<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('group_certificate')->nullable()->after('status');
            $table->boolean('bank')->default(false)->after('group_certificate');
            $table->string('bank_name')->nullable()->after('bank');
            $table->string('bank_branch')->nullable()->after('bank_name');
            $table->string('level_one')->nullable()->after('bank_branch');
            $table->string('level_two')->nullable()->after('level_one');
            $table->string('level_three')->nullable()->after('level_two');
            $table->string('level_four')->nullable()->after('level_three');
            $table->string('group_number')->nullable()->after('level_four');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['group_certificate', 'bank', 'bank_name', 'bank_branch', 'level_one', 'level_two', 'level_three', 'level_four', 'group_number']);
        });
    }
}

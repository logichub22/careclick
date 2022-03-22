<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meeting_name');
            $table->integer('group_id')->unsigned();
            $table->date('meeting_date');
            $table->string('venue');
            $table->text('meeting_minutes')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('group_meetings');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->unsigned()->nullable();
            $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->string('name', 255)->nullable();
            $table->string('domain', 255)->nullable();
            $table->integer('country')->unsigned()->nullable();
            $table->string('address', 255)->nullable();
            $table->string('org_email')->unique()->nullable();
            $table->string('org_msisdn')->unique()->nullable();
            $table->integer('is_financial')->default(1);
            $table->integer('project_id')->unsigned()->nullable();
            $table->string('permit_file')->nullable();
            $table->string('tax_certificate')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('organization_details');
        Schema::enableForeignKeyConstraints();
    }
}

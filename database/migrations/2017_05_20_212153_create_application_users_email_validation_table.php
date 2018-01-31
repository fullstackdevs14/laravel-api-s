<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationUsersEmailValidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_users_email_validation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('applicationUser_id')->unsigned();
            $table->foreign('applicationUser_id')
                ->references('id')
                ->on('application_users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('token');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_users_email_validation');
    }
}

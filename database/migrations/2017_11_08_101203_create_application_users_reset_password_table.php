<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationUsersResetPasswordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_users_reset_password', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->integer('applicationUser_id')->unsigned()->nullable();
            $table->foreign('applicationUser_id')
                ->references('id')
                ->on('application_users')
                ->onDelete('set null');

            $table->string('token', 200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_users_reset_password');
    }
}

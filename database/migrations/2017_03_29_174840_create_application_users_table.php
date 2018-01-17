<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('application_users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('firstName', 100);
            $table->string('lastName', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('tel', 14)->unique();
            $table->date('birthday');
            $table->string('picture', 255)->nullable();
            $table->boolean('email_validation')->default(false);
            $table->boolean('cgu_cgv_accepted')->default(false);
            $table->integer('mango_id')->nullable()->unique();
            $table->integer('mango_card_id')->nullable()->unique();
            $table->boolean('activated')->default(true);
            $table->rememberToken('rememberToken');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_users');
    }
}
   

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('email', 100)->unique();
            $table->string('tel', 14);
            $table->string('ownerFirstName');
            $table->string('ownerLastName');
            $table->string('password');
            $table->string('name', 150)->unique();
            $table->string('category');
            $table->string('address', 255);
            $table->string('city', 255);
            $table->string('postalCode', 255);
            $table->float('lat', 10,6);
            $table->float('lng', 10,6);
            $table->string('picture', 255);
            $table->boolean('openStatus')->default(false);
            $table->boolean('HHStatus')->default(false);
            $table->string('website', 255)->nullable();
            $table->boolean('activated')->default(false);
            $table->integer('mango_id')->unique();
            $table->integer('mango_bank_id')->unique()->nullable();
            $table->integer('fees')->default(0);
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
        Schema::dropIfExists('partners');
    }
}

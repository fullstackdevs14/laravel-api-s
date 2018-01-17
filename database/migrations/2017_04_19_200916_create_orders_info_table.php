<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_info', function (Blueprint $table){
            $table->increments('id');

            $table->timestamps();

            $table->integer('applicationUser_id')->unsigned()->nullable();
            $table->foreign('applicationUser_id')
                ->references('id')
                ->on('application_users')
                ->onDelete('set null');

            $table->integer('partner_id')->unsigned()->nullable();
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('set null');

            $table->string('orderId');
            $table->boolean('HHStatus');
            $table->boolean('accepted')->nullable();
            $table->boolean('delivered');
            $table->boolean('incident');
            $table->integer('payInId')->nullable();
            $table->integer('payInId_share_bill')->nullable();
            $table->integer('fees')->default(0);
            $table->integer('applicationUser_id_share_bill')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_info');
    }
}

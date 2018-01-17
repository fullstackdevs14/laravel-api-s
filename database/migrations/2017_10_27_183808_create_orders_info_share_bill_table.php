<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersInfoShareBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_info_share_bill', function (Blueprint $table){
            $table->increments('id');

            $table->timestamps();

            $table->integer('applicationUser_id_1')->unsigned()->nullable();
            $table->foreign('applicationUser_id_1')
                ->references('id')
                ->on('application_users')
                ->onDelete('set null');

            $table->integer('applicationUser_id_2')->unsigned()->nullable();
            $table->foreign('applicationUser_id_2')
                ->references('id')
                ->on('application_users')
                ->onDelete('set null');

            $table->integer('partner_id')->unsigned()->nullable();
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('set null');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders_info');

            $table->string('orderId');

            $table->boolean('accepted')->default(0);

            $table->boolean('expired')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

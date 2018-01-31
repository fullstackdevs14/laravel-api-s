<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersInfoTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_info_temp', function (Blueprint $table){
            $table->bigIncrements('id');
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
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders_info');
            $table->string('orderId');
            $table->integer('application_user_id_share_bill')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_info_temp');
    }
}


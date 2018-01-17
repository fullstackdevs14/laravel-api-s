<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
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
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders_info')
                ->onDelete('set null');
            $table->integer('incident_id')->unsigned()->nullable();
            $table->foreign('incident_id')
                ->references('id')
                ->on('incidents')
                ->onDelete('set null');
            $table->float('amount', 8, 2);
            $table->boolean('success');
            $table->mediumText('description');
            $table->integer('mango_refund_id');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}

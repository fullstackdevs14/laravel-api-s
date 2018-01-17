<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentMonitoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents_monitoring', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders_info')
                ->onDelete('set null');
            $table->string('message');
            $table->boolean('phone');
            $table->boolean('email');
            $table->boolean('reimburse');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidents_monitoring');
    }
}

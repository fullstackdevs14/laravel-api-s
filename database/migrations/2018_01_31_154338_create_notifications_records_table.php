<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('created_at');
            $table->bigInteger('applicationUser_id')->unsigned()->nullable();
            $table->foreign('applicationUser_id')
                ->references('id')
                ->on('application_users')
                ->onDelete('set null');
            $table->bigInteger('partners_id')->unsigned()->nullable();
            $table->foreign('partners_id')
                ->references('id')
                ->on('partners')
                ->onDelete('set null');
            $table->bigInteger('order_id')->unsigned()->nullable();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders_info')
                ->onDelete('set null');
            $table->boolean('notification_status');
            $table->enum('type', ['accept', 'ready', 'decline', 'decline_expire', 'share_demand', 'share_accept', 'share_expire', 'share_decline', 'payment_failure']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_records');
    }
}

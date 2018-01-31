<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationUsersInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_users_invoices', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('created_at');
            $table->integer('applicationUser_id')->unsigned()->nullable();
            $table->foreign('applicationUser_id')
                ->references('id')
                ->on('application_users')
                ->onDelete('set null');
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders_info')
                ->onDelete('set null');
            $table->text('invoice_id');
            $table->enum('type', ['invoice', 'credit']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_users_invoices');
    }
}

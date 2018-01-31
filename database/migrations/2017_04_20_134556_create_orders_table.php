<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders_info')
                ->onDelete('set null');

            $table->integer('category_id');
            $table->string('itemName');
            $table->decimal('itemPrice');
            $table->decimal('itemHHPrice');
            $table->decimal('tax');
            $table->boolean('alcohol');
            $table->integer('quantity');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

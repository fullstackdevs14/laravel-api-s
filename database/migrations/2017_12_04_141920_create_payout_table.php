<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('partner_id')->unsigned()->nullable();
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('set null');
            $table->float('amount', 8, 2);
            $table->boolean('success')->nullable();
            $table->integer('mango_payout_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payouts');
    }

}
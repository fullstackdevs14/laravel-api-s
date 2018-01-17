<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('partner_id')->unsigned()->nullable();
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('set null');
            $table->date('from');
            $table->date('to');
            $table->text('invoice_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}

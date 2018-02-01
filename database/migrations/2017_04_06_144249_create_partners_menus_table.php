<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners_menus', function(Blueprint $table) {
            $table->bigIncrements('id');

            $table->timestamps();

            $table->bigInteger('partner_id')->unsigned();
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                ->references('id')
                ->on('menu_categories');

            $table->string('name', 100);
            $table->decimal('price');
            $table->decimal('HHPrice')->nullable();
            $table->integer('quantity');
            $table->decimal('tax');
            $table->boolean('alcohol');
            $table->text('ingredients');
            $table->boolean('availability');
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
        Schema::dropIfExists('partners_menus');
    }
}

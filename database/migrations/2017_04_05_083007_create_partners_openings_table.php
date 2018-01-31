<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersOpeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners_openings', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('partner_id')->unsigned();
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
            $table->string('monday1', 5);
            $table->string('monday2', 5)->nullable();
            $table->string('monday3', 5)->nullable();
            $table->string('monday4', 5);
            $table->string('tuesday1', 5);
            $table->string('tuesday2', 5)->nullable();
            $table->string('tuesday3', 5)->nullable();
            $table->string('tuesday4', 5);
            $table->string('wednesday1', 5);
            $table->string('wednesday2', 5)->nullable();
            $table->string('wednesday3', 5)->nullable();
            $table->string('wednesday4', 5);
            $table->string('thursday1', 5);
            $table->string('thursday2', 5)->nullable();
            $table->string('thursday3', 5)->nullable();
            $table->string('thursday4', 5);
            $table->string('friday1', 5);
            $table->string('friday2', 5)->nullable();
            $table->string('friday3', 5)->nullable();
            $table->string('friday4', 5);
            $table->string('saturday1', 5);
            $table->string('saturday2', 5)->nullable();
            $table->string('saturday3', 5)->nullable();
            $table->string('saturday4', 5);
            $table->string('sunday1', 5);
            $table->string('sunday2', 5)->nullable();
            $table->string('sunday3', 5)->nullable();
            $table->string('sunday4', 5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners_openings');
    }
}

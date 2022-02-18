<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tour_id')->unsigned();
            $table->string('prefix')->default('TOURNAMENT-');
            $table->string('number_sequence')->default('001');
            $table->string('status',10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_models');
    }
}

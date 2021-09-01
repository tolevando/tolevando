<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpeningHourMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_hour_markets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('market_id');
            $table->foreign('market_id')->references('id')->on('markets');
            $table->string('day');
            $table->time('open_hour')->nullable();
            $table->time('close_hour')->nullable();
            $table->boolean('automatic_open_close');
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
        Schema::dropIfExists('opening_hour_markets');
    }
}

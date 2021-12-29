<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeatherDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_details', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('weather_id')->comment('天氣id');
            $table->string('district_id')->comment('鄉鎮區id');
            $table->dateTime('start_time')->comment('資料區間');
            $table->dateTime('end_time')->comment('資料區間');
            $table->string('value')->comment('參數值');
            $table->timestamps();
            $table->foreign('weather_id')->references('id')->on('weathers');
            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weathers');
    }
}

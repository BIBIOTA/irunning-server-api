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
            $table->dateTime('data_time')->nullable()->comment('資料時間');
            $table->dateTime('start_time')->nullable()->comment('資料區間');
            $table->dateTime('end_time')->nullable()->comment('資料區間');
            $table->timestamps();
            $table->foreign('weather_id')->references('id')->on('weathers');
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

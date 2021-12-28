<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeatherDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_datas', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('weather_detail_id')->comment('天氣詳細id');
            $table->string('measures')->comment('參數描述');
            $table->string('value')->comment('參數值');
            $table->timestamps();
            $table->foreign('weather_detail_id')->references('id')->on('weather_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_datas');
    }
}

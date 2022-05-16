<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAqiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aqi', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('city_id')->index('aqi_city_id_foreign')->comment('縣市id');
            $table->string('SiteName')->comment('測站名稱');
            $table->string('AQI')->comment('空氣品質指標');
            $table->string('Pollutant')->comment('空氣污染指標物');
            $table->string('Status')->comment('狀態');
            $table->string('SO2')->comment('二氧化硫(ppb)');
            $table->string('CO')->comment('一氧化碳(ppm)');
            $table->string('CO_8hr')->comment('一氧化碳8小時移動平均(ppm)');
            $table->string('O3')->comment('臭氧(ppb)');
            $table->string('O3_8hr')->comment('臭氧8小時移動平均(ppb)');
            $table->string('PM10')->comment('懸浮微粒(μg/m3)');
            $table->string('PM2_5')->comment('細懸浮微粒(μg/m3)');
            $table->string('NO2')->comment('二氧化氮(ppb)');
            $table->string('NOx')->comment('氮氧化物(ppb)');
            $table->string('NO')->comment('一氧化氮(ppb)');
            $table->string('WIND_SPEED')->comment('風速(m/sec)');
            $table->string('WIND_DIREC')->comment('風向(degrees)');
            $table->string('PublishTime')->comment('資料建置日期');
            $table->string('PM2_5_AVG')->comment('細懸浮微粒移動平均值(μg/m3)');
            $table->string('PM10_AVG')->comment('懸浮微粒移動平均值(μg/m3)');
            $table->string('SO2_AVG')->comment('二氧化硫移動平均值(ppb)');
            $table->string('Longitude')->comment('經度');
            $table->string('Latitude')->comment('緯度');
            $table->string('SiteId')->comment('測站編號');
            $table->string('ImportDate')->comment('匯入日期時間');
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
        Schema::dropIfExists('aqi');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_datas', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('weather_document_id')->index('weather_datas_weather_document_id_foreign')->comment('天氣文件id');
            $table->string('district_id')->index('weather_datas_district_id_foreign')->comment('鄉鎮區id');
            $table->dateTime('start_time')->comment('資料區間');
            $table->dateTime('end_time')->comment('資料區間');
            $table->string('value')->comment('參數值');
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
        Schema::dropIfExists('weather_datas');
    }
}

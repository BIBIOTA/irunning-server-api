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
            $table->string('weather_document_id')->comment('天氣文件id');
            $table->string('district_id')->comment('鄉鎮區id');
            $table->dateTime('start_time')->comment('資料區間');
            $table->dateTime('end_time')->comment('資料區間');
            $table->string('value')->comment('參數值');
            $table->timestamps();
            $table->foreign('weather_document_id')->references('id')->on('weather_documents');
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
        Schema::dropIfExists('weather_datas');
    }
}

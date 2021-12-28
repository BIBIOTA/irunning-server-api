<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeathersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weathers', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('district_id')->comment('鄉鎮區id');
            $table->dateTime('dataTime')->nullable()->comment('資料時間');
            $table->dateTime('start_time')->nullable()->comment('資料區間');
            $table->dateTime('end_time')->nullable()->comment('資料區間');
            $table->string('ci')->comment('舒適度指數');
            $table->integer('temperature')->comment('溫度(攝氏度)');
            $table->integer('apparent_temperature')->comment('體感溫度(攝氏度)');
            $table->integer('pop6h')->comment('六小時降雨機率');
            $table->integer('wx')->comment('天氣現象值(參照wx_document)');
            $table->timestamps();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeatherDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_documents', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name')->unique()->comment('天氣參數名稱');
            $table->string('description')->unique()->comment('天氣參數描述');
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
        Schema::table('weather_documents', function (Blueprint $table) {
            //
        });
    }
}

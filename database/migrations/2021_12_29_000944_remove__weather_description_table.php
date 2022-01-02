<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveWeatherDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('WeatherDescription', function (Blueprint $table) {
            $table->dropForeign(['weather_id']);
        });
        Schema::dropIfExists('WeatherDescription');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('WeatherDescription', function (Blueprint $table) {
            //
        });
    }
}

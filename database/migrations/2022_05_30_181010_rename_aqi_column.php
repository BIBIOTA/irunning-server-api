<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('SiteName', 'sitename');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('AQI', 'aqi');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('Pollutant', 'pollutant');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('Status', 'status');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('SO2', 'so2');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('CO', 'co');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('CO_8hr', 'co_8hr');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('O3', 'o3');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('O3_8hr', 'o3_8hr');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('PM10', 'pm10');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('PM2_5', 'pm2_5');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('NO2', 'no2');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('NOx', 'nox');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('NO', 'no');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('WIND_SPEED', 'wind_speed');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('WIND_DIREC', 'wind_direc');
        });

        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('PublishTime', 'publishtime');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('PM2_5_AVG', 'pm2_5_avg');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('PM10_AVG', 'pm10_avg');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('SO2_AVG', 'so2_avg');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('Longitude', 'longitude');
        });

        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('Latitude', 'latitude');
        });
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('SiteId', 'siteid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aqi', function (Blueprint $table) {
            //
        });
    }
};

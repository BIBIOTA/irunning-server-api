<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePm25Pm25AvgColumnsAqi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aqi', function (Blueprint $table) {
            $table->renameColumn('`PM2-5`', 'PM2_5')->comment('細懸浮微粒(μg/m3)');
            $table->renameColumn('`PM2-5_AVG`', 'PM2_5_AVG')->comment('細懸浮微粒移動平均值(μg/m3)');
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
}

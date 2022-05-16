<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWeatherDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weather_datas', function (Blueprint $table) {
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['weather_document_id'])->references(['id'])->on('weather_documents')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weather_datas', function (Blueprint $table) {
            $table->dropForeign('weather_datas_district_id_foreign');
            $table->dropForeign('weather_datas_weather_document_id_foreign');
        });
    }
}

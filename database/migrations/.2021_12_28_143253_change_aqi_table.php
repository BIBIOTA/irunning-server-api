<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAqiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aqi', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $response = Http::get('https://data.epa.gov.tw/api/v1/aqx_p_432?limit=1000&api_key=9be7b239-557b-4c10-9775-78cadfc555e9&sort=ImportDate%20desc&format=json');
            $datas = $response->json();
            foreach($datas['fields'] as $data) {
                if ($data['id'] === 'PM2.5') {
                    $column = 'PM2-5';
                    $table->string($column)->comment($data['info']['label']);
                } else if ($data['id'] === 'PM2.5_AVG') {
                    $column = 'PM2-5_AVG';
                    $table->string($column)->comment($data['info']['label']);
                } else {
                    $table->string($data['id'])->comment($data['info']['label']);
                }
            }
            $table->timestamps();
            $table->foreign('city_id')->references('id')->on('cities');
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
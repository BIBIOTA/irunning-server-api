<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Wx', function (Blueprint $table) {
            $table->string('weather_id');
            $table->string('description')->comment('描述');
            $table->dateTime('startTime')->comment('預報區間');
            $table->dateTime('endTime')->comment('預報區間');
            $table->string('value')->comment('值');
            $table->string('measures');
            $table->foreign('weather_id')->references('id')->on('weather');
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
        Schema::dropIfExists('Wx');
    }
}

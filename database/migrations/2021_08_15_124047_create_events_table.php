<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('link')->nullable();
            $table->boolean('event_status')->comment('狀態(取消或延期:0;正常:1)');
            $table->string('event_name');
            $table->tinyInteger('event_certificate')->nullable()->comment('賽事認證(1:IAAF,2:AIMS,3:本賽道經AIMS/IAAF丈量員丈量)');
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('location');
            $table->string('agent');
            $table->string('participate')->nullable();
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
        Schema::dropIfExists('events');
    }
}

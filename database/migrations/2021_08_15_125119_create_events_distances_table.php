<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsDistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_distances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('event_id');
            $table->string('event_distance');
            $table->string('event_price')->nullable();
            $table->string('event_limit')->nullable();
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_distances');
    }
}

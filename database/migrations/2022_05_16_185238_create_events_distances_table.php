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
            $table->char('id', 36)->primary();
            $table->string('event_id')->index('events_distances_event_id_foreign');
            $table->string('event_distance');
            $table->string('event_price')->nullable();
            $table->string('event_limit')->nullable();
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
        Schema::dropIfExists('events_distances');
    }
}

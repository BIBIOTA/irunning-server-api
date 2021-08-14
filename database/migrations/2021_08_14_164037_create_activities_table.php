<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id');
            $table->string('name');
            $table->integer('distance');
            $table->integer('moving_time');
            $table->integer('elapsed_time');
            $table->integer('total_elevation_gain');
            $table->timestamp('start_date_local');
            $table->float('average_speed', 8, 3);
            $table->float('max_speed', 8, 2);
            $table->float('average_cadence', 8, 2)->nullable();
            $table->boolean('has_heartrate');
            $table->float('average_heartrate', 8, 2)->nullable();
            $table->float('max_heartrate', 8, 2)->nullable();
            $table->float('elev_high', 8, 2)->nullable();
            $table->float('elev_low', 8, 2)->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}

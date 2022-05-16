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
            $table->char('id', 36)->primary();
            $table->string('member_id')->index('activities_user_id_foreign');
            $table->string('name');
            $table->integer('distance');
            $table->integer('moving_time');
            $table->integer('elapsed_time');
            $table->integer('total_elevation_gain');
            $table->timestamp('start_date_local');
            $table->double('average_speed', 8, 3);
            $table->double('max_speed', 8, 2);
            $table->double('average_cadence', 8, 2)->nullable();
            $table->boolean('has_heartrate');
            $table->double('average_heartrate', 8, 2)->nullable();
            $table->double('max_heartrate', 8, 2)->nullable();
            $table->double('elev_high', 8, 2)->nullable();
            $table->double('elev_low', 8, 2)->nullable();
            $table->longText('summary_polyline')->nullable();
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
        Schema::dropIfExists('activities');
    }
}

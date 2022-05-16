<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('member_id')->index('stats_user_id_foreign');
            $table->integer('count');
            $table->integer('distance');
            $table->integer('moving_time');
            $table->integer('elapsed_time');
            $table->integer('elevation_gain');
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
        Schema::dropIfExists('stats');
    }
}

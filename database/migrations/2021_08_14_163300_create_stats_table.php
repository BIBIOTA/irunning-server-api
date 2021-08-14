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
            $table->uuid('id')->unique()->primary();
            $table->string('user_id');
            $table->integer('count');
            $table->integer('distance');
            $table->integer('moving_time');
            $table->integer('elapsed_time');
            $table->integer('elevation_gain');
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
        Schema::dropIfExists('stats');
    }
}

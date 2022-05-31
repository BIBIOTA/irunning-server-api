<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events_distances', function (Blueprint $table) {
            $table->integer('event_price')->nullable()->change();
            $table->integer('event_limit')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events_distances', function (Blueprint $table) {
            $table->string('event_price')->nullable()->change();
            $table->string('event_limit')->nullable()->change();
        });
    }
};

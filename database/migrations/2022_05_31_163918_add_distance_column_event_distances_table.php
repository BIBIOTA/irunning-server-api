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
            $table->float('distance')->after('event_distance')->nullable()->comment('馬拉松賽事里程');
            $table->string('event_distance')->nullable()->comment('特殊賽事里程')->change();
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
            $table->dropColumn('distance');
        });
    }
};

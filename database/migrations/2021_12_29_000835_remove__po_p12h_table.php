<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePoP12hTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('PoP12h', function (Blueprint $table) {
            $table->dropForeign(['weather_id']);
        });
        Schema::dropIfExists('PoP12h');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('PoP12h', function (Blueprint $table) {
            //
        });
    }
}

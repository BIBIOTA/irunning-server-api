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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('entry_is_end')->after('participate')->comment('報名是否截止');
            $table->date('entry_start')->nullable()->after('entry_is_end')->comment('報名開始日');
            $table->date('entry_end')->nullable()->after('entry_start')->comment('報名截止日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('entry_is_end');
            $table->dropColumn('entry_start');
            $table->dropColumn('entry_end');
        });
    }
};

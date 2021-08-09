<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_token', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('user_id');
            $table->timestamp('expires_at');
            $table->integer('expires_in')->comment('hour');
            $table->string('refresh_token');
            $table->string('access_token');
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
        Schema::dropIfExists('member_token');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_tokens', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('member_id')->index('member_token_user_id_foreign');
            $table->timestamp('expires_at');
            $table->integer('expires_in')->comment('hour');
            $table->string('refresh_token');
            $table->string('access_token');
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
        Schema::dropIfExists('member_tokens');
    }
}

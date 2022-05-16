<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMemberTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_tokens', function (Blueprint $table) {
            $table->foreign(['member_id'], 'member_token_user_id_foreign')->references(['id'])->on('members')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_tokens', function (Blueprint $table) {
            $table->dropForeign('member_token_user_id_foreign');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('strava_id', 36);
            $table->string('login_from')->default('Strava');
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('nickname')->nullable()->comment('暱稱');
            $table->integer('resource_state')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('county')->nullable();
            $table->string('district')->nullable();
            $table->string('siteName')->nullable();
            $table->string('sex')->nullable();
            $table->integer('badge_type_id')->nullable();
            $table->double('weight', 8, 2)->nullable();
            $table->integer('runner_type')->default(1)->comment('跑者類型, 1:初階跑者, 2: 中階跑者, 3: 進階跑者');
            $table->boolean('join_rank')->default(false)->comment('是否參加排行榜,false:否,true:是');
            $table->boolean('is_register')->default(false)->comment('是否註冊,false:否,true:是');
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
        Schema::dropIfExists('members');
    }
}

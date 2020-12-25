<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadGroupEldwryUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('head_group_eldwry_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('head_group_eldwry_id')->nullable();
            $table->unsignedInteger('add_user_id')->nullable();
            $table->unsignedInteger('game_id')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_block')->default(0);
            $table->timestamps();
            $table->foreign('add_user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('head_group_eldwry_id')->references('id')->on('head_group_eldwrys')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_group_eldwry_users');
    }
}

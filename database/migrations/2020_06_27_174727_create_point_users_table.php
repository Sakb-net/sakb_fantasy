<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('game_players_history_id')->nullable();
            $table->unsignedInteger('player_id')->nullable();
            $table->double('points')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('game_players_history_id')->references('id')->on('game_players_history')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_users');
    }
}

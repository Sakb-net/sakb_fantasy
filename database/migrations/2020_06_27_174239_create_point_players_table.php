<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //2019_08_24_141218_create_game_points_table
        Schema::create('point_players', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('detail_playermatches_id')->nullable();
            $table->unsignedInteger('match_id')->nullable();
            $table->unsignedInteger('player_id')->nullable();
            $table->unsignedInteger('point_id')->nullable();
            $table->integer('number')->default(0);
            $table->double('points')->default(0);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign('detail_playermatches_id')->references('id')->on('detail_playermatches')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('match_id')->references('id')->on('matches')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign('point_id')->references('id')->on('points')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_players');
    }
}

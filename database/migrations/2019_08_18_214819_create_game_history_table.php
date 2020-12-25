<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('game_id')->nullable();
            $table->string('team_name')->nullable();
            $table->unsignedInteger('lineup_id')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->timestamps();
            
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lineup_id')->references('id')->on('settings')->onUpdate('cascade')->onDelete('cascade');
        });
        
        Schema::create('game_players_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('game_history_id')->nullable();
            $table->unsignedInteger('player_id')->nullable();
            $table->unsignedInteger('type_id')->nullable();  //main or sub احتياطى او اساسى
            $table->unsignedInteger('type_coatch')->nullable();  //coatch or sub_coatch
            $table->Integer('order_id')->default(1);
            $table->Integer('myteam_order_id')->default(1);
            $table->double('cost')->default(0);
            $table->tinyInteger('is_improv')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_change')->default(0);
            $table->tinyInteger('is_delete')->default(0);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('game_history_id')->references('id')->on('game_history')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('all_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_coatch')->references('id')->on('all_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_history');
        Schema::dropIfExists('game_players_history');
    }
}

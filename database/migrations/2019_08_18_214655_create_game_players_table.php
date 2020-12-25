<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePlayersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('game_players', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('game_id')->nullable();
            $table->unsignedInteger('player_id')->nullable();
            $table->unsignedInteger('type_id')->nullable();  //main or sub احتياطى او اساسى
            $table->unsignedInteger('type_coatch')->nullable();  //coatch or sub_coatch
            $table->Integer('order_id')->default(1);
            $table->Integer('myteam_order_id')->default(1);
            $table->double('cost')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_change')->default(0);
            $table->tinyInteger('is_delete')->default(0);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('all_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_coatch')->references('id')->on('all_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('game_players');
    }

}

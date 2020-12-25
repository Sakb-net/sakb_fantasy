<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameSubstitutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_substitutes', function (Blueprint $table) {
             $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('game_id')->nullable();
            $table->unsignedInteger('player_id')->nullable();
            $table->unsignedInteger('player_substitute_id')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->double('points')->default(0);
            $table->double('cost')->default(0);
            $table->unsignedInteger('card_type_id')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('player_substitute_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('type_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('card_type_id')->references('id')->on('all_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_substitutes');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPlayermatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_playermatches', function (Blueprint $table) {
             $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('match_id')->nullable();
            $table->unsignedInteger('team_id')->nullable();
            $table->unsignedInteger('player_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->string('link')->nullable();
            $table->double('cost')->default(0);
            $table->Integer('captain')->default(0);
            $table->Integer('shirtNumber')->default(0);
            $table->string('positionSide',100)->nullable();
            $table->Integer('formationPlace')->default(0);
            $table->Integer('accuratePass')->default(0);
            $table->Integer('wasFouled')->default(0);
            $table->Integer('lostCorners')->default(0);
            $table->Integer('goalsConceded')->default(0);
            $table->Integer('saves')->default(0);
            $table->Integer('goalKicks')->default(0);
            $table->Integer('totalPass')->default(0);
            $table->Integer('gameStarted')->default(0);
            $table->Integer('minsPlayed')->default(0);
            $table->Integer('blockedScoringAtt')->default(0);
            $table->Integer('totalScoringAtt')->default(0);
            $table->Integer('totalThrows')->default(0);
            $table->Integer('goalAssist')->default(0);
            $table->Integer('totalOffside')->default(0);
            $table->Integer('wonCorners')->default(0);
            $table->Integer('fouls')->default(0);
            $table->Integer('totalClearance')->default(0);
            $table->Integer('cornerTaken')->default(0);
            $table->Integer('wonTackle')->default(0);
            $table->Integer('totalTackle')->default(0);
            $table->Integer('totalSubOff')->default(0);
            $table->Integer('ontargetScoringAtt')->default(0);
            $table->Integer('shotOffTarget')->default(0);
            $table->Integer('goals')->default(0);
            $table->Integer('totalSubOn')->default(0);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('match_id')->references('id')->on('matches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('location_player')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_playermatches');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailMatchesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('detail_matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('match_id')->nullable();
            $table->unsignedInteger('team_id')->nullable();
            $table->unsignedInteger('player_id')->nullable();
            $table->unsignedInteger('assist_player_id')->nullable();
            $table->unsignedInteger('off_player_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->string('type', 50)->nullable();//goal,event,card,substitute
            $table->string('type_state', 50)->nullable();
            $table->Integer('goon')->default(0);
            $table->Integer('penalties')->default(0);
            $table->Integer('red_cart')->default(0);
            $table->Integer('yellow_cart')->default(0);
            $table->Integer('period')->default(0);
            $table->string('outcome')->nullable();
            $table->Integer('x')->default(0);
            $table->Integer('y')->default(0);
            $table->Integer('keyPass')->default(0);
            $table->timestamp('date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('lengthMin',100)->default(0);
            $table->string('lengthSec',100)->default(0);
            $table->string('reason',255)->nullable();
            $table->string('optaEventId')->nullable();
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('match_id')->references('id')->on('matches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('assist_player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('off_player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('location_player')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('detail_matches');
    }

}

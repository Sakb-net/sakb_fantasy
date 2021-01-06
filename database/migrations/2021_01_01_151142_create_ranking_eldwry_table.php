<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankingEldwryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranking_eldwry', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('eldwry_id')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('team_id')->nullable();
            $table->unsignedInteger('match_id')->nullable();
            $table->unsignedInteger('next_match_id')->nullable();
            $table->string('type')->nullable(); //away or home
            $table->string('link');
            $table->integer('won')->default(0);
            $table->integer('draw')->default(0);
            $table->integer('loss')->default(0);
            $table->integer('goals_own')->default(0);
            $table->integer('goals_aganist')->default(0);
            $table->integer('goals_diff')->default(0);
            $table->integer('points')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('eldwry_id')->references('id')->on('eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('match_id')->references('id')->on('matches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('next_match_id')->references('id')->on('matches')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ranking_eldwry');
    }
}

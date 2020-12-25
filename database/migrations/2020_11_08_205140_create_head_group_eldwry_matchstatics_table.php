<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadGroupEldwryMatchstaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('head_group_eldwry_matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('head_group_eldwry_id')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('first_team_match_id')->nullable();
            $table->unsignedInteger('second_team_match_id')->nullable();
            $table->tinyInteger('sort')->default(1);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('head_group_eldwry_id')->references('id')->on('head_group_eldwrys')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('first_team_match_id')->references('id')->on('head_group_eldwry_teamstatics')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('second_team_match_id')->references('id')->on('head_group_eldwry_teamstatics')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_group_eldwry_matches');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadGroupEldwryTeamstaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('head_group_eldwry_teamstatics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('head_group_eldwry_id')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->Integer('points')->default(0);
            $table->Integer('bouns')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('head_group_eldwry_id')->references('id')->on('head_group_eldwrys')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('head_group_eldwry_teamstatics');
    }
}

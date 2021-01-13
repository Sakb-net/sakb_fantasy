<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('link');
            $table->unsignedInteger('eldwry_id')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('fav_team')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->tinyInteger('max');
            $table->tinyInteger('min');
            $table->tinyInteger('player_trade')->comment('0 = none , 2 = all , 3 = administrator, 4 = manager');
            $table->date('date');
            $table->time('time');
            $table->time('time_choose');
            $table->string('code');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('admin_id')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->foreign('eldwry_id')->references('id')->on('eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('fav_team')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('all_types')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('draft');
    }
}

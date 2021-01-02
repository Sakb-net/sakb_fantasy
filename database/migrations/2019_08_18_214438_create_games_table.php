<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('eldwry_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('team_name')->nullable();
            $table->unsignedInteger('lineup_id')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('eldwry_id')->references('id')->on('eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lineup_id')->references('id')->on('settings')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}

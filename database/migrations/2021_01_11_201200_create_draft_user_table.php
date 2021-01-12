<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('draft_id');
            $table->unsignedInteger('user_id');
            $table->string('team_name');
            $table->tinyInteger('notifi')->comment('0 = no , 1 = yes');
            $table->tinyInteger('league_size');
            $table->tinyInteger('user_count');
            $table->foreign('draft_id')->references('id')->on('draft')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('draft_users');
    }
}

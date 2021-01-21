<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFavTeamToDraftUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('draft_users', function (Blueprint $table) {
            $table->unsignedInteger('fav_team')->nullable()->after('user_id');
            $table->foreign('fav_team')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('draft_users', function (Blueprint $table) {
            $table->dropColumn('fav_team');
        });
    }
}

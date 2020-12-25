<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToCreatorGroupEldwryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_eldwrys', function (Blueprint $table) {
            $table->unsignedInteger('creator_id')->nullable()->after('user_id');
            $table->foreign('creator_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_eldwrys', function (Blueprint $table) {
            $table->dropColumn('creator_id');
        });
    }
}

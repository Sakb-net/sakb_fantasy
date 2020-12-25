<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToGameSubstitutesCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_substitutes', function (Blueprint $table) {
            $table->string('code_substitute')->nullable()->after('sub_eldwry_id');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_substitutes', function (Blueprint $table) {
            $table->dropColumn('code_substitute');
        });
    }
}

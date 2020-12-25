<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToCardgamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->Integer('num_cardgold')->default(0)->after('lineup_id');
            $table->Integer('num_cardgray')->default(0)->after('lineup_id');
        });

        Schema::table('game_history', function (Blueprint $table) {
            $table->Integer('num_cardgold')->default(0)->after('lineup_id');
            $table->Integer('num_cardgray')->default(0)->after('lineup_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('num_cardgray');
            $table->dropColumn('num_cardgold');
        });
        Schema::table('game_history', function (Blueprint $table) {
            $table->dropColumn('num_cardgray');
            $table->dropColumn('num_cardgold');
        });
    }
}

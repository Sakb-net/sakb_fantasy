<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToAddnewcolumsDetailsPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_playermatches', function (Blueprint $table) {
            $table->Integer('cleanSheet')->default(0)->after('totalSubOn');
            $table->Integer('ownGoals')->default(0)->after('totalSubOn');
            $table->Integer('penaltySave')->default(0)->after('totalSubOn');
            $table->Integer('redCard')->default(0)->after('totalSubOn');
            $table->Integer('yellowCard')->default(0)->after('totalSubOn');
            $table->Integer('attPenGoal')->default(0)->after('totalSubOn');
            $table->Integer('attPenMiss')->default(0)->after('totalSubOn');
            $table->Integer('attPenTarget')->default(0)->after('totalSubOn');
            $table->Integer('goalAssistDeadball')->default(0)->after('totalSubOn');
            $table->Integer('assistOwnGoal')->default(0)->after('totalSubOn');
            $table->Integer('assistPenaltyWon')->default(0)->after('totalSubOn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_playermatches', function (Blueprint $table) {
            $table->dropColumn('cleanSheet');
            $table->dropColumn('ownGoals');
            $table->dropColumn('penaltySave');
            $table->dropColumn('redCard');
            $table->dropColumn('yellowCard');
            $table->dropColumn('attPenGoal');
            $table->dropColumn('attPenMiss');
            $table->dropColumn('attPenTarget');
            $table->dropColumn('goalAssistDeadball');
            $table->dropColumn('assistOwnGoal');
            $table->dropColumn('assistPenaltyWon');
        });
    }
}

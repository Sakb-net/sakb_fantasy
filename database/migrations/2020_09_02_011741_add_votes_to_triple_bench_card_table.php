<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToTripleBenchCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->Integer('bench_card')->default(0)->after('is_active');
            $table->Integer('triple_card')->default(0)->after('is_active');
        });

        Schema::table('game_history', function (Blueprint $table) {
            $table->Integer('bench_card')->default(0)->after('is_active');
            $table->Integer('triple_card')->default(0)->after('is_active');
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
            $table->dropColumn('bench_card');
            $table->dropColumn('triple_card');
        });

       Schema::table('game_history', function (Blueprint $table) {
            $table->dropColumn('bench_card');
            $table->dropColumn('triple_card');
        });
    }
}

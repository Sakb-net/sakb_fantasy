<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToIsBlockGroupeldwryuserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_eldwry_users', function (Blueprint $table) {
            $table->tinyInteger('is_block')->default(0)->after('is_active');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_eldwry_users', function (Blueprint $table) {
            $table->dropColumn('is_block');
        });
    }
}

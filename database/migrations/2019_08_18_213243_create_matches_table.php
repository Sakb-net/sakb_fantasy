<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('update_by')->nullable();
            $table->string('link');
            $table->string('opta_link')->nullable();
            $table->string('name')->nullable();
            $table->text('lang_name')->nullable();
            $table->text('image')->nullable();
            $table->unsignedInteger('first_team_id')->nullable();
            $table->unsignedInteger('second_team_id')->nullable();
            $table->unsignedInteger('winner_team_id')->nullable();
            $table->string('first_type')->nullable();
            $table->string('second_type')->nullable();
            $table->Integer('first_goon')->default(0);
            $table->Integer('second_goon')->default(0);
            $table->Integer('periodId')->default(0);
            $table->timestamp('date')->nullable();
            $table->string('time', 100)->nullable();
            $table->Integer('week')->nullable();
            $table->Integer('coverageLevel')->nullable();
            $table->string('venue')->nullable();
            $table->string('stage')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('video_id')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('first_team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('second_team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('winner_team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('matches');
    }

}

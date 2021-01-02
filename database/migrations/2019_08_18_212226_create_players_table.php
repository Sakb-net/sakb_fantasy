<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('team_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->unsignedInteger('type_id')->nullable(); //main or sub احتياطى او اساسى
            $table->string('link');
            $table->string('opta_link')->nullable();
            $table->string('name')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->text('lang_name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('nationalityId')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('foot')->nullable();
            $table->string('countryOfBirth')->nullable();
            $table->string('countryOfBirthId')->nullable();
            $table->string('dateOfBirth')->nullable();
            $table->string('placeOfBirth')->nullable();
            $table->string('real_position')->nullable();
            $table->string('real_position_side')->nullable();
            $table->string('join_date')->nullable();
            $table->mediumText('image')->nullable();
            $table->mediumText('image_match')->nullable();
            $table->integer('num_t_shirt')->nullable();
            $table->double('cost')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('location_player')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('all_types')->onUpdate('cascade')->onDelete('cascade');
            
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEldwryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->string('type_key', 50)->nullable();
            $table->string('value_ar')->nullable();
            $table->string('value_en')->nullable();
            $table->text('lang_name')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
        
        Schema::create('location_player', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->string('type_key', 50)->nullable();
            $table->string('value_ar')->nullable();
            $table->string('value_en')->nullable();
            $table->text('lang_name')->nullable();
            $table->string('color',100)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
          

        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->string('opta_link')->nullable();
            $table->string('name')->nullable();
            $table->text('lang_name')->nullable();
            $table->string('competitionCode')->nullable();
            $table->string('displayOrder')->nullable();
            $table->string('country')->nullable();
            $table->string('countryId')->nullable();
            $table->string('isFriendly')->nullable();
            $table->string('competitionFormat')->nullable();
            $table->string('type')->nullable();
            $table->string('competitionType')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
        
        Schema::create('eldwry', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('competition_id')->nullable();
            $table->unsignedInteger('update_by')->nullable();
            $table->string('link');
            $table->string('name')->nullable();
            $table->text('lang_name')->nullable();
            $table->double('cost')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('opta_link')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('includesVenues')->default(1);
            $table->tinyInteger('includesStandings')->default(1);
            $table->timestamps();
            $table->foreign('competition_id')->references('id')->on('competitions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_types');
        Schema::dropIfExists('location_player');
        Schema::dropIfExists('competitions');
        Schema::dropIfExists('eldwry');
    }
}

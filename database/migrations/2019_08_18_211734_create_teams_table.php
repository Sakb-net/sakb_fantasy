<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('eldwry_id')->nullable();
            $table->string('link');
            $table->string('opta_link')->nullable();
            $table->string('name')->nullable();
            $table->text('lang_name')->nullable();
            $table->string('shortName')->nullable();
            $table->string('officialName')->nullable();
            $table->string('type')->nullable();
            $table->string('teamType')->nullable();
            $table->string('code')->nullable();
            $table->string('addressZip')->nullable();
            $table->string('countryId')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('founded')->nullable();
            $table->mediumText('image')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('eldwry_id')->references('id')->on('eldwry')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}

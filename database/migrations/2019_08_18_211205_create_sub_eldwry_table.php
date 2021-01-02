<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubEldwryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sub_eldwry', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('update_by')->nullable();
            $table->unsignedInteger('eldwry_id')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->string('link');
            $table->string('opta_link')->nullable();
            $table->string('name')->nullable();
            $table->text('lang_name')->nullable();
            $table->Integer('num_week')->default(1);
            $table->float('change_point')->default(-4);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('start_change_date')->nullable();
            $table->timestamp('end_change_date')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreign('update_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('eldwry_id')->references('id')->on('eldwry')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('all_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('sub_eldwry');
    }

}

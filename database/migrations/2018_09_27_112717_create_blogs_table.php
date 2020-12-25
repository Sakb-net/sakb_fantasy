<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
           $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->integer('update_by')->default(0);
            $table->string('type', 50);
            $table->string('name');
            $table->text('lang_name')->nullable();
            $table->string('link');
            $table->text('content')->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('image')->nullable();
            $table->mediumText('video')->nullable();
            $table->mediumText('file')->nullable();
            $table->string('color', 50)->default('#000')->nullable();
            $table->string('tags', 255)->nullable();
            $table->tinyInteger('order_id')->default(1);
            $table->Integer('view_count')->default(0);
            $table->timestamp('date')->nullable();
            $table->string('time', 100)->nullable();
            $table->tinyInteger('comment_count')->default(0);
            $table->tinyInteger('is_share')->default(1);
            $table->tinyInteger('is_comment')->default(1);
            $table->tinyInteger('is_read')->default(0);
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_delete')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('blogs')->onUpdate('cascade')->onDelete('cascade');
           // $table->unique(['link','type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}

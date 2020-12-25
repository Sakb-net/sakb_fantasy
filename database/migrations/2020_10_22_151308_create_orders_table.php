<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('add_by')->nullable();
            $table->unsignedInteger('sub_eldwry_id')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->string('link');
            $table->string('name',255)->nullable();
            $table->string('source_pay', 50)->nullable();
            $table->string('method_pay', 100)->nullable();
            $table->string('type_request', 100);
            $table->float('cost')->default(0);
            $table->float('discount')->default(0);
            $table->string('checkoutId',255)->nullable();
            $table->string('transactionId',255)->nullable();
            $table->string('description',255)->nullable();
            $table->string('code',255)->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_read')->default(0);
            $table->tinyInteger('is_bill')->default(1);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('add_by')->references('id')->on('users')->onUpdate('cascade')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_eldwry_id')->references('id')->on('sub_eldwry')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('type_id')->references('id')->on('players')->onUpdate('cascade')->onDelete('cascade');    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

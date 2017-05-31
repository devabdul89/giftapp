<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->dateTime('date');
            $table->integer('admin_id')->unsigned();
            $table->text('description')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('product_id')->nullable();
            $table->double('price')->nullable();
            $table->string('currency')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('product_vendor')->default('amazon');
            $table->boolean('private')->default(0);
            $table->smallInteger('minimum_members')->default(1);
            $table->string('lat_lng')->nullable();
            $table->string('status')->default(0); //0:pending 1:completed 2:expired
            $table->string('message_code')->nullable();
            $table->integer('message_invite_count')->default(0);
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}

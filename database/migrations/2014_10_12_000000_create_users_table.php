<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fb_id')->nullable();
            $table->string('full_name');
            $table->string('email');
            $table->string('profile_picture')->nullable();
            $table->string('password');
            $table->boolean('password_created')->default(0);
            $table->string('session_token')->nullable();
            $table->boolean('walkthrough_completed')->default(0);
            $table->string('login_by')->default('facebook');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

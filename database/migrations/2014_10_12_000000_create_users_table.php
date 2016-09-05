<?php

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
            $table->string('username', 30);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->date('birthday');
            $table->integer('city_id');
            $table->integer('avatar_photo_id')->nullable();
            $table->integer('background_photo_id')->nullable();
            $table->integer('gallery_id');
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
        Schema::drop('users');
    }
}

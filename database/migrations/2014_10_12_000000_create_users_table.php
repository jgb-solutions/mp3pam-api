<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('name');
            $table->string('email', 60)->unique()->nullable();
            $table->string('password', 60)->nullable();
            $table->string('avatar')->nullable();
            $table->string('facebook_id', 16)->unique()->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->enum('type', ['artist', 'user'])->default('user');
            $table->boolean('admin')->default(false);
            $table->boolean('active')->default(true);
            $table->string('password_reset_code')->nullable();
            $table->boolean('first_login')->default(true);
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

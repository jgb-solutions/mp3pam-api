<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreateAlbumsTable extends Migration
  {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('albums', function (Blueprint $table) {
        $table->increments('id');
        $table->string('title')->index();
        $table->integer('hash')->unsigned()->unique();
        $table->string('cover');
        $table->string('img_bucket');
        $table->text('detail')->nullable();
        $table->integer('user_id')->unsigned();
        $table->integer('artist_id')->unsigned();
        $table->integer('release_year')->unsigned();
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
      Schema::dropIfExists('albums');
    }
  }

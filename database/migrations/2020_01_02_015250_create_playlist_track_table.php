<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreatePlaylistTrackTable extends Migration
  {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('playlist_track', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->integer('playlist_id')->unsigned();
        $table->integer('track_id')->unsigned();
        $table->timestamps();

//        $table
//          ->foreign('playlist_id')
//          ->references('id')
//          ->on('playlists');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public
    function down()
    {
      Schema::dropIfExists('playlist_track');
    }
  }

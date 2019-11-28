<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->integer('hash')->unsigned()->unique();
            $table->string('audio_name');
            $table->string('poster');
            $table->string('img_bucket');
            $table->string('audio_bucket');
            $table->boolean('featured')->default(false);
            $table->text('detail')->nullable();
            $table->text('lyrics')->nullable();
            $table->string('audio_file_size', 10);
            $table->integer('user_id')->unsigned();
            $table->integer('artist_id')->unsigned();
            $table->integer('album_id')->unsigned()->nullable();
            $table->integer('genre_id')->unsigned();
            $table->integer('number')->nullable();
            $table->integer('play_count')->unsigned()->default(0);
            $table->integer('download_count')->unsigned()->default(0);
            $table->boolean('publish')->default(true);
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
        Schema::dropIfExists('tracks');
    }
}

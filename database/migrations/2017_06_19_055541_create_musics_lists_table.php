<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicsListsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('music_lists', function (Blueprint $table) {
			$table->increments('id');
	      		$table->integer('playlist_id')->unsigned();
	      		$table->integer('music_id')->unsigned();
	      		$table->timestamps();

	      		$table
	      			->foreign('playlist_id')
	      			->references('id')
	      			->on('playlists')
	      			->onDelete('cascade');
        		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('music_lists');
	}
}

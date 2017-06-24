<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('musics', function($table)
		{
			$table->increments('id');
			$table->string('title')->index();
			$table->string('slug');
			$table->string('name');
			$table->string('image');
			$table->tinyInteger('featured');
			$table->text('description');
			$table->string('size');
			$table->integer('user_id');
			$table->integer('artist_id');
			$table->integer('category_id');
			$table->integer('views');
			$table->integer('play');
			$table->integer('download');
			$table->boolean('publish');
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
		Schema::dropIfExists('musics');
	}
}

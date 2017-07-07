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
			$table->integer('hash')->unsigned();
			$table->string('name');
			$table->string('image');
			$table->boolean('featured')->default(false);
			$table->text('detail');
			$table->string('size', 20);
			$table->integer('user_id')->unsigned();
			$table->integer('artist_id')->unsigned();
			$table->integer('category_id')->unsigned();
			$table->integer('play')->unsigned()->default(0);
			$table->integer('download')->unsigned()->default(0);
			$table->boolean('publish')->default(false);
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

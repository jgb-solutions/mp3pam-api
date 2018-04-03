<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('artists', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('stage_name')->unique();
			$table->integer('hash')->unsigned()->unique();
			$table->string('avatar')->nullable();
			$table->integer('user_id')->unsigned()->unique();
			$table->text('bio')->nullable();
			$table->boolean('verified')->default(false);
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('artists');
	}
}

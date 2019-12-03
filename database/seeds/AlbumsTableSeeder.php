<?php

use App\Models\Album;
use Illuminate\Database\Seeder;

class AlbumsTableSeeder extends Seeder
{
	public function run()
	{
		Album::truncate();

		factory(Album::class, 18)->create();
	}
}
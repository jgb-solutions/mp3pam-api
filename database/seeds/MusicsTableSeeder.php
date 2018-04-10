<?php

use Illuminate\Database\Seeder;

use App\Models\Music;

class MusicsTableSeeder extends Seeder
{
	public function run()
	{
		Music::truncate();

		factory(Music::class, 50)->create();
	}
}

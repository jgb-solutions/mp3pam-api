<?php

use Illuminate\Database\Seeder;

use App\Models\Track;

class TracksTableSeeder extends Seeder
{
	public function run()
	{
		Track::truncate();

		factory(Track::class, 200)->create();
	}
}

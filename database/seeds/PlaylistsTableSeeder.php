<?php

use Illuminate\Database\Seeder;

use App\Models\Playlist;

class PlaylistsTableSeeder extends Seeder
{
	public function run()
	{
		Playlist::truncate();

		factory(Playlist::class, 50)->create();
	}
}

<?php

use App\Models\User;
use App\Models\Artist;
use App\Helpers\MP3Pam;
use Illuminate\Database\Seeder;

class ArtistsTableSeeder extends Seeder
{
	public function run()
	{
		Artist::truncate();

		factory(Artist::class, 50)->create();
	}
}
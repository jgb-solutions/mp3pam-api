<?php

use App\Models\Artist;
use Illuminate\Database\Seeder;

class ArtistsTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('artists')->delete();

		$artists = [
			[
			  	'name' => '',
				'stageName' => '',
				'username' => '',
				'avatar' => '',
				'bio' => '',
			],
		];

		foreach ($artists as $artist) { Artist::create($artist); }
	}
}
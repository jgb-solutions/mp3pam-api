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
			  	'name' => 'Daniel Darinus',
				'stageName' => 'Fantom Tapajè',
				'hash' => App\Helpers\MP3Pam::getHash(Artist::class),
			],
		];

		foreach ($artists as $artist) { Artist::create($artist); }
	}
}
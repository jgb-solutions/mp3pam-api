<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class ArtistsTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('users')->delete();

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
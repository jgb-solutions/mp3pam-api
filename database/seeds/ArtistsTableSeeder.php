<?php

use App\Models\User;
use App\Models\Artist;
use App\Helpers\MP3Pam;
use Illuminate\Database\Seeder;

class ArtistsTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('artists')->delete();

		$artist = [
		  	'name' => 'Daniel Darinus',
			'stageName' => 'Fantom Tapajè',
			'hash' => MP3Pam::getHash(Artist::class),
			'user_id' => User::first()->id
		];

		Artist::create($artist);
	}
}
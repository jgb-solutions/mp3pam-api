<?php

use App\Models\User;
use App\Models\Artist;
use App\Helpers\MP3Pam;
use Illuminate\Database\Seeder;

class ArtistsTableSeeder extends Seeder
{
	public function run()
	{
		// DB::table('artists')->delete();
		// $user_id = User::first()->id;

		// $artist = [
		//   	'name' 			=> 'Daniel Darinus',
		// 	'stage_name' 	=> 'Fantom Tapajè',
		// 	'hash' 			=> MP3Pam::getHash(Artist::class),
		// 	'user_id' 		=> $user_id
		// ];

		// Artist::create($artist);
		Artist::truncate();

		factory(Artist::class, 25)->create();
	}
}
<?php

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('genres')->delete();

		$genres = [
			'Compas (Konpa)',
			'Roots (Rasin)',
			'Reggae',
			'Yanvalou',
			'R&B',
			'Rap',
			'Rap Kreyòl',
			'Dancehall',
			'Other',
			'Carnival',
			'Gospel',
			'DJ',
			'Mixtape',
			'Rabòday',
			'Rara',
			'Reggaeton',
			'House',
			'Jazz',
			'Raga',
			'Soul',
			'Sanba',
			'Sanmba',
			'Rock & Roll',
			'Techno',
			'Slow',
			'Salsa',
			'Troubadour',
			'Riddim',
			'Afro',
			'Slam'
		];

		foreach ($genres as $genre) {
			Genre:: create([
				'name' 	=> $genre,
				'slug'		=> str_slug($genre)
			]);
		}
	}
}
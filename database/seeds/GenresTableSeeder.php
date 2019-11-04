<?php

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('genres')->delete();

		$genres = [
			'Konpa',
			'Rasin',
			'Reggae',
			'Yanvalou',
			'R&B',
			'Rap Kreyòl',
			'Dancehall',
			'Lòt Jan',
			'Kanaval',
			'Gospel',
			'Levanjil',
			'DJ',
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
			'Twoubadou',
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
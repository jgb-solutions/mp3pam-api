<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('categories')->delete();

		$categories = [
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

		foreach ($categories as $category) {
			Category:: create([
				'name' 	=> $category,
				'slug'		=> str_slug($category)
			]);
		}
	}
}
<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		$this->call(UsersTableSeeder::class);
		$this->command->info('Users table seeded!');

		$this->call(AdminsTableSeeder::class);
		$this->command->info('Admins table seeded!');

		$this->call(CategoriesTableSeeder::class);
		$this->command->info('Categories table seeded!');

		if (app()->isLocal()) {
			$this->call(ArtistsTableSeeder::class);
			$this->command->info('Artists table seeded!');

			$this->call(MusicsTableSeeder::class);
			$this->command->info('Musics table seeded!');
		}

	}
}

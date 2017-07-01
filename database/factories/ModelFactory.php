<?php

use App\Models\User;
use App\Models\Artist;
use App\Models\Music;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
// $factory->define(App\Models\User::class, function (Faker\Generator $faker) {
// 	static $password;

// 	return [
// 		'name' => $faker->name,
// 		'email' => $faker->unique()->safeEmail,
// 		'password' => $password ?: $password = bcrypt('secret'),
// 		'remember_token' => str_random(10),
// 		'telephone' => rand(11111111,99999999)
// 	];
// });

$factory->define(Music::class, function (Faker\Generator $faker)
{
	$admin = User::whereAdmin(1)->first();
	$name = $faker->name;

	return [
		'title'			=> $name,
		'slug' 			=> str_slug($name),
		'description' 	=> $faker->realText(500),
		'size' 			=> rand(10000,99999),
		'name' 		=> 'placeholder-music.mp3',
		'image' 		=> 'placeholder-image.jpg',
		'user_id' 		=> $admin->id,
		'artist_id'		=> Artist::first()->id,
		'category_id' 	=> Category::first()->id,
	];
});
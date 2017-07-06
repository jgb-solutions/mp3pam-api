<?php

use App\Models\User;
use App\Models\Artist;
use App\Models\Music;
use App\Models\Category;

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
<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Music;
use App\Models\Artist;
use App\Helpers\MP3Pam;
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
	$admin = Admin::first();
	$name = $faker->name;

	return [
		'title'			=> $name,
		'hash' 			=> MP3Pam::getHash(Music::class),
		'detail' 		=> $faker->realText(200),
		'lyrics' 		=> $faker->realText(1000),
		'file_size'		=> rand(10000,99999),
		'file_name' 	=> '',
		'image' 			=> '',
		'user_id' 		=> $admin->id,
		'artist_id'		=> Artist::inRandomOrder()->first()->id,
		'category_id' 	=> Category::inRandomOrder()->first()->id,
	];
});

$factory->define(Artist::class, function (Faker\Generator $faker)
{
	return [
	  	'name' 			=> $faker->name,
		'stage_name' 	=> $faker->name,
		'hash' 			=> MP3Pam::getHash(Artist::class),
		'user_id' 		=> User::first()
	];
});

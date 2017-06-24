<?php

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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
	static $password;

	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = bcrypt('secret'),
		'remember_token' => str_random(10),
		'telephone' => rand(11111111,99999999)
	];
});

$factory->define(App\Models\Music::class, function (Faker\Generator $faker) {
	$admin = App\Models\User::whereAdmin(1)->first();
	$name = $faker->name;
	return [
		'name' => $name,
		'slug' => str_slug($name),
		'user_id' => $admin->id,
		'description' => $faker->realText(1000),
		'category_id' => App\Models\Category::first()->id,
		'budget' => rand(1000,5000000)
	];
});
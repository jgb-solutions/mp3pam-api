<?php

use App\Helpers\MP3Pam;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Track;
use App\Models\User;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Track::class, function (Faker\Generator $faker) {
    $admin = User::admin()->first();
    $name = $faker->name;

    return [
        'title' => $name,
        'hash' => MP3Pam::getHash(Track::class),
        'detail' => $faker->realText(200),
        'lyrics' => $faker->realText(1000),
        'audio_file_size' => rand(10000, 99999),
        'audio_name' => '',
        'image' => '',
        'user_id' => $admin->id,
        'artist_id' => Artist::inRandomOrder()->first()->id,
        'genre_id' => Genre::inRandomOrder()->first()->id,
    ];
});

$factory->define(Artist::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'stage_name' => $faker->name,
        'hash' => MP3Pam::getHash(Artist::class),
        'user_id' => User::first(),
    ];
});

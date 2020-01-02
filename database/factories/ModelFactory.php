<?php

  use App\Helpers\MP3Pam;
  use App\Models\Artist;
  use App\Models\Genre;
  use App\Models\Playlist;
  use App\Models\Track;
  use App\Models\User;
  use App\Models\Album;


  /** @var \Illuminate\Database\Eloquent\Factory $factory */
  $factory->define(Track::class, function (Faker\Generator $faker) {
    $admin = User::isAdmin()->first();
    $name  = $faker->name;
    extract(MP3Pam::getRandomTimestamps());

    $posters = [
      "cover256x256-558f05a20d704c239b77f8d806886464.jpg",
      "cover256x256-4de6dd257b8347e7bebe00b3b4630b71.jpg",
      "cover256x256-bd6bca6cfcae4825ab8e40ff2ea89596.jpg",
      "3ded8e0edc080f07a3a80ba5354d3ee7.jpg",
      "cover256x256-b82dac25a8fc4522b5cee417c8f9c414.jpg",
      "cover256x256-69a4662389e44c9e8f13b1ae8868d9b7.jpg",
      "cover256x256-3786d418d27f41378cb17b94a1890a50.jpg",
      "cover256x256-a97367ecb2c44ced9f722edd7c37414b.jpg",
      "cover256x256-7b82a27d2bf94279800b5d1f1e4077c4.jpg",
      "cover256x256-888b684ae24d47b79f4f55d66dc8cb40.jpg",
      "51ri1Ho8s5L._AA256_.jpg",
      "cover256x256-e3f35413861645bf921926f04fdd7499.jpg",
      "cover256x256-26048aee74bc4dc7b21a9a02295ad546.jpg",
      "a327310e6620b5f6d6dc3c0875d8b528.jpg",
      "0ba78f0b96c90599c04693c1faed92c7.jpg",
      "weebie.jpeg", "cover256x256-2df51bf5cf51437691ed27ca7e1981df.jpg",
      "apa7remqyqce2xx87c18.jpeg", "5820f738b9bdcdcde097aa9334e5c00a.jpg",
      "616j6KySGPL._AA256_.jpg", "1357828077_uploaded.jpg", "download3.jpeg",
      "Pu5fUTwO_400x400.jpg", "image.jpg",
    ];

    $audios = [
      "Always On My Mind.mp3",
      "Chris_Brown_-_Fallen_Angel_(mp3.pm).mp3",
      "DANOLA premye fwa.mp3",
      "Daville_-_Mirrors_(mp3.pm).mp3",
      "Gym Class Heroes ft. Adam Levine - Stereo Hearts.mp3",
      "Jason Derulo - It Girl.mp3",
      "OMVR-Bad-News.mp3",
      "Pierre Jean - Krèy.mp3",
      "Pierre Jean - Peye Pote.mp3",
      "T-Vice - San Limit Ringtone.mp3",
    ];

    return [
      'title' => $name,
      'hash' => MP3Pam::getHash(Track::class),
      'detail' => $faker->realText(200),
      'lyrics' => $faker->realText(1000),
      'audio_file_size' => rand(10000, 99999),
      'audio_name' => "demo/" . $audios[array_rand($audios)],
      'poster' => "tracks/" . $posters[array_rand($posters)],
      'user_id' => $admin->id,
      'artist_id' => Artist::inRandomOrder()->first()->id,
      'genre_id' => Genre::inRandomOrder()->first()->id,
      'album_id' => Album::inRandomOrder()->first()->id,
      'number' => rand(00, 99),
      'img_bucket' => 'img-storage-dev.mp3pam.com',
      'audio_bucket' => 'audio-storage-dev.mp3pam.com',
      'allowDownload' => true,
      'created_at' => $created_at,
      'updated_at' => $updated_at,
    ];
  });

  $factory->define(Playlist::class, function (Faker\Generator $faker) {
    $admin_id = User::isAdmin()->first()->id;
    extract(MP3Pam::getRandomTimestamps());

    return [
      'title' => $faker->name,
      'hash' => MP3Pam::getHash(Playlist::class),
      'user_id' => $admin_id,
      'created_at' => $created_at,
      'updated_at' => $updated_at,
    ];
  });

  $factory->define(Artist::class, function (Faker\Generator $faker) {
    extract(MP3Pam::getRandomTimestamps());

    $posters = [
      "p06b4ktw.jpg", "_2Xp8Hde_400x400.png", "p01bqgmc.jpg",
      "p05kdhc3.jpg", "256x256.jpg", "GettyImages-521943452-e1527600573393-256x256.jpg",
      "oU7C04AF_400x400.jpg", "SM9t8paa_400x400.jpg", "p01br7j4.jpg",
      "181121-Daly-Tekashi-6ix9ine-tease_wpljyq.jpeg", "256.jpg", "p023cxqn.jpg",
      "p01bqlzy.jpg", "culture1.jpg", "Tyga-Molly-256x256.jpg",
      "GettyImages-599438124-256x256.jpg", "bLVTjpR4_400x400.jpg", "p01br530.jpg",
      "Kfd0NPizaV8FW0vyIBzHn_xs822K-jHgxZYT-S7Znmc.jpg", "55973d5d0a44d8d36a8b09607abbf70a.jpg",
      "GettyImages-2282702_l75xht (1).jpeg", "p01br58s.jpg", "p049twzl.jpg", "247664528009202.jpg",
    ];

    return [
      'name' => $faker->name,
      'stage_name' => $faker->name,
      'poster' => "artists/" . $posters[array_rand($posters)],
      'hash' => MP3Pam::getHash(Artist::class),
      'user_id' => User::first(),
      'bio' => $faker->realText(1000),
      'facebook' => $faker->userName,
      'twitter' => $faker->userName,
      'youtube' => $faker->userName,
      'instagram' => $faker->userName,
      'img_bucket' => 'img-storage-dev.mp3pam.com',
      'created_at' => $created_at,
      'updated_at' => $updated_at,
    ];
  });

  $factory->define(Album::class, function (Faker\Generator $faker) {
    extract(MP3Pam::getRandomTimestamps());

    $covers = [
      "images.jpeg", "180408-stereo-williams-cardi-b-hero_es4khm.jpeg",
      "kendrick-lamar-damn.-album-artwork.jpg",
      "2109c873c3f33ea6dfe2ca6842881c3a.jpg",
      "everybody-s-something.jpg", "e6eddf74f552b58f044564937c4d03f7.jpg",
      "p01jq74x.jpg", "Birdman_and_Lil_Wayne_Leather_So_Soft.jpg",
      "51+Cgm4HBUL._AA256_.jpg", "d03ffbb2-4558-4ffe-846f-62d58ab2d3a2.jpg",
      "YT-Drumma-Boy-256x256.jpg", "mkrmvba8-7903915.jpg",
      "2pac_instrumentals-rap-revolution_2.jpg", "UserAvatar.jpg",
      "itchHR-1-256x256.jpg", "41j0zn-UXnL._AA256_.jpg",
      "3d6c58c6-4572-4c72-a6e6-63b0f5d0b446.jpg", "p02lppdp.jpg",
    ];

    return [
      'title' => $faker->name,
      'detail' => $faker->realText(200),
      'cover' => "albums/" . $covers[array_rand($covers)],
      'hash' => MP3Pam::getHash(Album::class),
      'user_id' => User::first(),
      'artist_id' => Artist::inRandomOrder()->first()->id,
      'img_bucket' => 'img-storage-dev.mp3pam.com',
      'release_year' => rand(1950, 2019),
      'created_at' => $created_at,
      'updated_at' => $updated_at,
    ];
  });

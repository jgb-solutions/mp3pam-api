<?php

  use App\Models\Track;
  use App\Helpers\MP3Pam;
  use App\Models\Playlist;
  use Illuminate\Database\Seeder;
  use Illuminate\Support\Facades\DB;

  class PlaylistTrackTableSeeder extends Seeder
  {
    public function run()
    {
      DB::table('playlist_track')->truncate();

      extract(MP3Pam::getRandomTimestamps());

      foreach (range(1, 300) as $item) {
        DB::table('playlist_track')->insert([
          'track_id' => Track::inRandomOrder()->first()->id,
          'playlist_id' => Playlist::inRandomOrder()->first()->id,
          'created_at' => $created_at,
          'updated_at' => $updated_at,
        ]);
      }
    }
  }

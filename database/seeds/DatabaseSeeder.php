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

        $this->call(GenresTableSeeder::class);
        $this->command->info('Genres table seeded!');

        if (app()->isLocal()) {
            $this->call(ArtistsTableSeeder::class);
            $this->command->info('Artists table seeded!');

            $this->call(AlbumsTableSeeder::class);
            $this->command->info('Albums table seeded!');

            $this->call(TracksTableSeeder::class);
            $this->command->info('Tracks table seeded!');

            $this->call(PlaylistsTableSeeder::class);
            $this->command->info('Playlists table seeded!');

            $this->call(PlaylistTrackTableSeeder::class);
            $this->command->info('PlaylistTrack table seeded!');
        }
    }
}

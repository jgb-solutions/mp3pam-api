<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();
        $users = [
            [
                'name' => "Jean Gérard",
                'email' => "jgbneatdesign@gmail.com",
                'password' => "asdf,,,",
                'admin' => 1,
                'telephone' => 41830318,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('users')->delete();

		$user = [
		  	'name'  			=> env('ADMIN_NAME'),
		  	'email'     	=> env('ADMIN_EMAIL'),
		  	'password'  	=> bcrypt(env('ADMIN_PASSWORD')),
		  	'telephone'		=> env('ADMIN_TELEPHONE'),
		  	'admin'		=> 1,
		  	'active'		=> 1,
		];

		User::create($user);
	}
}
<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('users')->delete();

		$user = [
		  	'name'  		=> env('ADMIN_NAME'),
		  	'username'  	=> env('ADMIN_USERNAME'),
		  	'email'     		=> env('ADMIN_EMAIL'),
		  	'password'  	=> bcrypt(env('ADMIN_PASSWORD')),
		  	'avatar' 		=> env('AVATAR')
		  	'telephone'		=> env('ADMIN_TELEPHONE'),
		  	'admin'		=> 1,
		  	'active'		=> 1,
		];

		User::create($user);
	}
}
<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('users')->delete();

		$user = [
		  	'name'  			=> "John Doe",
		  	'email'     	=> "john@doe.com",
		  	'password'  	=> bcrypt("password"),
		  	'telephone'		=> env("36478199"),
		  	'active'		=> 1,
		];

		User::create($user);
	}
}
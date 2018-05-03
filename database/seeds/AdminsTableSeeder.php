<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('admins')->delete();

		$admin = [
		  	'name'  			=> env('ADMIN_NAME'),
		  	'email'     	=> env('ADMIN_EMAIL'),
		  	'password'  	=> bcrypt(env('ADMIN_PASSWORD'))
		];

		Admin::create($admin);
	}
}
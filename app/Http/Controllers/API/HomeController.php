<?php

namespace App\Http\Controllers\API;

use App\Models\Music;
use App\Helpers\MP3Pam;
use App\Http\Controllers\Controller;

class HomeController extends Controller {

	public function index()
	{
		return $data = MP3Pam::cache('page.home', function() {
			return [
				'featuredMusics' => Music::featured()->published()->latest()->take(8)->get(),
				'lastMonthTopMusics'  => Music::lastMonth()->popular()->byPlay()->take(8)->get(),
			];
		});
	}

}
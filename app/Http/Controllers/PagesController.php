<?php

namespace App\Http\Controllers;

use App\Helpers\MP3Pam;
use App\Models\Music;

class PagesController extends Controller
{
	public function index()
	{
		return view('pages.home');
	}

	public function notFound()
	{
		return view('errors.404');
	}

	public function discover()
	{
		$data = [
			'musics' => Music::published()
							->rand()
							->take(12)
							->get()
		];

		return view('pages.discover.index', $data);
	}

	public function discoverMusic()
	{
		$musics = Music::remember(120)
						->published()
						->rand()
						->paginate(20);

		return view('pages.discover.music', compact('musics'));
	}
}
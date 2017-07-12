<?php

namespace App\Http\Controllers;

use Cache;
use App\Models\Music;

class PagesController extends Controller
{
	public function index()
	{
		$data = Cache::rememberForever('page.home', function() {
			$data = [
				'featuredMusics' => Music::featured()
										->published()
										->latest()
										->take(8)
										->get(),
				'lastMonthTopMusics'  => Music::lastMonth()
											->popular()
											->byPlay()
											->take(8)
											->get(),
			];

			return $data;
		});

		return view('home', $data);

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
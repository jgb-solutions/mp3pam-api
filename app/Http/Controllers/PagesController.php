<?php

namespace App\Http\Controllers;

use App\Helpers\MP3Pam;
use App\Models\Track;

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
			'tracks' => Track::published()
							->rand()
							->take(12)
							->get()
		];

		return view('pages.discover.index', $data);
	}

	public function discoverTrack()
	{
		$tracks = Track::remember(120)
						->published()
						->rand()
						->paginate(20);

		return view('pages.discover.track', compact('tracks'));
	}
}
<?php

namespace App\Http\Controllers\API;

use App\Models\Artist;
use App\Models\Track;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
	public function getIndex(Request $request) {
		$term = $request->get('q');
		$type = $request->get('type');

		if (isset($type) && ! empty($type)) {
			$fn = 'search' . $type;
			return $this->$fn($term);
		}

		$key = 'search_' . $term;

		$results = Cache::rememberForever($key, function() use ($term, $request) {
			// $track_res = $this->search('tracks', $query);
			$track_res = null;
			$track_results = Track::search($track_res['ids'], $term)
				->get(['id', 'play', 'download', 'views', 'artist', 'name', 'image']);

			$track_results = $this->prepare('track', $track_results);

			// $video_res = $this->search('videos', $query);
			$video_res = null;

			$video_results = Video::search($video_res['ids'], $term)
				->get(['id', 'download', 'views', 'artist', 'name', 'image', 'youtube_id']);

			$video_results = $this->prepare('video', $video_results);


			$results = $track_results->merge($video_results)->shuffle();

			return $results;
		});

		if ($request->ajax()) {
			return $results;
		}

		return view('search.index', [
			'results' => $results,
			'query' => $term,
			'title'	=> 'Rezilta pou: ' . $term
		]);
	}



	public function search_tracks($term)
	{
		return Track::with('category')
			->latest()
			->search($term)
			->take(30)
			->get(['title', 'image', 'category_id', 'hash']);
			// ->paginate(20, ['id', 'name', 'play', 'views', 'artist', 'download'] );
	}

	public function search_artists($term)
	{
		return Artist::withCount('tracks')
			->latest()
			->search($term)
			->take(30)
			->get(['stageName', 'hash', 'avatar']);
			// ->paginate(20, ['id', 'name', 'play', 'views', 'artist', 'download'] );
	}

	public function search(Request $request)
	{
		$term = $request->term;

		return [
			'tracks' => $this->search_tracks($term),
			'artists' => $this->search_artists($term)
		];
	}
}
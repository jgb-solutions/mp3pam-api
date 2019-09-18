<?php

namespace App\Http\Controllers;

use Cache;
use App\Models\Track;
use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePlaylistRequest;
use App\Http\Requests\UpdatePlaylistRequest;

class PlaylistsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except(['index', 'show']);
	}

	public function index()
	{
		if (request()->has('page')) {
			$page = request()->get('page');
		} else {
			$page = 1;
		}

		$key = 'all_playlists_' . $page;

		$playlists = Cache::rememberForEver($key, function() {
			return Playlist::has('mList')->latest()->paginate(15);
		});

		// return $playlists;
		return view('playlists.index', compact('playlists'));
	}

	public function show($id, Request $request)
	{
		$key = '_playlist_show_' . $id;

		$data = Cache::rememberForEver($key, function() use ($id) {
			$playlist = Playlist::findOrFail($id);

		   $data = [
		   	'playlist' => $playlist,
		   	'tracks' => $playlist->tracks
		   ];

		   return $data;
		});

		if ($request->ajax()) {
			return $data['tracks'];
		}

		return view('playlists.show', $data);
	}

	public function getCreate()
	{
		$user = auth()->user();

		$data = [
			'playlists' => $user->playlists()->latest()->get(),
		];

		return view('playlists.create', $data);
	}

	public function postCreate(CreatePlaylistRequest $request)
	{
		$user = auth()->user();

		$name = $request->get('name');

		$playlist = [
			'name' => $name,
			'slug' => str_slug($name)
		];

		$user->playlists()->create($playlist);

		Cache::flush();

		return back();
	}

	public function edit(Playlist $playlist)
	{
		$user = auth()->user();

		$data = [
			'playlists' => $user->playlists()->latest()->get(),
			'playlist' => $playlist
		];

		return view('playlists.edit', $data);
	}

	public function update(UpdatePlaylistRequest $request, Playlist $playlist)
	{
		$data = [
			'name' => $request->get('name'),
			'slug' => $request->get('name')
		];

		$playlist->update($data);

		Cache::flush();

		return redirect(route('playlists.create'))
			->withMessage('Lis mizik la mete ajou av&egrave;k siks&egrave;.')
			->withStatus('success');
	}

	public function destroy(Playlist $playlist)
	{
		$this->authorize('destroy', $playlist);

		// $playlist->mList()->delete();
		$playlist->delete();

		Cache::forget('_playlist_show_' . $playlist->id);

		if (auth()->user()->admin) {
			return redirect(route('playlists.create'))
						->withMessage('Ou efase lis mizik la av&egrave;k siks&egrave;.')
						->withStatus('success');
		}

		return redirect(route('user.index'))
			->withMessage('Ou efase lis mizik la av&egrave;k siks&egrave;.')
			->withStatus('success');
	}

	public function listTracks(Playlist $playlist)
	{
		$user = auth()->user();

		$data = [
			'playlist' => $playlist,
			'tracks' => $playlist->tracks,
			'title' => $playlist->name
		];

		return view('playlists.list', $data);
	}

	public function postAddTrack(Playlist $playlist, Track $track)
	{
		if($playlist->mList()->whereTrackId($track->id)->first()) {
			return redirect($playlist->url)
				->withMessage('Ou ajoute mizik sa a nan lis la deja')
				->withStatus('warning');
		}

		$playlist->mList()->create([
			'track_id' => $track->id
		]);

		Cache::forget('_playlist_show_' . $playlist->id);

		return redirect($playlist->url)
			->withMessage("Ou ajoute $track->name nan lis mizik ou a av&egrave;k siks&egrave;.")
			->withStatus('success');
	}

	public function removeTrack(Playlist $playlist, Track $track)
	{
		$playlist->mList()->whereTrackId($track->id)->delete();

		Cache::flush();

		if (count($playlist->list)) {
			return redirect($playlist->url)
				->withMessage("Ou efase $track->name nan lis mizik ou a av&egrave;k siks&egrave;.")
				->withStatus('success');
		}

		return redirect(route('playlist.tracks', ['id' => $playlist->id]))
			->withMessage("Ou efase $track->name nan lis mizik ou a av&egrave;k siks&egrave;.")
			->withStatus('success');
	}
}

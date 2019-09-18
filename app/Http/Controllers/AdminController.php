<?php

namespace App\Http\Controllers;

use Auth;
use Cache;
use Validator;
use App\Models\User;
use App\Models\track;
use App\Models\Category;
use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
	public function __construct()
	{
		// $this->middleware('admin');
	}

	public function index()
	{
		return 'welcome to admin';
		return view('admin.index');
	}

	public function tracks()
	{
		// $track = track::remember(120)->latest()->paginate(30);
		$track = track::latest()->paginate(30);

		// $track_count = track::remember(120)->count();
		$track_count = track::count();

		$title = 'Administrayon Mizik (' . $track_count . ')';

		return view('admin.track.index')
					->withTitle($title)
					->withtracks($track)
					->withtrackCount($track_count);
	}

	public function videos()
	{
		// $video = video::remember(120)->latest()->paginate(30);
		$video = video::latest()->paginate(30);

		// $video_count = track::remember(120)->count();
		$video_count = video::count();

		$title = 'Administrayon Videyo (' . $video_count . ')';

		return view('admin.video.index')
					->withTitle($title)
					->withvideos($video)
					->withvideoCount($video_count);
	}

	public function playlists()
	{
		// $video = video::remember(120)->latest()->paginate(30);
		$playlist = playlist::latest()->paginate(30);

		// $playlist_count = track::remember(120)->count();
		$playlist_count = playlist::count();

		$title = 'Administrayon Lis Mizik (' . $playlist_count . ')';

		return view('admin.playlists.index')
					->withTitle($title)
					->withplaylists($playlist)
					->withplaylistCount($playlist_count);
	}

	public function users()
	{
		$users = User::latest()->paginate(10);
		$userscount = User::count();

		return view('admin.users.index')
					->withTitle('Administrayon Itilizatè (' . $userscount . ')')
					->withUsers($users)
					->withUsersCount($userscount);
	}
}
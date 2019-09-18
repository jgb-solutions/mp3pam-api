<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Track;
use App\Models\Category;
use App\Helpers\MP3Pam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrackCollection;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UsersController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->only([
			'getUser',
			'edit',
			'update',
			'delete',
			'boughtTracks',
		]);
	}

	public function index()
	{
		return MP3Pam::cache('users_index_', function() {
			return User::latest()->paginate(10);
		});
	}

	public function show($id)
	{
		$key = '__user__' . $id;

		return MP3Pam::cache($key, function() use ($id) {
			return User::with([
				'tracks' => function($query) {
					$query->select(['user_id', 'title', 'size', 'play', 'hash', 'play', 'download'])
						->latest()->take(10);
				}
			])->findOrFail($id);;
		});
	}

	public function tracks($id)
	{
		$key = '_user_tracks';

		return MP3Pam::cache($key, function() use ($key, $id) {
			$user = User::findOrFail($id);

			return $user->tracks()->latest()->paginate(10, ['id', 'title', 'size', 'play', 'hash', 'play', 'download']);
		});
	}

	public function toggleLike(Track $track)
	{
		$toggle = auth()->user()->likedTracks()->toggle($track);

		return (bool) count($toggle['attached']) ? 'true' : 'false';
	}

	public function tracksLiked()
	{
		$liked_tracks = auth()->user()->likedTracks()->latest()->paginate(10);

		return new TrackCollection($liked_tracks);
	}

	public function getLogin()
	{
		if (Auth::guest()) {
			return view('auth.login', ['title' => 'Koneksyon']);
		}

		return redirect(route('user.index'));
	}

	public function postLogin(Request $request)
	{
		if (Auth::attempt($request->only('email', 'password'), true)) {
			$user = Auth::user();

			if ($user->admin) {
				return redirect()->intended(route('admin.index'))
					->withMessage('Byenvini ankò, ' . $user->firstName . '!')
					->withStatus('info');
			}

			return redirect()->intended(route('user.index'))
				->withMessage('Byenvini ankò, ' . $user->firstName . '!')
				->withStatus('info');
		} else {
			return redirect(route('login'))
				->withMessage(config('site.message.errors.login'))
				->withStatus('warning')
				->withInput();
		}
	}

	public function getLogout()
	{
		Auth::logout();

		return redirect('/');
	}

	public function store(StoreUserRequest $request)
	{
		$user = User::create([
			'name' 			=> $request->get('name'),
			'password' 		=> bcrypt($request->get('password')),
			'email' 			=> $request->get('email'),
			'telephone' 	=> $request->get('telephone')
		]);

		Auth::login($user);

		$data = [
			'user' => $user,
			'subject' => 'Byenvini sou ' . config('site.name')
		];

		TKPM::sendMail('emails.user.welcome', $data, 'user');

		return redirect(route('user.index'))
			->withMessage('Byenvini, ' . $user->firstName)
			->withStatus('info');

	}

	public function getRegister()
	{
		if (Auth::check()) {
			return redirect(route('user.index'));
		}

		return view('auth.registration')
			->withTitle('Kreye yon kont');
	}

	public function getUser()
	{
		$user = Auth::user();
		$key = '_profile_user_data_' . $user->id;

		$data = Cache::rememberForever($key, function() use ($key, $user) {
			$data = [
				'tracks' 				=> $user->tracks()->latest()->take(6)->get(),
				'videos'				=> $user->videos()->latest()->take(6)->get(),
				'trackcount' 			=> $user->tracks()->count(),
				'videocount' 			=> $user->videos()->count(),
				'trackViewsCount' 		=> $user->tracks()->sum('views'),
				'videoViewsCount'		=> $user->videos()->sum('views'),
				'trackplaycount' 		=> $user->tracks()->sum('play'),
				'trackdownloadcount' 	=> $user->tracks()->sum('download'),
				'videodownloadcount' 	=> $user->videos()->sum('download'),
				'bought_count' 		=> $user->bought()->count(),
				'title'				=> 'Pwofil Ou',
				'user'				=> $user,
				'author'				=> $user->username ? '@' . $user->username . ' &mdash;' : $user->name . ' &mdash; '
			];

			return $data;
		});

		return view('user.profile', $data);
	}

	public function getUsertracks($usernameOrId = null)
	{
		$userRoute = $this->getUserFrom($usernameOrId);
		$user = $userRoute['user'];
		$route = $userRoute['route'];

		$user_tracks = $user->tracks();
		$user_videos = $user->videos();

		$first_name = ucwords( TKPM::firstName($user->name));
		$title = "Navige Tout Mizik $first_name Yo";

		$data = [
			'tracks' 				=> $user->tracks()->remember(5)->latest()->paginate(24),
			// 'tracks' 				=> $user->tracks()->latest()->paginate(10),
			'trackcount' 			=> $user_tracks->count(),
			'videocount' 			=> $user_videos->count(),
			'trackViewsCount' 	=> $user_tracks->sum('views'),
			'videoViewsCount'		=> $user_videos->sum('views'),
			'trackplaycount' 		=> $user_tracks->sum('play'),
			'trackdownloadcount' => $user_tracks->sum('download'),
			'videodownloadcount' => $user_videos->sum('download'),
			'bought_count' 		=> $user->bought()->count(),
			'first_name' 			=> $first_name,
			'title'					=> $title,
			'user'					=> $user,
			'route' 					=> $route
		];

		return view('user.track', $data);
	}

	public function getUservideos($usernameOrId = null)
	{
		$userRoute = $this->getUserFrom($usernameOrId);
		$user = $userRoute['user'];
		$route = $userRoute['route'];

		$user_tracks = $user->tracks();
		$user_videos = $user->videos();

		$first_name = ucwords(TKPM::firstName($user->name));
		$title = "Navige Tout Videyo $first_name Yo";

		$data = [
			'videos' 				=> $user->videos()->remember(5)->latest()->paginate(24),
			// 'videos' 				=> $user->videos()->latest()->paginate(10),
			'trackcount' 			=> $user_tracks->count(),
			'videocount' 			=> $user_videos->count(),
			'trackViewsCount' 	=> $user_tracks->sum('views'),
			'videoViewsCount'		=> $user_videos->sum('views'),
			'trackplaycount' 		=> $user_tracks->sum('play'),
			'trackdownloadcount' => $user_tracks->sum('download'),
			'videodownloadcount' => $user_videos->sum('download'),
			'bought_count' 		=> $user->bought()->count(),
			'title'					=> $title,
			'first_name' 			=> $first_name,
			'user'					=> $user,
			'route'					=> $route
		];

		return view('user.video', $data);
	}

	public function playlists($usernameOrId = null)
	{
		$userRoute = $this->getUserFrom($usernameOrId);
		$user = $userRoute['user'];
		$route = $userRoute['route'];

		$user_tracks = $user->tracks();
		$user_videos = $user->videos();

		$first_name = ucwords(TKPM::firstName($user->name));
		$title = 'Navige Tout Lis Mizik ';
		$title .= Auth::check() ? 'Ou ' :  $first_name;
		$title .= ' Yo';

		$data = [
			'trackcount' 			=> $user_tracks->count(),
			'videocount' 			=> $user_videos->count(),
			'trackViewsCount' 	=> $user_tracks->sum('views'),
			'videoViewsCount'		=> $user_videos->sum('views'),
			'trackplaycount' 		=> $user_tracks->sum('play'),
			'trackdownloadcount' => $user_tracks->sum('download'),
			'videodownloadcount' => $user_videos->sum('download'),
			'bought_count' 		=> $user->bought()->count(),
			'title'					=> $title,
			'first_name' 			=> $first_name,
			'user'					=> $user,
			'route'					=> $route,
			'playlists' 			=> $user->playlists()->paginate(15),
		];

		return view('user.playlists', $data);
	}

	public function edit($id = null)
	{
		$user = $id ? User::findOrFail($id) : Auth::user();

		$title = 'Modifye pwofil ';
		$title .= $id ? $user->name : 'ou';

		return view('user.profile-edit', compact('title', 'user'));
	}

	public function update(UpdateUserRequest $request, $id = null)
	{
		$user = $id ? User::findOrFail($id) : Auth::user();

		$name 		= $request->get('name');
		$email 		= $request->get('email');
		$password 	= $request->get('password');
		$image 		= $request->file('image');
		$telephone	= $request->get('telephone');
		$username 	= $request->get('username');

		if (isset($image)) {
			$img_ext = $image->getClientOriginalExtension();
			$img_name 	= Str::random(8) . time() . '.' . $img_ext;

			$content = file_get_contents($request->file('image')->getRealPath());

			$img_success = Storage::disk('images')->put($img_name, $content);

			if ($img_success) {
				TKPM::image($img_name, 250, 250, 'thumbs');
				TKPM::image($img_name, 50, 50, 'thumbs/profile');

				if ($user->image) {
					Storage::disk('images')->delete([
						$user->image,
						'thumbs/' . $user->image,
						'thumbs/profile/' . $user->image,
					]);
				}
			}
		}

		if (!empty($name)) {
			$user->name = $name;
		}

		if (!empty($email)) {
			$user->email = $email;
		}

		if (!empty($password)) {
			$user->password = bcrypt($password);
		}

		if (!empty($image)) {
			$user->image = $img_name;
		}

		if (!empty($telephone)) {
			$user->telephone = $telephone;
		}

		if (!empty($username)) {
			$user->username = $username;
		}

		$user->save();

		Cache::flush();

		if (Auth::user()->admin)
			return redirect(route('admin.users'))
				->withMessage("Ou mete pwofil la ajou avèk siskè!")
				->withStatus('success');

		return redirect( route('user.index'))
			->withMessage('Ou mete pwofil ou ajou avèk siskè!')
			->withStatus('success');
	}

	public function getUserPublic($id)
	{
		$userRoute = $this->getUserFrom($id);
		$user = $userRoute['user'];
		$route = $userRoute['route'];

		return $this->getUserData($user, $route);
	}

	public function getUserName($username)
	{
		$userRoute = $this->getUserFrom($username);
		$user = $userRoute['user'];
		$route = $userRoute['route'];

		return $this->getUserData($user, $route);
	}

	private function getUserData($user, $route)
	{
		$key = 'user_public_' . $user->id;

		$data = Cache::rememberForever($key, function() use ($user, $route) {
			$data = [
				'tracks' 				=> $user->tracks()->published()->latest()->take(12)->get(),
				'videos'					=> $user->videos()->latest()->take(12)->get(),
				'trackcount' 			=> $user->tracks()->count(),
				'videocount' 			=> $user->videos()->count(),
				'trackViewsCount' 	=> $user->tracks()->sum('views'),
				'videoViewsCount'		=> $user->videos()->sum('views'),
				'trackplaycount' 		=> $user->tracks()->sum('play'),
				'trackdownloadcount' => $user->tracks()->sum('download'),
				'videodownloadcount' => $user->videos()->sum('download'),
				'first_name' 			=> ucwords( TKPM::firstName($user->name)),
				'bought_count' 		=> $user->bought()->count(),
				'title'					=> "Pwofil $user->name",
				'user'					=> $user,
				'author'					=> $user->username ? '@' . $user->username . ' &mdash;' : $user->name . ' &mdash; ',
				'route' 					=> $route
			];

			return $data;
		});

		return view('user.profile-public', $data);
	}

	public function destroy(Request $request, User $user)
	{
		if ($user->admin) {
			return redirect(route('admin.users'))
				->withMessage('Ou pa ka efase administrat&egrave; a.')
				->withStatus('warning');
		}

		$loggedUser = Auth::user();

		if (!$loggedUser->admin) {
			if ($user->id !== $loggedUser->id) {
				return redirect(route('user.index'))
					->withMessage('Ou pa gen dwa efase kont sa a.')
					->withStatus('warning');
			}
		}

		$user->load('tracks', 'videos');

		$del = $request->get('del');

		$admin = $loggedUser->admin ? $loggedUser : User::whereAdmin(1)->first();

		if ($loggedUser->admin) {
			$tracks = $user->tracks;
			$videos = $user->videos;

			foreach ($tracks as $track) {
				$track->user_id = $admin->id;
				$track->save();

				Vote::whereObj('track')
					->whereObjId($track->id)
					->whereUserId($user->id)
					->delete();
			}

			foreach ($videos as $video) {
				$video->user_id = $admin->id;
				$video->save();

				Vote::whereObj('video')
					->whereObjId($video->id)
					->whereUserId($user->id)
					->delete();
			}

			$boughts = trackSold::whereUserId($user->id)->get();
			$boughts->each(function($track) {
				$track->delete();
			});

			if ($user->image) {
				Storage::disk('images')->delete([
					$user->image,
					'thumbs/' . $user->image,
					'thumbs/profile/' . $user->image,
				]);
			}

			$user->delete();

			return redirect(route('admin.users'))
				->withMessage('Ou efase kont la av&egrave;k siskè.')
				->withStatus('success');
		}

		$tracks = $user->tracks;
		$videos = $user->videos;

		foreach ($tracks as $track) {
			Vote::whereObj('track')
				->whereObjId($track->id)
				->whereUserId($user->id)
				->delete();

			if ($del) {
				Storage::disk('tracks')->delete([$track->mp3name]);
				Storage::disk('images')->delete([
					$track->image,
					'show/' . $track->image,
					'thumbs/' . $track->image,
					'thumbs/tiny/' . $track->image,
				]);

				$track->delete();
			} else {
				$track->user_id = $admin->id;
				$track->save();
			}

		}

		foreach ($videos as $video) {
			Vote::whereObj('video')
				->whereObjId($video->id)
				->whereUserId($user->id)
				->delete();

			if ($del) {
				$video->delete();
			} else {
				$video->user_id = $admin->id;
				$video->save();
			}
		}

		$boughts = TrackSold::whereUserId($user->id)->get();
		$boughts->each(function($track) {
			$track->delete();
		});

		$user->delete();

		Auth::logout();

		Cache::flush();

		$aff = '';

		if ($del) {
			$aff = 'Mizik ak Videyo ou yo efase tou avèk siskè. Ou ka <a href="' . route('register') . '">kreye yon nouvo kont</a> nenpòt lè ou vle.';
		}

		return redirect('/')
			->withMessage('Kont ou an efase avèk sikè. <br>' . $aff)
			->withStatus('success');
	}

	public function boughttracks()
	{
		$user 				= Auth::user();

		$trackcount 			= $user->tracks()->count();
		$videocount 			= $user->videos()->count();
		$trackplaycount 		= $user->tracks()->sum('play');
		$trackdownloadcount 	= $user->tracks()->sum('download');
		$videodownloadcount 	= $user->videos()->sum('download');
		$trackViewsCount 		= $user->tracks()->sum('views');
		$videoViewsCount 		= $user->videos()->sum('views');

		$firstname 			= TKPM::firstName($user->name);

		$bought_tracks = $user->bought()->get(['track_id']);
		$tracks = [];
		$track_ids = [];

		foreach ($bought_tracks as $bought_track) {
			$track_ids[] = $bought_track->track_id;
		}

		if ($track_ids) {
			$tracks = track::find($track_ids)->reverse();
		}

		$bought_tracks_count = $bought_tracks->count();

		$title = "Ou achte $bought_tracks_count mizik";

		$data = [
			'title' => $title,
			'tracks' => $tracks,
			'first_name' => $firstname,
			'trackcount' => $trackcount,
			'videocount' => $videocount,
			'trackplaycount' => $trackplaycount,
			'trackdownloadcount' => $trackdownloadcount,
			'videodownloadcount' => $videodownloadcount,
			'trackViewsCount' => $trackViewsCount,
			'videoViewsCount' => $videoViewsCount,
			'user' => $user,
			'bought_count' => $bought_tracks_count
		];
		return view('user.bought-track', $data);
	}


	public function facebookRedirect()
	{
		return Socialite::driver('facebook')->redirect();
	}

	public function handleFacebookCallback()
	{
		return $this->handleProviderCallback('facebook');
	}

	public function twitterRedirect()
	{
		return Socialite::driver('twitter')->redirect();
	}

	public function handleTwitterCallback()
	{
		return $this->handleProviderCallback('twitter');
	}

	public function handleProviderCallback($provider)
	{
		try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
        	// $url = $provider . 'Redirect';
         	// return $this->$url();
        	return redirect(route('login'))
				->withMessage(config('site.message.errors.login'))
				->withStatus('warning');
        }

        // dd($user);

        $authUser = $this->findOrCreateUser($user);

        Auth::login($authUser, true);

        return redirect()->intended(route('user.index'))
        	->withMessage('Byenvini ankò, ' . TKPM::firstName($authUser->name) . '!')
				->withStatus('info');
	}

	private function findOrCreateUser($user)
    {
    	$authUser = User::whereEmail($user->email)->first();

        if ($authUser) {
            return $authUser;
        }

		Cache::flush();

        return User::create([
            'name' => $user->name,
            'username' => $user->nickname ?: str_slug($user->name) . '-' . $user->id,
            'email' => $user->email,
            'avatar' => $user->avatar
        ]);
    }

   private function getUserFrom($usernameOrId) {
   	$route = [];

    	if (isset($usernameOrId)) {
			if (is_numeric($usernameOrId)) {
				$user = User::findOrFail($usernameOrId);
				$route = ['id' => $usernameOrId];
			} else if (is_string($usernameOrId)) {
				$user = User::byUsername($usernameOrId)->firstOrFail();
				$route = ['username' => $usernameOrId];
			}
		} else {
			$user = auth()->user();
		}

		return $data = [
			'user' => $user,
			'route' => $route
		];
   }
}
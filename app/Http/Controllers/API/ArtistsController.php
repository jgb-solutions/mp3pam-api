<?php

namespace App\Http\Controllers\API;

use App;
use Storage;
use App\Models\Artist;
use App\Helpers\MP3Pam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMusicRequest;
use App\Http\Requests\UpdateMusicRequest;

class ArtistsController extends Controller
{
	public function __construct()
	{
		// $this->middleware('auth')->except([
		// 	'index',
		// 	'listBuy',
		// 	'show',
		// 	'getMusic',
		// 	'getBuy',
		// 	'play',
		// 	'sayHello'
		// ]);

		// $this->middleware('musicOwner')->only(['edit', 'update']);
	}

	public function index()
	{
		// $data = [
		// 	// 'musics'	=> Music::remember(120)->latest()->published()->paginate(12),
		// 	'musics'	=> Music::latest()->published()->paginate(10),
		// ];
		return MP3Pam::cache('artists_index', function() {
			return Artist::latest()->paginate(10, ['id', 'name', 'stageName', 'hash', 'avatar', 'verified']);
		});

	}

	public function store(StoreMusicRequest $request)
	{
		$name 	= $request->get('name');
		$artist 	= $request->get('artist');

		$storedmusic = Music::whereName($name)
								->whereArtist($artist)
								->first();

		if ($storedmusic) {
			if ($request->ajax()) {
	        		$response = [];

	        		$response['success']  = true;
	        		if ($storedmusic->price == 'paid') {
	        			$response['url'] = route('music.edit', ['id' => $storedmusic->id]);
	        		} else {
	        		 	$response = [
	        				'success' => true,
	        				'emailedAndTweeted' => true,
	        				'id' => $storedmusic->id,
        		 			'url' => $storedmusic->url,
	        		 		'emailAndTweetUrl' => $storedmusic->emailAndTweetUrl
	        			];
	        		}

	        		return $response;
	        	}

			return redirect(route('music.show', [
      		 		'id' =>$storedmusic->id,
      		 		'slug' =>$storedmusic->slug
	        	]));
		}


		/****** music Uploading *******/
		$price 		= $request->get('price');
		$slug		= Str::slug($name);
		$music 		= $request->file('music');
		$music_size = MP3Pam::size($music->getClientsize());
		$music_ext 	= $music->getClientOriginalExtension();
		$music_name =  Str::random(8) . time() . '.' . $music_ext;

		$content = file_get_contents($request->file('music')->getRealPath());

		$music_success = Storage::disk('musics')->put($music_name, $content);

		/************ Image Uploading *****************/
		$img 		= $request->file('image');
		$img_type	= $img->getMimeType();
		$img_ext 	= $img->getClientOriginalExtension();
		$img_name 	= Str::random(8) . time() . '.' . $img_ext;

		$content = file_get_contents($request->file('image')->getRealPath());

		$img_success = Storage::disk('images')->put($img_name, $content);

		if ($img_success) {
			MP3Pam::image($img_name, 245, 250, 'thumbs');
			MP3Pam::image($img_name, 100, null, 'thumbs/tiny');
			MP3Pam::image($img_name, 640, 360, 'show');
		}

		$admin_id = User::whereAdmin(1)->first()->id;
		$user_id  = (Auth::check()) ? Auth::user()->id : $admin_id;

		if ($music_success && $img_success) {
			$music = new Music;
			$music->name = ucwords($name);
			$music->artist = ucwords($artist);
			$music->mp3name = $music_name;
			$music->image = $img_name;
			$music->user_id = $user_id;
			$music->category_id = $request->get('category');
			$music->size = $music_size;

			if ($price == 'free') {
				$music->publish = 1;
			}

			$music->price = $price;

			if (! $price) {
				$music->publish = 1;
				$music->price = 'free';
			}

			$music->description = $request->get('description');
			$music->slug = $slug;

			$music->save();


			/************** GETID3 **************/
			MP3Pam::tag($music, $img_name, $img_type);

			/******* Flush the cache ********/
			Cache::flush();

        	if ($request->ajax()) {
        		$response = [];

        		if ($music->paid) {
        			$response['url'] = route('music.edit', ['id' => $music->id]);
        		} else {
	        		$response = [
	        			'success' => true,
	        			'id' => $music->id,
        		 		'url' => $music->url,
        		 		'emailAndTweetUrl' => $music->emailAndTweetUrl
	        		];
        		}

        		return $response;
        	}

        	Cache::forget('latest.musics');

			if ($music->paid) {
					return redirect(route('music.edit', ['id' => $music->id]));
				} else {
				 	return redirect (route('music.show', [
				 		'id' =>$music->id,
				 		'slug' =>$music->slug
				 	]));
			}
		} else	{
			if ($request->ajax()) {
		        		$response = [];

		        		$response['success'] = false;
		        		$response['message'] = 'Nou regrèt men nou pa reyisi mete mizik ou a. Eseye ankò.';

		       	 	return $response;
		        }

			return back()
				->withMessage('Nou regrèt men nou pa reyisi mete mizik ou a. Eseye ankò.')
				->withStatus('failed');
		}

	}

	public function emailAndTweet($musicId)
	{
		$music = Music::with('user')->findOrFail($musicId);

		if ($music->paid) {
			// Send an email to the new user letting them know their music has been uploaded
			$data = [
				'music' => $music,
				'subject' => 'Felisitasyon!!! Ou fèk mete yon nouvo mizik pou vann.'
			];

			MP3Pam::sendMail('emails.user.buy', $data, 'music');
		} else {
			// Send an email to the new user letting them know their music has been uploaded
			$data = [
				'music' 		=> $music,
				'subject' 	=> 'Felisitasyon!!! Ou fèk mete yon nouvo mizik'
			];

			MP3Pam::sendMail('emails.user.music', $data, 'music');
		}

		if (! App::isLocal()) {
			MP3Pam::tweet($music, 'music');
		}

		return [
			'success' => true
		];
	}

	public function show($hash, $slug = null)
	{
		$key = '_artist_' . $hash;

		return MP3Pam::cache($key, function() use ($hash, $key) {
			return Artist::byHash($hash)->firstOrFail();
		});
	}

	public function musics($hash)
	{
		$key = '_artist_musics';

		return MP3Pam::cache($key, function() use ($key, $hash) {
			$artist = Artist::byHash($hash)->firstOrFail();

			return $artist->musics()->paginate(10, ['id', 'title', 'size', 'play', 'hash', 'play', 'download']);
		});
	}

	public function edit(Music $music)
	{
		$data = [
			'music'	=> $music,
			'title'	=> $music->name,
			'cats'	=> Category::allCategories(),
			'user' => Auth::user()
		];

		return $data;
	}

	public function update(Music $music, UpdateMusicRequest $request)
	{
		$code 	= $request->get('code');
		$price 	= $request->get('price');

		if ($music->paid) {
			if (! empty($code)) {
				$music->code = $code;
			}
		}

		$name = $request->get('name');
		$artist = $request->get('artist');
		$slug = Str::slug($name);
		$description = $request->get('description');
		$category = $request->get('cat');
		$publish = $request->get('publish');
		$featured = $request->get('featured');
		$image = $request->file('image');

		if (isset($image) ) {
			$img_ext = $image->getClientOriginalExtension();
			$img_name = Str::random(8) . time() . '.' . $img_ext;

			$content = file_get_contents($image->getRealPath());

			$img_success = Storage::disk('images')->put($img_name, $content);

			if ($img_success) {
				MP3Pam::image($img_name, 250, 250, 'thumbs');
				MP3Pam::image($img_name, 100, null, 'thumbs/tiny');
				MP3Pam::image($img_name, 640, 360, 'show');
			}
		}

		if (!empty($name))
			$music->name = ucwords($name);

		if (!empty($artist))
			$music->artist = ucwords($artist);

		if (! empty($description)) {
			$music->description = $description;
		}

		if (! empty($image)) {
			$music->image = $img_name;
		}

		if (!empty($category)) {
			$music->category_id = $category;
		}

		if (! empty($price)) {
			$music->price = $price;
		}

		if (Auth::user()->admin) {
			if ($featured) {
				$music->featured = 1;
			} else {
				$music->featured = 0;
			}
		}

		if ($publish && $price == 'paid') {
			$music->publish = 1;
		}

		elseif (! $publish && $price == 'paid') {
			$music->publish = 0;
		}

		$music->slug = $slug;
		$music->save();

		Cache::flush();

		if ($music->price == 'paid' && ! $music->publish) {
			return back()
				->withMessage(config('site.message.update'))
				->withStatus('info');
		} else if ($music->price == 'paid' && $music->publish) {
			if (! $music->code){
				return back();
			}

			return redirect(route('buy.show', ['id' => $music->id]))
				->withMessage(config('site.message.update'))
				->withStatus('info');
		}

		return redirect(route('music.show', ['id' => $music->id, 'slug' => $music->slug]))
			->withMessage(config('site.message.update'))
			->withStatus('success');
	}

	public function destroy(Request $request, music $music)
	{
		$user = $request->user();

		if ($user->id == $music->user_id || $user->admin) {
			Vote::whereObj('music')
				->whereObjId($music->id)
				->whereUserId($user->id)
				->delete();

			Storage::disk('images')->delete([
				$music->image,
				'thumbs/' . $music->image,
				'tiny/' . $music->image,
				'show/' . $music->image
			]);

			Storage::disk('musics')->delete($music->mp3name);

			MusicList::whereMusicId($music->id)->delete();

			$music->delete();

			Cache::flush();

			if ($user->admin) {
				return redirect(route('admin.music'))
					->withMessage(config('site.message.music-deletion-success'))
					->withStatus('success');
			}

			return redirect(route('music'))
				->withMessage(config('site.message.music-deletion-success'))
				->withStatus('success');
		}

	}

	public function download($hash, Request $request)
	{
		$music = Music::byHash($hash)->firstOrFail();
		// if ($music->download >= 100) {
		// 	if ($request->has('token')) {
		// 		MP3Pam::download($music);
		// 	}

		// 	return view('music.download', compact('music'));
		// }

		return MP3Pam::download($music);
	}

	public function play(Music $music)
	{
		$music->play += 1;
		$music->save();

		return redirect($music->mp3_url);
	}

	public function upload()
	{
		$data = [
			'cats'	=> Category::remember(120, 'allCategories')->orderBy('name')->get()
			// 'cats'	=> Category::orderBy('name')->get()
		];

		return view('music.upload', $data);
	}


	public function getBuy($id)
	{
		$key = '_music_buy_' . $id;

		if (Cache::has($key)) {
			$data = Cache::get($key);
			return view('music.buy', $data);
		}

		$music = Music::with('user', 'category')
			->published()
			->paid()
			->findOrFail($id);

		// $music->views += 1;
		// $music->save();

		$data = [];
		$data['bought'] = '';

		if (Auth::check()) {
			$user = Auth::user();

			$data['bought'] = $user->bought()->wheremusicId($music->id)->first();
		}

		$data['related'] = Music::remember(120)->related($music)
		// $data['related'] = Music::related($music)
			->get(['id', 'name', 'image', 'play', 'download', 'views']);

		$data['author'] = $music->user->name . ' &mdash; ';
		$data['title'] = "Achte $music->name";
		$data['music']	= $music;

		Cache::put($key, $data, 120);

		return view('music.buy', $data);
	}

	public function postBuy($id, Request $request)
	{
		$code = $request->get('code');

		if (Auth::check()) {
			$music = Music::find($id);
			$user = Auth::user();

			if ($user->id == $music->user_id) {
				return back()
					->withMessage( config('site.message.cant-buy') );
			}

			$bought = musicSold::whereUserId($user->id)
				  ->whereMusicId($music->id)
				  ->first();

			if ($bought) {
				return redirect(route('user.bought'))
					->withMessage( config('site.message.bought-already'))
					->withStatus('warning');
			}

			if ($code == $music->code) {
				$sold = new musicSold;
				$sold->user_id 	= $user->id;
				$sold->music_id 	= $music->id;
				$sold->save();

				$music->buy_count += 1;
				$music->save();

				return redirect("/user/my-bought-musics")
					->withMessage( config('site.message.bought-success') );
			} else {
				return back()
					->withMessage(config('site.message.bought-failed'));
			}
		}

		return redirect(route('login'))
			->withMessage(config('site.message.login'));
	}
}
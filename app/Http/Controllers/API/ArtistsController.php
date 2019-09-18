<?php

namespace App\Http\Controllers\API;

use App;
use Storage;
use App\Models\Artist;
use App\Helpers\MP3Pam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrackRequest;
use App\Http\Requests\UpdateTrackRequest;

class ArtistsController extends Controller
{
	public function __construct()
	{
		// $this->middleware('auth')->except([
		// 	'index',
		// 	'listBuy',
		// 	'show',
		// 	'gettrack',
		// 	'getBuy',
		// 	'play',
		// 	'sayHello'
		// ]);

		// $this->middleware('trackOwner')->only(['edit', 'update']);
	}

	public function index()
	{
		// $data = [
		// 	// 'tracks'	=> track::remember(120)->latest()->published()->paginate(12),
		// 	'tracks'	=> track::latest()->published()->paginate(10),
		// ];
		return MP3Pam::cache('artists_index', function() {
			return Artist::latest()->paginate(10, ['id', 'name', 'stageName', 'hash', 'avatar', 'verified']);
		});

	}

	public function store(StoretrackRequest $request)
	{
		$name 	= $request->get('name');
		$artist 	= $request->get('artist');

		$storedtrack = track::whereName($name)
								->whereArtist($artist)
								->first();

		if ($storedtrack) {
			if ($request->ajax()) {
	        		$response = [];

	        		$response['success']  = true;
	        		if ($storedtrack->price == 'paid') {
	        			$response['url'] = route('track.edit', ['id' => $storedtrack->id]);
	        		} else {
	        		 	$response = [
	        				'success' => true,
	        				'emailedAndTweeted' => true,
	        				'id' => $storedtrack->id,
        		 			'url' => $storedtrack->url,
	        		 		'emailAndTweetUrl' => $storedtrack->emailAndTweetUrl
	        			];
	        		}

	        		return $response;
	        	}

			return redirect(route('track.show', [
      		 		'id' =>$storedtrack->id,
      		 		'slug' =>$storedtrack->slug
	        	]));
		}


		/****** track Uploading *******/
		$price 		= $request->get('price');
		$slug		= Str::slug($name);
		$track 		= $request->file('track');
		$track_size = MP3Pam::size($track->getClientsize());
		$track_ext 	= $track->getClientOriginalExtension();
		$track_name =  Str::random(8) . time() . '.' . $track_ext;

		$content = file_get_contents($request->file('track')->getRealPath());

		$track_success = Storage::disk('tracks')->put($track_name, $content);

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

		if ($track_success && $img_success) {
			$track = new track;
			$track->name = ucwords($name);
			$track->artist = ucwords($artist);
			$track->mp3name = $track_name;
			$track->image = $img_name;
			$track->user_id = $user_id;
			$track->category_id = $request->get('category');
			$track->size = $track_size;

			if ($price == 'free') {
				$track->publish = 1;
			}

			$track->price = $price;

			if (! $price) {
				$track->publish = 1;
				$track->price = 'free';
			}

			$track->description = $request->get('description');
			$track->slug = $slug;

			$track->save();


			/************** GETID3 **************/
			MP3Pam::tag($track, $img_name, $img_type);

			/******* Flush the cache ********/
			Cache::flush();

        	if ($request->ajax()) {
        		$response = [];

        		if ($track->paid) {
        			$response['url'] = route('track.edit', ['id' => $track->id]);
        		} else {
	        		$response = [
	        			'success' => true,
	        			'id' => $track->id,
        		 		'url' => $track->url,
        		 		'emailAndTweetUrl' => $track->emailAndTweetUrl
	        		];
        		}

        		return $response;
        	}

        	Cache::forget('latest.tracks');

			if ($track->paid) {
					return redirect(route('track.edit', ['id' => $track->id]));
				} else {
				 	return redirect (route('track.show', [
				 		'id' =>$track->id,
				 		'slug' =>$track->slug
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

	public function emailAndTweet($trackId)
	{
		$track = track::with('user')->findOrFail($trackId);

		if ($track->paid) {
			// Send an email to the new user letting them know their track has been uploaded
			$data = [
				'track' => $track,
				'subject' => 'Felisitasyon!!! Ou fèk mete yon nouvo mizik pou vann.'
			];

			MP3Pam::sendMail('emails.user.buy', $data, 'track');
		} else {
			// Send an email to the new user letting them know their track has been uploaded
			$data = [
				'track' 		=> $track,
				'subject' 	=> 'Felisitasyon!!! Ou fèk mete yon nouvo mizik'
			];

			MP3Pam::sendMail('emails.user.track', $data, 'track');
		}

		if (! App::isLocal()) {
			MP3Pam::tweet($track, 'track');
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

	public function tracks($hash)
	{
		$key = '_artist_tracks';

		return MP3Pam::cache($key, function() use ($key, $hash) {
			$artist = Artist::byHash($hash)->firstOrFail();

			return $artist->tracks()->paginate(10, ['id', 'title', 'size', 'play', 'hash', 'play', 'download']);
		});
	}

	public function edit(track $track)
	{
		$data = [
			'track'	=> $track,
			'title'	=> $track->name,
			'cats'	=> Category::allCategories(),
			'user' => Auth::user()
		];

		return $data;
	}

	public function update(track $track, UpdatetrackRequest $request)
	{
		$code 	= $request->get('code');
		$price 	= $request->get('price');

		if ($track->paid) {
			if (! empty($code)) {
				$track->code = $code;
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
			$track->name = ucwords($name);

		if (!empty($artist))
			$track->artist = ucwords($artist);

		if (! empty($description)) {
			$track->description = $description;
		}

		if (! empty($image)) {
			$track->image = $img_name;
		}

		if (!empty($category)) {
			$track->category_id = $category;
		}

		if (! empty($price)) {
			$track->price = $price;
		}

		if (Auth::user()->admin) {
			if ($featured) {
				$track->featured = 1;
			} else {
				$track->featured = 0;
			}
		}

		if ($publish && $price == 'paid') {
			$track->publish = 1;
		}

		elseif (! $publish && $price == 'paid') {
			$track->publish = 0;
		}

		$track->slug = $slug;
		$track->save();

		Cache::flush();

		if ($track->price == 'paid' && ! $track->publish) {
			return back()
				->withMessage(config('site.message.update'))
				->withStatus('info');
		} else if ($track->price == 'paid' && $track->publish) {
			if (! $track->code){
				return back();
			}

			return redirect(route('buy.show', ['id' => $track->id]))
				->withMessage(config('site.message.update'))
				->withStatus('info');
		}

		return redirect(route('track.show', ['id' => $track->id, 'slug' => $track->slug]))
			->withMessage(config('site.message.update'))
			->withStatus('success');
	}

	public function destroy(Request $request, track $track)
	{
		$user = $request->user();

		if ($user->id == $track->user_id || $user->admin) {
			Vote::whereObj('track')
				->whereObjId($track->id)
				->whereUserId($user->id)
				->delete();

			Storage::disk('images')->delete([
				$track->image,
				'thumbs/' . $track->image,
				'tiny/' . $track->image,
				'show/' . $track->image
			]);

			Storage::disk('tracks')->delete($track->mp3name);

			trackList::wheretrackId($track->id)->delete();

			$track->delete();

			Cache::flush();

			if ($user->admin) {
				return redirect(route('admin.track'))
					->withMessage(config('site.message.track-deletion-success'))
					->withStatus('success');
			}

			return redirect(route('track'))
				->withMessage(config('site.message.track-deletion-success'))
				->withStatus('success');
		}

	}

	public function download($hash, Request $request)
	{
		$track = track::byHash($hash)->firstOrFail();
		// if ($track->download >= 100) {
		// 	if ($request->has('token')) {
		// 		MP3Pam::download($track);
		// 	}

		// 	return view('track.download', compact('track'));
		// }

		return MP3Pam::download($track);
	}

	public function play(track $track)
	{
		$track->play += 1;
		$track->save();

		return redirect($track->mp3_url);
	}

	public function upload()
	{
		$data = [
			'cats'	=> Category::remember(120, 'allCategories')->orderBy('name')->get()
			// 'cats'	=> Category::orderBy('name')->get()
		];

		return view('track.upload', $data);
	}


	public function getBuy($id)
	{
		$key = '_track_buy_' . $id;

		if (Cache::has($key)) {
			$data = Cache::get($key);
			return view('track.buy', $data);
		}

		$track = track::with('user', 'category')
			->published()
			->paid()
			->findOrFail($id);

		// $track->views += 1;
		// $track->save();

		$data = [];
		$data['bought'] = '';

		if (Auth::check()) {
			$user = Auth::user();

			$data['bought'] = $user->bought()->wheretrackId($track->id)->first();
		}

		$data['related'] = track::remember(120)->related($track)
		// $data['related'] = track::related($track)
			->get(['id', 'name', 'image', 'play', 'download', 'views']);

		$data['author'] = $track->user->name . ' &mdash; ';
		$data['title'] = "Achte $track->name";
		$data['track']	= $track;

		Cache::put($key, $data, 120);

		return view('track.buy', $data);
	}

	public function postBuy($id, Request $request)
	{
		$code = $request->get('code');

		if (Auth::check()) {
			$track = track::find($id);
			$user = Auth::user();

			if ($user->id == $track->user_id) {
				return back()
					->withMessage( config('site.message.cant-buy') );
			}

			$bought = trackSold::whereUserId($user->id)
				  ->wheretrackId($track->id)
				  ->first();

			if ($bought) {
				return redirect(route('user.bought'))
					->withMessage( config('site.message.bought-already'))
					->withStatus('warning');
			}

			if ($code == $track->code) {
				$sold = new trackSold;
				$sold->user_id 	= $user->id;
				$sold->track_id 	= $track->id;
				$sold->save();

				$track->buy_count += 1;
				$track->save();

				return redirect("/user/my-bought-tracks")
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
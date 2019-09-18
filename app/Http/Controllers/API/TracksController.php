<?php

namespace App\Http\Controllers\API;

use App\Models\Track;
use App\Jobs\TagTrack;
use App\Helpers\MP3Pam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrackResource;
use App\Http\Resources\TrackCollection;
use App\Http\Requests\StoreTrackRequest;
use App\Http\Requests\UpdateTrackRequest;

class TracksController extends Controller
{
	protected $user;

	public function __construct()
	{
		// $this->middleware('auth')->except([
		// 	'index',
		// 	'listBuy',
		// 	'show',
		// 	'getTrack',
		// 	'getBuy',
		// 	'play',
		// 	'sayHello'
		// ]);

		// $this->middleware('trackOwner')->only(['edit', 'update']);
	}

	public function index()
	{
		return new TrackCollection(Track::with('category', 'artist')->latest()->paginate(10));
	}

	public function store()
	{
		$user = MP3Pam::getUserFromToken();

		$track = $user->tracks()->create([
			'title'     => $request->title,
			'hash'          	=> MP3Pam::getHash(Track::class),
			'image' 		=> MP3Pam::image($request->file('image'), 500, null),
			'name' 		=> MP3Pam::store($request->file('track')),
			'detail' 		=> $request->detail,
			'category_id'   	=> $request->category,
			'artist_id' 		=> $request->artist,
			'category_id' 	=> $request->category,
			'size' 			=> MP3Pam::size($request->file('track')->getClientsize()),
		]);

		// 	GETID3
		// 	dispatch job because it's going to take some time.
		dispatch(new TagTrack($track));

		\Mail::to('john@johndoe.com')->queue(new \App\Mail\WelcomeEmail($track));

		return $track;
	}

	public function show($hash)
	{
		$track = Track::with(['artist', 'category'])->byHash($hash)->firstOrFail();

		$related = Track::related($track)->get();
		// return $related;

		return [
			'track' 	=> new TrackResource($track),
			'related' => new TrackCollection($related),
			'hasLiked' => (bool) auth()->user()->hasLiked($track)
		];
	}

	public function edit(Track $track)
	{
		$data = [
			'track'	=> $track,
			'title'	=> $track->name,
			'cats'	=> Category::allCategories(),
			'user' => Auth::user()
		];

		return $data;
	}

	public function update(Track $track, UpdateTrackRequest $request)
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

			TrackList::whereTrackId($track->id)->delete();

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
		$track = Track::byHash($hash)->firstOrFail();
		// if ($track->download >= 100) {
		// 	if ($request->has('token')) {
		// 		MP3Pam::download($track);
		// 	}

		// 	return view('track.download', compact('track'));
		// }

		return MP3Pam::download($track);
	}

	public function play(Track $track)
	{
		$track->play_count += 1;
		$track->save();

		return redirect($track->mp3_url);
	}
}
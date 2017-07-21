<?php

namespace App\Http\Controllers\API;

use App\Models\Music;
use App\Jobs\TagMusic;
use App\Helpers\MP3Pam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMusicRequest;
use App\Http\Requests\UpdateMusicRequest;

class MusicsController extends Controller
{
	protected $user;

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

		return MP3Pam::cache('_musics_index_', function() {
			return Music::latest()->paginate(10, [
				'title', 'play', 'download', 'hash'
			]);
		});

	}

	public function store(StoreMusicRequest $request)
	{
		$user = MP3Pam::getUserFromToken();

		$music = $user->musics()->create([
			'title'          		=> $request->title,
			'hash'          	=> MP3Pam::getHash(Music::class),
			'image' 		=> MP3Pam::image($request->file('image'), 500, null),
			'name' 		=> MP3Pam::store($request->file('music')),
			'detail' 		=> $request->detail,
			'category_id'   	=> $request->category,
			'artist_id' 		=> $request->artist,
			'category_id' 	=> $request->get('category'),
			'size' 			=> MP3Pam::size($request->file('music')->getClientsize()),
		]);

		// 	/************** GETID3 **************/
		// 	dispatch job because it's going to take some time.
		dispatch(new TagMusic($music));
		\Mail::to('john@johndoe.com')
		->queue(new \App\Mail\WelcomeEmail($music));

		return $music;
	}

	public function show($hash, $slug = null)
	{
		$key = '_music_' . $hash;

		return MP3Pam::cache($key, function() use ($hash, $key) {
			$music = Music::with([
				'user' => function($query) {
					$query->select(['id', 'name', 'username', 'email', 'avatar', 'telephone']);
				},
				'artist' => function($query) {
					$query->select(['id', 'name', 'stageName', 'hash', 'avatar', 'verified']);
				},
				'category'
			])->byHash($hash)->firstOrFail();

			$related = Music::related($music)->get(['id', 'name', 'image', 'play', 'download', 'hash']);
			// return $related;

			$data = [
				'music' 	=> $music,
				'related' => $related,
			];

			return $data;
		});

		return $data;
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
}
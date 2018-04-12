<?php

namespace App\Helpers;

use App;
use Auth;
use Image;
use Cache;
use getID3;
use Twitter;
use Storage;
use JWTAuth;
use App\Models\Vote;
use getid3_writetags;

class MP3Pam
{
	public static function image($file, $width = null, $height = null)
	{
		$folderPath = static::makeUploadFolder();

		if (! Storage::exists($folderPath)) {
			Storage::makeDirectory($folderPath);
		}

		Image::make($file->getRealPath())
			->resize($width, $height, function($constraint) {
				$constraint->aspectratio();
			})
			->save(static::storagePath(static::makeUploadFilePath($file)))
			->destroy();

		return static::makeUploadFilePath($file);
	}

	public static function profileLink($username = null, $id = null )
	{
		if ($username) {
			return route('user.public', ['username'=>$username]);
		} else {
			return route('user.public', ['id'=>$id]);
		}
	}

	public static function asset($asset, $size = 'null')
	{
		$imgSize = [
			'thumbs' 	=> config('site.image_upload_path') .'/thumbs/',
			'images' 	=> config('site.image_upload_path') .'/',
			'show'  	=> config('site.image_upload_path') .'/show/',
			'tiny' 		=> config('site.image_upload_path') .'/thumbs/tiny/',
			'profile' 	=> config('site.image_upload_path') .'/thumbs/profile/',
			'null'		=> '/'
		];

		$relativeUrl = $imgSize[$size] . $asset;

		if (App::isLocal()) {
			return asset($relativeUrl);
		}

		$cdnUrl = config('app.url');

		return url($cdnUrl . $relativeUrl);
	}

	public static function route($path, $params = [])
	{
		if (! App::isLocal()) {
			return secure_url(route($path, $params, false));
		}

		return url(route($path, $params));
	}

	public static function store($file)
	{
		return $file->storeAs(
			static::makeUploadFolder(),
			static::makeUploadFileName($file)
		);
	}

	public static function makeUploadFolder()
	{
		return 'user_' . static::getUserFromToken()->id . '/' . date('Y/m/d');
	}

	public static function makeUploadFileName($file)
	{
		return time() . '.' . $file->getClientOriginalExtension();
	}

	public static function makeUploadFilePath($file)
	{
		return static::makeUploadFolder() . '/' . static::makeUploadFileName($file);
	}

	public static function storagePath($path)
	{
		return storage_path("app/public/$path");
	}

	public static function logout($route)
	{
		Auth::logout();
		return redirect($route);
	}

	public static function isActive($route)
	{
		return request()->url() === route($route) ? 'active': '';
	}

	public static function flash($title, $text, $type, $buttonText, $color = null)
	{
		return [
			'title' => $title,
			'text'  => $text,
			'type'  => $type,
			'button_text' => $buttonText,
			//'button_color', '#FF9B22',
		];
	}

	public static function size($size, $round = 2)
	{
	    	$sizes = [' B', ' KB', ' MB'];

	    	$total = count($sizes) - 1;

	    	for ($i = 0; $size > 1024 && $i < $total; $i++) {
	       		$size /= 1024;
    		}

		return round($size, $round) . $sizes[$i];
	}

	public static function tag($music)
	{
		$mp3_handler = new getID3;
	   	$mp3_handler->setOption(['encoding'=> 'UTF-8']);

	    	$mp3_writter = new getid3_writetags;

	    	$mp3_writter->filename          			= storage_path('app/public/' . $music->name);
	  	$mp3_writter->tagformats        			= ['id3v2.3'];
	  	$mp3_writter->overwrite_tags    			= true;
	  	$mp3_writter->tag_encoding      			= 'UTF-8';
	  	$mp3_writter->remove_other_tags 		= true;

	  	$data['title'][]   						= config('site.url') . ' - ' . $music->fullTitle;
	  	$data['artist'][]  						= config('site.name') . ' - ' . config('site.url');
	  	$data['album'][]   					= config('site.name') . ' - ' . config('site.url');
	  	// $data['year'][]    = $mp3_year;
	  	// $data['genre'][]   = $mp3_genre;
	  	$data['comment'][] 					= config('site.name') . ' - ' . config('site.url');
    		$data['attached_picture'][0]['data']		 	= file_get_contents(public_path(config('site.defaultThumbnail')));
    		$data['attached_picture'][0]['picturetypeid'] 	= "image/jpg";
    		$data['attached_picture'][0]['mime'] 		= "image/jpg";

	  	$data['attached_picture'][0]['description'] = config('site.name');

	  	$mp3_writter->tag_data = $data;

	  	return $mp3_writter->WriteTags();
	}

	public static function download($music)
	{
		$music->download_count += 1;
		$music->save();

		if ($music->name) {
			$mp3name = storage_path('app/public/' . $music->name);
		} else {
			$mp3name = public_path(config('site.defaultMP3URL'));
		}

		header('Content-Description: File Transfer');
    	header('Content-Type: application/octet-stream');
    	header('Content-Disposition: attachment; filename=' . $music->fullTitle . '.mp3' );
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header('Content-Length: ' . filesize($mp3name) );
    	readfile($mp3name) ;
    	exit;
	}


	public static function firstName($name)
	{
		return explode(' ', $name)[0];
	}

	public static function tweet($obj, $type)
	{
		if ( $type === 'music' ) {
			$status = '#NouvoMizik ';
		} elseif ( $type === 'video' ) {
			$status = '#NouvoVideyo ';
		}

		$author = $obj->user->username ? '@' . $obj->user->username . ' &mdash;' : $obj->user->name . ' -- ';

		$status .= "$author $obj->title " . $obj->url .
					" via @TKPMizik @TiKwenPam #" . $obj->category->slug;

	        	Twitter::postTweet([
	        		'status' => $status,
	        		'format' => 'json'
	        	]);
	}

	public static function profileImage($user)
	{
		if ($user->image) {
			return MP3Pam::asset($user->image, 'thumbs');
		} elseif ($user->avatar) {
			return $user->avatar;
		} else {
			return MP3Pam::asset(config('site.logo'));
		}
	}

	public static function getHash($model)
	{
		do {
			$hash = rand(00000000, 99999999);
		} while ($model::whereHash($hash)->first());

		return $hash;
	}

	public static function cache($key, $fn, $time = null)
	{
		if (env('CACHE', false)) {
			// generate new key variable depending on the page
			// the user is viewing. And set a default key in case
			// they are not viewing a paginated page
			$page = request()->has('page') ? request('page') : 1;
			$newKey = $key . $page;

			// Cache any kind of content of any model
			return Cache::get($newKey, function() use ($newKey, $fn, $time) {
				$value = $fn();

				if ($time) {
					Cache::put($newKey, $value, $time);
				} else {
					Cache::forever($newKey, $value);
				}

				return $value;
			});
		}

		return $fn();
	}

	public static function getUserFromToken()
	{
		try {
		 	if (! $user = JWTAuth::parseToken()->authenticate()) {
		      		return response()->json(['user_not_found'], 404);
		 	}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			  return response()->json(['token_expired'], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			  return response()->json(['token_invalid'], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
			  return response()->json(['token_absent'], $e->getStatusCode());
		}

		return $user;
	}
}
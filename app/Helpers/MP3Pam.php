<?php

namespace App\Helpers;

use App;
use Auth;
use Cache;
use Storage;
use Image;
use Swap;

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
			'null'		=> ''
		];

		$relativeUrl = $imgSize[$size] . $asset;

		if (App::isLocal()) {
			return asset($relativeUrl);
		}

		$cdnUrl = 'https://cache.haitiprojet.com';

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
		return 'user_' . auth()->id() . '/' . date('Y/m/d');
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

	public static function number($number)
	{
		return number_format($number, 2, '.', ',');
	}

	public static function convertRate($amount, $from = 'HTG', $to = 'USD')
	{
		$rate = Swap::latest("$from/$to")->getValue();

		return $rate * $amount;
	}

	public static function usdToHTG($amount)
	{
		return static::convertRate($amount, 'USD', 'HTG');
	}

	public static function getRate($amount, $from = 'HTG', $to = 'USD')
	{
		return Swap::latest("$from/$to")->getValue();
	}
}
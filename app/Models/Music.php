<?php

namespace App\Models;

use Storage;
use Carbon\Carbon;
use App\Helpers\MP3Pam;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
	protected $appends = [
		'url',
		'play_url',
		'image_url',
		'download_url'
	];
	// protected $with = ['user'];

	protected $fillable = [
		'title',
		'hash',
		'name',
		'image',
		'detail',
		'user_id',
		'artist_id',
		'category_id',
		'description',
		'size',
	];

	protected $hidden = [
		'id',
		'updated_at',
		'user_id',
		'artist_id',
		'category_id',
		'publish',
		'name',
		'image',
		'hash',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function artist()
	{
		return $this->belongsTo(Artist::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function scopeByHash($query, $hash)
	{
		return $query->where('hash', $hash);
	}

	public function scopeByCategory($query, $category)
	{
		$query->where('category_id', $category->id);
	}

	public function scopeSearch($query, $ids, $term)
	{
		// $query->whereIn('id', $ids)
		$query
			->where('name', 'like', "%$term%")
			->orWhere('artist', 'like', "%$term%")
			->orderBy('download', 'desc')
			->orderBy('views', 'desc')
			->take(20);
	}

	public function scopeRand($query)
	{
		$query->orderByRaw('RAND()');
	}

	public function scopeRelated($query, $obj, $nb_rows = 6)
	{
		$query
			->whereCategoryId($obj->category_id)
			->where('id', '!=', $obj->id)
			->orderByRaw('RAND()') // get random rows from the DB
			->published()
			->take($nb_rows);
	}

	public function scopeLastMonth($query)
	{
		$today = Carbon::today();
		$lastMonth = Carbon::today()->subMonth();

		$query
			->where('created_at',  '<',  $today)
			->where('created_at', '>', $lastMonth);
	}

	public function scopePopular($query)
	{
		$query
			->orderBy('download', 'desc')
			->orderBy('play', 'desc');
	}

	public function scopeFeatured($query)
	{
		$query->whereFeatured(1);
	}

	public function scopePublished($query)
	{
		$query->wherePublish(1);
	}

	public function scopeByPlay($query)
	{
		$query->orderBy('play', 'desc');
	}

	public function getFullTitleAttribute()
	{
		return $this->artist->name . ' - ' . $this->title;
	}

	public function getUrlAttribute()
	{
		return MP3Pam::route('music.show', ['hash' => $this->hash]);
	}

	public function getMp3UrlAttribute()
	{
		$mp3Path = config('site.defaultMP3URL');
		if ($this->name) $mp3Path = Storage::url($this->name);

		return MP3Pam::asset($mp3Path);
	}

	public function getPlayUrlAttribute()
	{
		return MP3Pam::route('music.play', ['id' => $this->id]);
	}

	public function getImageUrlAttribute()
	{
		$imagePath = config('site.defaultThumbnail');
		if ($this->image) $imagePath = Storage::url($this->image);

		return MP3Pam::asset($imagePath);
	}

	public function scopeUrl()
	{
		return MP3Pam::route('music.show', ['id' => $this->id, 'slug' => $this->slug]);
	}

	public function getDownloadUrlAttribute()
	{
		return MP3Pam::route('musics.get', ['music' => $this->hash]);
	}


	// // TNTSearch
	// public static function insertToIndex($mp3)
	// {
	//     TNTSearch::selectIndex("mp3s.index");
	//     $index = TNTSearch::getIndex();
	//     $index->insert($mp3->toArray());
	// }

	// public static function deleteFromIndex($mp3)
	// {
	//     TNTSearch::selectIndex("mp3s.index");
	//     $index = TNTSearch::getIndex();
	//     $index->delete($mp3->id);
	// }

	// public static function updateIndex($mp3)
	// {
	//     TNTSearch::selectIndex("mp3s.index");
	//     $index = TNTSearch::getIndex();
	//     $index->update($mp3->id, $mp3->toArray());
	// }

	// public static function boot()
	// {
	// 	parent::boot();

	// 	static::created([__CLASS__, 'insertToIndex']);
	// 	static::updated([__CLASS__, 'updateIndex']);
	// 	static::deleted([__CLASS__, 'deleteFromIndex']);
	// }
}
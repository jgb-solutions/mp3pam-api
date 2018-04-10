<?php

namespace App\Models;

use Storage;
use Carbon\Carbon;
use App\Helpers\MP3Pam;
use Laravel\Scout\Searchable;

class Music extends BaseModel
{
	use Searchable;

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

	public function scopeSearch($query, $term)
	{
		// $query->whereIn('id', $ids)
		$query->where('title', 'like', "%$term%")
			->orWhere('detail', 'like', "%$term%")
			->orWhere('lyrics', 'like', "%$term%");
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
			->orderBy('download_count', 'desc')
			->orderBy('play_count', 'desc');
	}

	public function scopeFeatured($query)
	{
		$query->whereFeatured(1);
	}

	public function scopePublished($query)
	{
		$query->wherePublish(1);
	}

	public function isPublished()
	{
		return $this->publish === 1;
	}

	public function scopeByPlay($query)
	{
		$query->orderBy('play_count', 'desc');
	}

	public function getFullTitleAttribute()
	{
		return $this->artist->name . ' - ' . $this->title;
	}

	public function getUrlAttribute()
	{
		return MP3Pam::route('api.musics.show', ['hash' => $this->hash]);
	}

	public function getPublicUrlAttribute()
	{
		return MP3Pam::route('musics.show', ['hash' => $this->hash]);
	}

	public function getMp3UrlAttribute()
	{
		$mp3Path = config('site.defaultMP3URL');

		if ($this->file_name){
			$mp3Path = Storage::url($this->file_name);
		}

		return MP3Pam::asset($mp3Path);
	}

	public function getPlayUrlAttribute()
	{
		return MP3Pam::route('api.musics.play', ['id' => $this->id]);
	}

	public function getImageUrlAttribute()
	{
		$imagePath = config('site.defaultThumbnail');
		if ($this->image) $imagePath = Storage::url($this->image);

		return MP3Pam::asset($imagePath);
	}

	public function scopeUrl()
	{
		return MP3Pam::route('api.musics.show', ['id' => $this->id, 'slug' => $this->slug]);
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


	// Algolia
	public function toSearchableArray()
	{
		return [
         'hash'				=> $this->hash,
			'title'         	=> $this->title,
         'detail'        	=> $this->detail,
         'lyrics'        	=> $this->lyrics,
         // 'play_url'    		=> $this->play_url,
         'image'				=> $this->image_url,
         'publicUrl'       => $this->public_url,
         'url'       		=> $this->url,
         // 'play_count'    	=> $this->play_count,
         'download_url'  	=> $this->download_url,
         // 'download_count'	=> $this->download_count,
         'category'      	=> $this->category->name,
         'artist' 			=> $this->artist->stage_name
		];
	}

	// public function shouldBeSearchable()
	// {
	// 	return $this->isPublished();
	// }
}
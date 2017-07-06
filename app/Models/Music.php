<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\MP3Pam;

class Music extends Model
{
	// protected $appends = [
	// 	'url',
	// 	'title',
	// 	'mp3',
	// 	'poster',
	// 	'download_url',
	// 	'emailAndTweetUrl'
	// ];
	// protected $with = ['user'];

	protected $fillable = [
		'title',
		'name',
		'image',
		'user_id',
		'description',
		'category_id',
		'size',
		'slug'
	];

	// public function artist()
	// {
	// 	return $this->belongsTo(Artist::class);
	// }

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
			->orderBy('views', 'desc');
	}

	public function scopeFeatured($query)
	{
		$query->whereFeatured(1);
	}

	public function getEmailAndTweetUrlAttribute()
	{
		return MP3Pam::route('music.emailAndTweet', ['id'=>$this->id]);
	}

	public function scopePublished($query)
	{
		$query->wherePublish(1);
	}

	public function scopePaid($query)
	{
		$query->wherePrice('paid');
	}

	public function scopeByPlay($query)
	{
		$query->orderBy('play', 'desc');
	}

	public function getUrlAttribute()
	{
		return MP3Pam::route('music.show', ['id' => $this->id,'name' => $this->slug ]);
	}

	public function getMp3Attribute()
	{
		return MP3Pam::route('music.play', ['id' => $this->id]);
	}

	public function getPosterAttribute()
	{
		return $this->imageUrl;
	}

	public function getImageUrlAttribute()
	{
		return url(MP3Pam::asset($this->image, 'show'));
	}

	public function scopeUrl()
	{
		return MP3Pam::route('music.show', ['id' => $this->id, 'slug' => $this->slug]);
	}

	public function getDownloadUrlAttribute()
	{
		return MP3Pam::route('music.get', ['music' => $this->id]);
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
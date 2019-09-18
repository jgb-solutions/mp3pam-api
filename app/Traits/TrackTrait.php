<?php

namespace App\Traits;

use App\Helpers\MP3Pam;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Track;
use App\Models\Artist;
use App\Models\Category;

trait TrackTrait
{
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

	public function getTitleAttribute()
	{
		$separator = $this->artist ? ' - ' : '';
		return $this->artist . $separator . $this->name;
	}

	public function scopeFeatured($query)
	{
		$query->whereFeatured(1);
	}

	public function getEmailAndTweetUrlAttribute()
	{
		return MP3Pam::route('track.emailAndTweet', ['id'=>$this->id]);
	}
}
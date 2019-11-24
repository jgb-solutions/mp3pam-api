<?php

namespace App\Models;

use Storage;
use App\Helpers\MP3Pam;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends BaseModel
{
	// use Searchable;

	protected $guarded = [];

	public function tracks(): HasMany
	{
		return $this->hasMany(Track::class);
	}

	public function getCountAttribute()
	{
		return $this->tracks()->count();
	}

	public function scopebyName($query)
	{
		$query->orderBy('name');
	}

	public function scopeSearch($query, $term)
	{
		// $query->whereIn('id', $ids)
		$query->where('name', 'like', "%$term%")
			->orWhere('stageName', 'like', "%$term%")
			->orWhere('bio', 'like', "%$term%");
	}

	public function getAvatarUrlAttribute()
	{
		$avatarPath = config('site.defaultThumbnail');
		if ($this->avatar) $avatarPath = Storage::url($this->avatar);

		return MP3Pam::asset($avatarPath);
	}

	public function getUrlAttribute()
	{
		return MP3Pam::route('artists.show', ['hash' => $this->hash]);
	}

	public function scopeByHash($query, $hash)
	{
		$query->where('hash', $hash);
	}

	public function getTracksUrlAttribute()
	{
		return MP3Pam::route('artists.tracks', ['hash' => $this->hash]);
	}

	// Algolia
	public function toSearchableArray()
	{
		return [
			'name'		=> $this->name,
         'stageName'	=> $this->stage_name,
         'hash'		=> $this->hash,
         'avatar'		=> $this->avatar_url,
         'tracks'		=> $this->tracks_url,
         'bio'			=> $this->bio
		];
	}
}
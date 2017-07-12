<?php

namespace App\Models;

use Storage;
use App\Helpers\MP3Pam;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
	protected $fillable = ['name', 'slug'];

	protected $hidden = [
		'id',
		'hash',
		'updated_at',
		'created_at',
	];

	protected $appends = ['avatar_url', 'url', 'musics_url'];

	public function musics()
	{
		return $this->hasMany(Music::class);
	}

	public function getCountAttribute()
	{
		return $this->musics()->count();
	}

	public function scopebyName($query)
	{
		$query->orderBy('name');
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

	public function getMusicsUrlAttribute()
	{
		return MP3Pam::route('artists.musics', ['hash' => $this->hash]);
	}
}
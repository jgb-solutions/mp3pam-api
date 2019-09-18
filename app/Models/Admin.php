<?php

namespace App\Models;

use Storage;
use App\Helpers\MP3Pam;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'username', 'email', 'password', 'avatar', 'telephone'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */

	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
		'password', 'remember_token',
	];

	protected $appends = ['avatar_url', 'tracks_url'];

	public function tracks()
	{
		return $this->hasMany(Track::class);
	}

	public function getAvatarUrlAttribute()
	{
		if (! empty($this->facebook_id)) {
			return $this->avatar;
		}

		$avatarPath = config('site.defaultAvatar');

		if (!empty($this->avatar)) {
			$avatarPath = Storage::url($this->avatar);
		}

		return MP3Pam::asset($avatarPath);
	}

	public function getTracksUrlAttribute()
	{
		return MP3Pam::route('users.tracks', ['id' => $this->id]);
	}
}

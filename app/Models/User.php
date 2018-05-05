<?php

namespace App\Models;

use Storage;
use App\Helpers\MP3Pam;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
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

	protected $appends = ['avatar_url', 'musics_url'];

	public function likedMusics()
	{
		return $this->belongsToMany(Music::class, 'liked_musics')->withTimestamps();
	}

	public function hasLiked($music)
	{
		return $this->likedMusics()->wherePivot('music_id', $music->id)->exists();
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

	public function getMusicsUrlAttribute()
	{
		return MP3Pam::route('users.musics', ['id' => $this->id]);
	}

	/**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

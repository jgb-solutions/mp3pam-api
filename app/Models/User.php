<?php

namespace App\Models;

use App\Helpers\MP3Pam;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopeAdmin($query)
    {
        return $query->whereAdmin(1);
    }

    public function likedTracks()
    {
        return $this->belongsToMany(Track::class, 'liked_tracks')->withTimestamps();
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class);
    }

    public function hasLiked($track)
    {
        return $this->likedTracks()->wherePivot('track_id', $track->id)->exists();
    }

    public function artists(): HasMany
    {
        return $this->hasMany(Artist::class);
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function artists_by_stage_name_asc(): HasMany
    {
        return $this->hasMany(Artist::class)->orderBy('stage_name');
    }

    public function getAvatarUrlAttribute()
    {
        if (!empty($this->facebook_id)) {
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

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            $query->orderBy('created_at', 'desc');
        });
    }
}

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
    protected $guarded = ['id'];

    protected $casts = [
      'first_login' => 'boolean',
    ];

    public function scopeIsAdmin($query)
    {
      return $query->whereAdmin(1);
    }

    public function setPasswordAttribute($value)
    {
      $this->attributes['password'] = bcrypt($value);
    }

    public function likedTracks()
    {
      return $this->belongsToMany(Track::class, 'liked_tracks')->withTimestamps();
    }

    public function tracks(): HasMany
    {
      return $this->hasMany(Track::class)->orderBy('created_at', 'DESC');
    }

    public function hasLiked($track)
    {
      return $this->likedTracks()->wherePivot('track_id', $track->id)->exists();
    }

    public function artists(): HasMany
    {
      return $this->hasMany(Artist::class)->orderBy('created_at', 'DESC');
    }

    public function albums(): HasMany
    {
      return $this->hasMany(Album::class)->orderBy('created_at', 'DESC');
    }

    public function artists_by_stage_name_asc(): HasMany
    {
      return $this->hasMany(Artist::class)->orderBy('stage_name');
    }

    public function getAvatarUrlAttribute()
    {
      if ($this->avatar) {
        return 'https://' . $this->img_bucket . '/' . $this->avatar;
      }

      return $this->fb_avatar;
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

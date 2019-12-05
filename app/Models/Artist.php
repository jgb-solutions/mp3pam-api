<?php

  namespace App\Models;

  use Storage;
  use App\Traits\HelperTrait;
  use App\Helpers\MP3Pam;
  use Laravel\Scout\Searchable;
  use Illuminate\Database\Eloquent\Relations\HasMany;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;

  class Artist extends BaseModel
  {
    use HelperTrait;

    private $default_poster_url = "https://img-storage-prod.mp3pam.com/placeholders/artist-placeholder.jpg";

    protected $guarded = [];

    public function tracks(): HasMany
    {
      return $this->hasMany(Track::class);
    }

    public function setBioAttribute($bio)
    {
      $this->attributes['bio'] = nl2br($bio);
    }

    public function getCountAttribute()
    {
      return $this->tracks()->count();
    }

    public function scopebyName($query)
    {
      $query->orderBy('name');
    }

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);
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

    public function scopeByHash($query, $hash)
    {
      $query->where('hash', $hash);
    }

    public function getFacebookUrlAttribute()
    {
      return $this->getSocialNetworkUrl("facebook");
    }


    public function getTwitterUrlAttribute()
    {
      return $this->getSocialNetworkUrl("twitter");
    }


    public function getInstagramUrlAttribute()
    {
      return $this->getSocialNetworkUrl("instagram");
    }

    public function getYouTubeUrlAttribute()
    {
      return $this->getSocialNetworkUrl("youtube");
    }

    private function getSocialNetworkUrl($social_network)
    {
      switch ($social_network) {
        case "facebook":
          return $this->makeSocialNetworkUrl("https://www.facebook.com/", $this->facebook);
          break;
        case "twitter":
          return $this->makeSocialNetworkUrl("https://www.twitter.com/", $this->twitter);
          break;
        case "instagram":
          return $this->makeSocialNetworkUrl("https://www.instagram.com/", $this->instagram);
          break;
        case "youtube":
          return $this->makeSocialNetworkUrl("https://www.youtube.com/", $this->youtube);
          break;
      }
    }

    private function makeSocialNetworkUrl($link, $username)
    {
      if ($username) {
        return filter_var($username, FILTER_VALIDATE_URL) ?
          $username :
          $link . $username;
      } else {
        return null;
      }
    }
  }
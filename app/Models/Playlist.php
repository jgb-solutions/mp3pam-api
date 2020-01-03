<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\HasMany;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;
  use Illuminate\Database\Eloquent\Relations\HasOneThrough;

  class Playlist extends BaseModel
  {
    private $default_poster_url = "https://img-storage-prod.mp3pam.com/placeholders/playlist-placeholder.png";

    protected $guarded = [];

    public function scopeRandom($query, $hash)
    {
      $query
        ->where('hash', '!=', $hash)
        ->orderByRaw('RAND()'); // get random rows from the DB
    }

    public function scopeHasTracks($query)
    {
      return $query->has('trackList');
    }

    public function getCoverUrlAttribute()
    {
      $track = $this->tracks()->first();

      if ($track) {
        return $track->poster_url;
      } else {
        return $this->default_poster_url;
      }
    }

    public function trackList(): HasMany
    {
      return $this->hasMany(PlaylistTrack::class);
    }

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);
    }

    public function tracks(): BelongsToMany
    {
      return $this->belongsToMany(Track::class)
        ->withTimestamps()
        ->orderBy('playlist_track.created_at', 'asc');
    }
  }
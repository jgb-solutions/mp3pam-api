<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\HasMany;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;
  use Illuminate\Database\Eloquent\Relations\HasOneThrough;

  class Playlist extends BaseModel
  {
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
      return $this->tracks()->first()->poster_url;
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
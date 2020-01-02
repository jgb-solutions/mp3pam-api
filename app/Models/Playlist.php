<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;

  class Playlist extends BaseModel
  {
    protected $guarded = [];

    public function scopeHasTracks($query)
    {
      return $query->has('tracks');
    }

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);
    }

    public function tracks(): BelongsToMany
    {
      return $this->belongsToMany(Track::class)
        ->withTimestamps()
        ->orderBy('playlist_track.created_at');
    }
  }
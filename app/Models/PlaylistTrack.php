<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Relations\BelongsTo;

  class PlaylistTrack extends BaseModel
  {
    protected $table = 'playlist_track';
    protected $guarded = [];


    public function playlist(): BelongsTo
    {
      return $this->belongsTo(Playlist::class);
    }
  }
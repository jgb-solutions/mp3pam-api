<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Relations\HasMany;
  use Laravel\Scout\Searchable;
  use App\Traits\HelperTrait;
  use Carbon\Carbon;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;
  use Storage;

  class Track extends BaseModel
  {
    use Searchable;
    use HelperTrait;

    private $default_poster_url = "https://img-storage-prod.mp3pam.com/placeholders/track-placeholder.jpg";

    protected $guarded = [];

    protected $casts = [
      'allowDownload' => 'boolean',
    ];

    public function trackList(): HasMany
    {
      return $this->hasMany(PlaylistTrack::class);
    }

    public function playlists(): BelongsToMany
    {
      return $this->belongsToMany(Playlist::class)
        ->withTimestamps()
        ->orderBy('playlist_track.created_at', 'desc');
    }

    public function artist(): BelongsTo
    {
      return $this->belongsTo(Artist::class);
    }

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);
    }

    public function genre(): BelongsTo
    {
      return $this->belongsTo(Genre::class);
    }

    public function album(): BelongsTo
    {
      return $this->belongsTo(Album::class);
    }

    public function setDetailAttribute($detail)
    {
      $this->attributes['detail'] = nl2br($detail);
    }

    public function setLyricsAttribute($lyrics)
    {
      $this->attributes['lyrics'] = nl2br($lyrics);
    }


    public function scopeByHash($query, $hash)
    {
      return $query->where('hash', $hash);
    }

    public function scopeByGenre($query, $genre_slug)
    {
      $genre = Genre::whereSlug($genre_slug)->first();

      $query->where('genre_id', $genre->id);
    }

    public function scopeRand($query)
    {
      $query->orderByRaw('RAND()');
    }

    public function scopeRelated($query, $obj)
    {
      $query
        ->whereGenreId($obj->genre_id)
        ->where('id', '!=', $obj->id)
        ->orderByRaw('RAND()'); // get random rows from the DB
//        ->published()
    }

    public function scopeLastMonth($query)
    {
      $today     = Carbon::today();
      $lastMonth = Carbon::today()->subMonth();

      $query
        ->where('created_at', '<', $today)
        ->where('created_at', '>', $lastMonth);
    }

    public function scopePopular($query)
    {
      $query
        ->orderBy('download_count', 'desc')
        ->orderBy('play_count', 'desc');
    }

    public function scopeFeatured($query)
    {
      $query->whereFeatured(1);
    }

    public function scopePublished($query)
    {
      $query->wherePublish(1);
    }

    public function isPublished()
    {
      return $this->publish === 1;
    }

    public function scopeByPlay($query)
    {
      $query->orderBy('play_count', 'desc');
    }

    public function toSearchableArray()
    {
      extract($this->toArray());

      return compact('id', 'title', 'detail', 'lyrics');
    }
  }

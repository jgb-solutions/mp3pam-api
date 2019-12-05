<?php

  namespace App\Models;

  use App\Traits\HelperTrait;
  use App\Helpers\MP3Pam;
  use Carbon\Carbon;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Storage;

  class Track extends BaseModel
  {
    use HelperTrait;

    private $default_poster_url = "https://img-storage-prod.mp3pam.com/placeholders/track-placeholder.jpg";

    protected $guarded = [];

    public function peopleWhoFavoured()
    {
      return $this->belongsToMany(User::class, 'liked_tracks')->withTimestamps();
    }

    public function artist(): BelongsTo
    {
      return $this->belongsTo(Artist::class);
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

    public function scopeByGenre($query, $genre)
    {
      $query->where('genre_id', $genre->id);
    }

    public function scopeSearch($query, $term)
    {
      // $query->whereIn('id', $ids)
      $query->where('title', 'like', "%$term%")
        ->orWhere('detail', 'like', "%$term%")
        ->orWhere('lyrics', 'like', "%$term%");
    }

    public function scopeRand($query)
    {
      $query->orderByRaw('RAND()');
    }

    public function scopeRelated($query, $obj, $nb_rows = 6)
    {
      $query
        ->whereGenreId($obj->genre_id)
        ->where('id', '!=', $obj->id)
        ->orderByRaw('RAND()')// get random rows from the DB
        ->published()
        ->take($nb_rows);
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
  }

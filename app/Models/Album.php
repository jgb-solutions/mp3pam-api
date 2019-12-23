<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Traits\HelperTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends BaseModel
{
  use Searchable;
  use HelperTrait;

  private $default_poster_url = "https://img-storage-prod.mp3pam.com/placeholders/album-placeholder.jpg";

  protected $guarded = [];

  public function scopeHasTracks($query)
  {
    return $query->has('tracks');
  }

  public function scopeRandom($query, $hash)
  {
    $query
      ->where('hash', '!=', $hash)
      ->orderByRaw('RAND()'); // get random rows from the DB
  }

  public function peopleWhoFavoured()
  {
      return $this->belongsToMany(User::class, 'liked_tracks')->withTimestamps();
  }

  public function artist(): BelongsTo
  {
      return $this->belongsTo(Artist::class);
  }

  public function user(): BelongsTo
  {
      return $this->belongsTo(User::class);
  }

  public function tracks(): HasMany
  {
    return $this->hasMany(Track::class)->orderBy('number', 'ASC');
  }

  public function setDetailAttribute($detail)
  {
    $this->attributes['detail'] = nl2br($detail);
  }

  public function toSearchableArray()
  {
    extract($this->toArray());

    return compact('id', 'title', 'detail');
  }
}

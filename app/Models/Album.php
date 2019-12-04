<?php

namespace App\Models;

use App\Traits\HelperTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends BaseModel
{
  use HelperTrait;

  private $default_poster_url = "https://img-storage-prod.mp3pam.com/placeholders/album-placeholder.jpg";

  protected $guarded = [];

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
    return $this->hasMany(Track::class);
  }
}

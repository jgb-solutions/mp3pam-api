<?php

  namespace App\Models;

  use App\Helpers\MP3Pam;
  use Illuminate\Database\Eloquent\Relations\HasMany;

  class Genre extends BaseModel
  {
    protected $guarded = [];

    public $timestamps = false;

    public function scopeHasTracks($query)
    {
      return $query->has('tracks');
    }

    public function tracks(): HasMany
    {
      return $this->hasMany(Track::class);
    }

    public function getCountAttribute()
    {
      return $this->track()->count();
    }

    public function scopebyName($query)
    {
      $query->orderBy('name');
    }

    public function getUrlAttribute()
    {
      return MP3Pam::route('category.show', ['slug' => $this->slug]);
    }

    public static function allCategories()
    {
      return MP3Pam::cache('allCategories', function () {
        return static::withCount('track')->orderBy('name')->get();
      });
    }
  }

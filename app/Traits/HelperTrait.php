<?php

  namespace App\Traits;

  use Carbon\Carbon;

  trait HelperTrait
  {
    public function scopeRand($query)
    {
      $query->orderByRaw('RAND()');
    }

    public function scopeRelated($query, $obj, $nb_rows = 6)
    {
      $query
        ->whereCategoryId($obj->category_id)
        ->where('id', '!=', $obj->id)
        ->orderByRaw('RAND()')// get random rows from the DB
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
        ->orderBy('download', 'desc')
        ->orderBy('views', 'desc');
    }


    public function scopeFeatured($query)
    {
      $query->whereFeatured(1);
    }

    public static function makeFileUrl($bucket, $filePath)
    {

    }

    public function getPosterUrlAttribute()
  {
    if ($this->poster) {
      return "https://" . $this->img_bucket . '/' . $this->poster;
    } else {
      return $this->default_poster_url;
    }
  }

    public function getCoverUrlAttribute()
    {
      if ($this->cover) {
        return "https://" . $this->img_bucket . '/' . $this->cover;
      } else {
        return $this->default_poster_url;
      }
    }

    public function getAudioUrlAttribute()
    {
      return "https://" . $this->audio_bucket . '/' . $this->audio_name;
    }
  }
<?php

  namespace App\Traits;

  use App\Helpers\MP3Pam;
  use Carbon\Carbon;

  trait HelperTrait
  {
    public function scopeRand($query)
    {
      $query->inRandomOrder();
    }

    public function scopeRelated($query, $obj, $nb_rows = 6)
    {
      $query
        ->whereCategoryId($obj->category_id)
        ->where('id', '!=', $obj->id)
        ->inRandomOrder()// get random rows from the DB
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
      // return "https://" . $this->audio_bucket . '/' . $this->audio_name; if public
      $wasabi   = \Storage::disk('wasabi');

      $client   = $wasabi->getClient();

      $command  = $client->getCommand('GetObject', [
        'Bucket' => $this->audio_bucket,
        'Key' => $this->audio_name,
        'ResponseCacheControl' => 'max-age=86400',
      ]);

      $request = $client->createPresignedRequest($command, "+7 days");

      $url = (string) $request->getUri();

      return $url;
    }

    public function getAudioFileSizeAttribute($size)
    {
      return MP3Pam::size($size);
    }
  }
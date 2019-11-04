<?php

namespace App\Models;

use App\Helpers\MP3Pam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Storage;

class Track extends BaseModel
{
    // use Searchable;

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
            ->orderByRaw('RAND()') // get random rows from the DB
            ->published()
            ->take($nb_rows);
    }

    public function scopeLastMonth($query)
    {
        $today = Carbon::today();
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

    public function getFullTitleAttribute()
    {
        return $this->artist->name . ' - ' . $this->title;
    }

    public function getUrlAttribute()
    {
        return MP3Pam::route('api.tracks.show', ['patih' => $this->hash]);
    }

    public function getPublicUrlAttribute()
    {
        return MP3Pam::route('tracks.show', ['hash' => $this->hash]);
    }

    public function getMp3UrlAttribute()
    {
        $mp3Path = config('site.defaultMP3URL');

        if ($this->file_name) {
            $mp3Path = Storage::url($this->file_name);
        }

        return MP3Pam::asset($mp3Path);
    }

    public function getPlayUrlAttribute()
    {
        return MP3Pam::route('tracks.play', ['hash' => $this->hash]);
    }

    public function getImageUrlAttribute()
    {
        $imagePath = config('site.defaultThumbnail');
        if ($this->image) {
            $imagePath = Storage::url($this->image);
        }

        // return MP3Pam::asset($imagePath);

        return 'http://via.placeholder.com/500x500';
    }

    public function scopeUrl()
    {
        return MP3Pam::route('api.tracks.show', ['id' => $this->id, 'slug' => $this->slug]);
    }

    public function getDownloadUrlAttribute()
    {
        return MP3Pam::route('tracks.get', ['track' => $this->hash]);
    }

    // // TNTSearch
    // public static function insertToIndex($mp3)
    // {
    //     TNTSearch::selectIndex("mp3s.index");
    //     $index = TNTSearch::getIndex();
    //     $index->insert($mp3->toArray());
    // }

    // public static function deleteFromIndex($mp3)
    // {
    //     TNTSearch::selectIndex("mp3s.index");
    //     $index = TNTSearch::getIndex();
    //     $index->delete($mp3->id);
    // }

    // public static function updateIndex($mp3)
    // {
    //     TNTSearch::selectIndex("mp3s.index");
    //     $index = TNTSearch::getIndex();
    //     $index->update($mp3->id, $mp3->toArray());
    // }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::created([__CLASS__, 'insertToIndex']);
    //     static::updated([__CLASS__, 'updateIndex']);
    //     static::deleted([__CLASS__, 'deleteFromIndex']);
    // }

    // Algolia
    public function toSearchableArray()
    {
        return [
            'hash' => $this->hash,
            'title' => $this->title,
            'detail' => $this->detail,
            'lyrics' => $this->lyrics,
            // 'play_url'            => $this->play_url,
            'image' => $this->image_url,
            'publicUrl' => $this->public_url,
            'url' => $this->url,
            // 'play_count'        => $this->play_count,
            'download_url' => $this->download_url,
            // 'download_count'    => $this->download_count,
            'genre' => $this->genre->name,
            'artist' => $this->artist->stage_name,
        ];
    }

    // public function shouldBeSearchable()
    // {
    //     return $this->isPublished();
    // }
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use TKPM;
use Illuminate\Support\Collection;

class Playlist extends BaseModel
{
	protected $appends = ['url'];
	protected $fillable = ['name', 'slug', 'user_id', 'track_list_id'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function mList(): HasMany
	{
		return $this->hasMany(TrackList::class);
	}

	public function getTracksAttribute(): HasMany
	{
		$ids = $this->mList()->pluck('track_id')->toArray();

		$tracks = Track::find($ids, ['id', 'artist', 'name', 'image', 'slug']);

		$sorted = array_flip($ids);

		foreach ($tracks as $track) {
			$sorted[$track->id] = $track;
		}

		$sorted = Collection::make(array_values($sorted));

		return $sorted;
	}

	public function getUrlAttribute()
	{
		return TKPM::route('playlist.show', ['id' => $this->id,'name' => $this->slug ]);
	}
}
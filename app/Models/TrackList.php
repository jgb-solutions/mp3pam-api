<?php

namespace App\Models;

class TrackList extends BaseModel
{
	protected $fillable = ['playlist_id', 'track_id'];

	public function playlist()
	{
		return $this->belongsTo(Playlist::class);
	}
}
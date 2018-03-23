<?php

namespace App\Models;

class MusicList extends BaseModel
{
	protected $fillable = ['playlist_id', 'music_id'];

	public function playlist()
	{
		return $this->belongsTo(Playlist::class);
	}
}
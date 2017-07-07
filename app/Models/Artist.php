<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
	protected $fillable = ['name', 'slug'];

	protected $hidden = [
		'id',
		'updated_at',
		'created_at',
	];

	public function musics()
	{
		return $this->hasMany(Music::class);
	}

	public function getCountAttribute()
	{
		return $this->musics()->count();
	}

	public function scopebyName($query)
	{
		$query->orderBy('name');
	}
}
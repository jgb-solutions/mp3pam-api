<?php

namespace App\Models;

use App\Helpers\MP3Pam;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $table = 'categories';

	protected $fillable = ['name', 'slug'];

	protected $hidden = ['id'];

	protected $appends = ['url'];

	public $timestamps = false;

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

	public function getUrlAttribute()
	{
		return MP3Pam::route('category.show', ['slug' => $this->slug]);
	}

	public static function allCategories()
	{
		return MP3Pam::cache('allCategories', function() {
			return static::orderBy('name')->get();
		});
	}
}
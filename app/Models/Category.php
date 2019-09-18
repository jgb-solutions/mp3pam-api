<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Helpers\MP3Pam;

class Category extends BaseModel
{
	protected $table = 'categories';

	protected $fillable = ['name', 'slug'];

	protected $hidden = ['id'];

	protected $appends = ['url'];

	public $timestamps = false;

	public function track(): HasMany
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
		return MP3Pam::cache('allCategories', function() {
			return static::withCount('track')->orderBy('name')->get();
		});
	}
}
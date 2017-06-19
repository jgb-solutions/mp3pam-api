<?php

namespace App\Models;

use HP, Storage;
// use App\Traits\HaitiProjetTrait;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	protected $dates = ['published_at'];

	protected $with = ['category'];
	// use HaitiProjetTrait;

	protected $fillable = [
		'name', 'slug',
		'description', 'user_id',
		'category_id', 'budget', 'logo', 'doc'
	];

	protected $appends = [
		'categoryUrl',
		'logoUrl',
		'docUrl',
		'docExt',
		'url',
	];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function funders()
	{
		return $this->belongsToMany(User::class, 'funds')->withPivot('amount')->withTimestamps();
	}

	public function percentageReceived()
	{
		return HP::number($this->fundedPercentageFor($this->received));
	}

	// public function setExcerptAttribute($value)
	// {
	//     $this->attributes['excerpt'] = str_limit($value, config('site.excerpt'), $end = '...');
	// }

	public function getUrlAttribute()
	{
		return HP::route("project.show", $this->slug);
	}

	public function scopeRelated($query, $obj, $nb_rows = 6)
	{
		$query->whereCategoryId($obj->category_id)
			->where('id', '!=', $obj->id)
			// ->orderByRaw('RAND()') // get random rows from the DB. Old way of doing it
			->inRandomOrder() // get random rows from the DB. New way of doing it. It also supports SQLite
			->take($nb_rows);
	}

	public function scopeSearch($query, $term)
	{
		$query->where('name', 'like', "%$term%")
			->orWhere('details', 'like', "%$term%");
	}

	public function getCategoryUrlAttribute()
	{
		return HP::route('category.show', $this->category->slug);
	}

	public function getEditUrlAttribute()
	{
		return HP::route('project.edit', $this->id);
	}

	public function getDeleteUrlAttribute()
	{
		return HP::route('project.delete',$this->id);
	}

	public function getLogoUrlAttribute()
	{
		$logoPath = config('site.defaultThumbnail');
		if ($this->logo) $logoPath = Storage::url($this->logo);

		return HP::asset($logoPath);
	}

	public function getDocUrlAttribute()
	{
		$docPath = Storage::url($this->doc);

		return HP::asset($docPath);
	}

	public function getDocExtAttribute()
	{
		if (! $doc = $this->doc) return;

		return pathinfo($doc)['extension'];
	}

		public function getFundedPercentageAttribute()
		{
			return $this->received / $this->budget * 100;
		}

		public function fundedPercentageFor($amount)
		{
				return $amount / $this->budget * 100;
		}

		public function isFunded()
		{
			return $this->funded === 1;
		}

	public function getDocIconAttribute()
	{
		if (! $doc = $this->doc) return;

		$ext = pathinfo($doc)['extension'];

		switch ($ext) {
			case 'doc':
				return 'word';
				break;
			case 'docx':
				return 'word';
				break;
			default:
				return 'pdf';
				break;
		}
	}

	public static function boot()
	{
		parent::boot();

		// Attach event handler, on deleting of the project
		Project::deleting(function($project) {
			Storage::delete([$project->logo, $project->doc]);
		});
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Vote extends Model
{
	public $timestamps = false;

	protected $fillable = ['vote', 'user_id', 'obj_id', 'obj'];
}
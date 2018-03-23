<?php

namespace App\Models;

class Vote extends BaseModel
{
	public $timestamps = false;

	protected $fillable = ['vote', 'user_id', 'obj_id', 'obj'];
}
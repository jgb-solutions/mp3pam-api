<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MusicSold extends Model
{
	protected $table = 'sold_mp3s';

	public $timestamps = false;

	protected $fillable = ['mp3_id', 'user_id'];
}
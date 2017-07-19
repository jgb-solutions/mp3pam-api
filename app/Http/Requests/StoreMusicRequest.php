<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMusicRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			// 'music'  => 'required|mimes:mpga|max:64000000',
			'music'     	=> 'required|max:64000000',
			'image' 	=> 'required|image',
			'title' 		=> 'required',
			'category' 	=> 'required',
			'artist'         => 'required',
		];
	}
}
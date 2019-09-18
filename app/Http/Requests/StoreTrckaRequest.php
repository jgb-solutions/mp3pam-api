<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoretrackRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			// 'track'  => 'required|mimes:mpga|max:64000000',
			'track'     	=> 'required|max:64000000',
			'image' 	=> 'required|image',
			'title' 		=> 'required',
			'detail' 	=> 'required',
			'category' 	=> 'required',
			'artist'         => 'required',
		];
	}
}
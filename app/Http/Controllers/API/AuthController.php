<?php

namespace App\Http\Controllers\API\Auth;

use JWTAuth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
	public function register(RegisterFormRequest $request)
	{
		return User::create([
			'name' 		=> $request->name,
			'username' 	=> str_slug($request->name),
			'email' 		=> $request->email,
			'password' 		=> bcrypt($request->password),
			'telephone' 	=> $request->telephone
		]);
	}

	public function login(Request $request)
	{
		try {
			$token = JWTAuth::attempt($request->only('email', 'password'), [
			'exp' => Carbon::now()->addWeek()->timestamp,
			]);
		} catch (JWTException $e) {
			return response()->json([
				'error' => 'Could not authenticate',
			], 500);
		}

		if (!$token) {
			return response()->json([
			'error' => 'Could not authenticate',
			], 401);
		} else {
			$data = [];
			$meta = [];

			$data['name'] = $request->user()->name;
			$meta['token'] = $token;

			return response()->json([
				'data' => $data,
				'meta' => $meta
			]);
		}
	}
}
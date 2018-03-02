<?php

namespace App\Http\Controllers\API\Auth;

use JWTAuth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
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
		// grab credentials from the request
		$credentials = $request->only('email', 'password');

		try {
		   	// attempt to verify the credentials and create a token for the user
		   	if (! $token = JWTAuth::attempt($credentials)) {
		       		return response()->json(['message' => 'Imel oubyen Modpas la pa bon.'], 401);
		   	}
		} catch (JWTException $e) {
		   	// something went wrong whilst attempting to encode the token
		   	return response()->json(['message' => 'Nou pa rive kreye kòd tokenn nan.'], 500);
		}

		// all good so return the token
		$user = JWTAuth::toUser($token);
		return response()->json(compact('token', 'user'));
	}

	public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
	public function handleFacebookConnect()
	{
		$providerUser = Socialite::driver('facebook')->stateless()->user();
		// for example we might do something like... Check if a user exists with the email and if so, log them in.
		// $user = User::firstOrCreate([
		//     'facebook_id' => $providerUser->getId(),
		//     'email' => $providerUser->getEmail(),
		//     'name' => $providerUser->getName(),
		//     'avatar' => $providerUser->getAvatar(),
		// ]);

		$token = JWTAuth::fromUser($user);

		return response()->json([
		    'token' => $token
		]);
		dd($providerUser);
		return 'token nan baz';
		return response()->json([
			'token' => 'askdfasdjfalasdkfasdlfjlasd',
			'user' => 'alskdfjaskdfjakdsfk'
		]);
	}
}
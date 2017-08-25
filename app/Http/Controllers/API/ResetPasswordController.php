<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\MP3Pam;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\RecoverPassword;

class ResetPasswordController extends Controller
{
	public function recover(Request $request)
	{
		$this->validate($request, ['email' => 'required|email']);

		$user = User::where('email', $request->email)->firstOrFail();

		if (! $user->password_reset_code) {
			$user->password_reset_code = str_random(30);
			$user->save();
		}

		$user->notify(new RecoverPassword($user->password_reset_code));

		return response()->json([
			'message' => 'Nou voye yon kòd sou imel ou. Kopye l epi vin kole l la pou reyinisyalize modpas ou a.'
		],  200);
	}

	public function verify(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
			'code'  => 'required'
		]);

		$user = User::where('email', $request->email)->firstOrFail();

		// check recovery table for a match
		if ($user->password_reset_code == $request->code) {
			// code verified. We reset the password reset code field
			$user->password_reset_code = null;
			$user->save();

			// Returned a positive response
			return response()->json([
				'message' => 'Kòd la verifye! Ou ka chanje modpas ou a.'
			],  200);
		}

		return response()->json([
			'message' => 'Nou pa ka verifye kòd ou a. Tanpri verifye kòd nou voye sou imel ou a epi eseye ankò.'
		],  401);
	}

	public function resetPassword(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required|confirmed',
		]);

		$user = User::where('email', $request->email)->firstOrFail();

		$user->update(['passworld' => bcrypt($request->password)]);

		return response()->json(['success' => true]);
	}
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function loginWithgoogle()
	{
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function callbackGoogle()
	{
		try
		{
			$google_user = Socialite::driver('google')->stateless()->user();

			$user = User::where('email', $google_user->getEmail())->first();

			if (!$user)
			{
				$new_user = User::Create([
					'name'     => $google_user->getName(),
					'email'    => $google_user->getEmail(),
					'password' => Hash::make(''),
				]);

				$payload = [
					'exp' => Carbon::now()->addDay()->timestamp,
					'uid' => User::where('name', '=', $google_user->getName())->first()->id,
				];

				$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
				$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return redirect(env('VITE_APP_ROOT'))->withCookie($cookie);
			}
			else
			{
				$payload = [
					'exp' => Carbon::now()->addDay()->timestamp,
					'uid' => User::where('name', '=', $google_user->getName())->first()->id,
				];

				$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
				$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return redirect(env('VITE_APP_ROOT'))->withCookie($cookie);
			}
		}
		catch (\Throwable $th)
		{
			Log::error('Something went wrong!' . $th->getMessage());
		}
	}
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

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
			$googleUser = Socialite::driver('google')->stateless()->user();

			$user = User::where('email', $googleUser->getEmail())->first();
			$token = Str::random(60);
			if (!$user)
			{
				$newUser = User::Create([
					'name'      => $googleUser->getName(),
					'email'     => $googleUser->getEmail(),
					'password'  => Hash::make(''),
					'token'     => $token,
					'thumbnail' => $googleUser->avatar,
					'google_id' => $googleUser->id,
				]);

				$payload = [
					'exp' => Carbon::now()->addDay()->timestamp,
					'uid' => User::where('name', '=', $googleUser->getName())->first()->id,
				];

				$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
				$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return redirect(env('VITE_APP_ROOT_NEWSFEED'))->withCookie($cookie);
			}
			else
			{
				$payload = [
					'exp' => Carbon::now()->addDay()->timestamp,
					'uid' => User::where('email', '=', $googleUser->getEmail())->first()->id,
				];

				$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
				$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return redirect(env('VITE_APP_ROOT_NEWSFEED'))->withCookie($cookie);
			}
		}
		catch (\Throwable $th)
		{
			Log::error('Something went wrong!' . $th->getMessage());
		}
	}
}

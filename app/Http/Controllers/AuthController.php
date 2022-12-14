<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyUserRequest;
use App\Mail\ResetPasswordMail;
use App\Mail\SignupEmail;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$token = Str::random(60);

		$user = User::create([
			'name'      => $request->name,
			'email'     => $request->email,
			'password'  => Hash::make($request->password),
			'token'     => $token,
		]);

		$verifyUser = User::where('email', $request->email)->first();
		Mail::to($verifyUser->email)->send(new SignupEmail($user, $token));

		return response()->json('email sent, user registered');
	}

	public function verify(VerifyUserRequest $request): JsonResponse
	{
		$updated = DB::table('users')->where([
			'email'=> $request->email,
			'token'=> $request->token,
		])->first();

		if (!$updated)
		{
			return response()->json('token not found', 404);
		}

		User::where('email', $request->email)->update([
			'email_verified_at'=> Carbon::now(),
		]);

		$payload = [
			'exp' => Carbon::now()->addDay()->timestamp,
			'uid' => User::where('email', '=', $request->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('verified, logged in')->withCookie($cookie);
	}

	/**
	 * Get a JWT via given credentials.
	 */
	public function login(LoginRequest $request): JsonResponse
	{
		$field = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		$request->merge([$field => $request->input('email')]);

		$authenticated = auth()->attempt(
			[
				$field     => request()->email,
				'password' => request()->password,
			]
		);

		if (!$authenticated)
		{
			$email = Email::where('email', '=', $request->email)->first();
			if ($email)
			{
				$loginEmail = $email->user->email;
				$authenticated = auth()->attempt([
					'email'    => $loginEmail,
					'password' => $request->password,
				]);
				$user = auth()->user();
				if ($email->email_verified_at === null)
				{
					return response()->json(['error' => 'Non primary email is not verified'], 403);
				}
			}
		}

		if (!$authenticated)
		{
			return response()->json('wrong email or password', 401);
		}

		$payload = [
			'exp' => Carbon::now()->addDay()->timestamp,
			'uid' => auth()->user()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$remember = 1440;

		if ($request->remember == 'yes')
		{
			$remember = 43000;
		}

		$cookie = cookie('access_token', $jwt, $remember, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function me(): JsonResponse
	{
		return response()->json(
			[
				'message' => 'authenticated successfully',
				'user'    => jwtUser()->load('emails'),
			],
			200
		);
	}

	public function forgot(ForgotPasswordRequest $request): JsonResponse
	{
		$token = Str::random(60);
		DB::table('password_resets')->insert([
			'email'     => $request->email,
			'token'     => $token,
			'created_at'=> Carbon::now(),
		]);

		$user = User::where('email', $request->email)->first();
		Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

		return response()->json('email sent');
	}

	public function reset(ResetPasswordRequest $request): JsonResponse
	{
		$updated = DB::table('password_resets')->where([
			'email'=> $request->email,
			'token'=> $request->token,
		])->first();

		if (!$updated)
		{
			return response()->json('token not found', 404);
		}

		User::where('email', $request->email)->update([
			'password'=> bcrypt($request->password),
		]);

		DB::table('password_resets')->where('email', $request->email)->delete();

		return response()->json('password changed');
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie('access_token', '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}
}

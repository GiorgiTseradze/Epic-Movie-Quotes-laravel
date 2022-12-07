<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
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
		User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => Hash::make($request->password),
		]);

		return response()->json('User successfully registered!', 200);
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
			return response()->json('wrong email or password', 401);
		}

		$payload = [
			'exp' => Carbon::now()->addDay()->timestamp,
			'uid' => User::where($field, '=', request()->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');

		$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function me(): JsonResponse
	{
		return response()->json(
			[
				'message' => 'authenticated successfully',
				// 'user'    => jwtUser(),
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

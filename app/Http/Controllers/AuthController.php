<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

	public function forgot(ForgotPasswordRequest $request): RedirectResponse
	{
		$status = Password::sendResetLink(
			$request->only('email')
		);
		return $status === Password::RESET_LINK_SENT
		? redirect()->route('verification.notice')
		: back()->withErrors(['email' => __($status)]);
	}

	public function reset(ResetPasswordRequest $request): RedirectResponse
	{
		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		return $status === Password::PASSWORD_RESET
					? redirect()->route('login')->with('status', __($status))
					: back()->withErrors(['email' => [__($status)]]);
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie('access_token', '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}
}

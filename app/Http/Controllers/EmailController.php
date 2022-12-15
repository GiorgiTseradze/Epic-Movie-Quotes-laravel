<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEmailRequest;
use App\Mail\VerifyEmail;
use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailController extends Controller
{
	public function store(AddEmailRequest $request): JsonResponse
	{
		$token = Str::random(60);

		Email::create([
			'email'   => $request->email,
			'user_id' => jwtUser()->id,
			'token'   => $token,
		]);
		$user = jwtUser();
		$verifyUser = Email::where('email', $request->email)->first();
		Mail::to($verifyUser->email)->send(new VerifyEmail($user, $verifyUser));

		return response()->json('New email registered', 201);
	}

	public function verify(Request $request): JsonResponse
	{
		$email = Email::where('email', $request->email)->where('token', $request->token)->first();

		if (!$email)
		{
			return response()->json('token not found', 404);
		}

		$email->email_verified_at = Carbon::now();
		$email->save();

		return response()->json('New email verified', 200);
	}

	public function destroy(Email $email): JsonResponse
	{
		$email->delete();
		return response()->json('Email has been deleted successfully', 200);
	}
}

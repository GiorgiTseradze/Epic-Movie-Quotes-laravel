<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
	public function update(ProfileUpdateRequest $request): JsonResponse
	{
		$user = JwtUser();
		if ($request->name)
		{
			$user->name = $request->name;
		}
		if ($request->email)
		{
			Email::where('email', $user->email)->delete();

			Email::create([
				'email'             => $user->email,
				'user_id'           => jwtUser()->id,
				'email_verified_at' => Carbon::now(),
				'token'             => $user->token,
			]);
			$user->email = $request->email;
			Email::where('email', $request->email)->delete();
		}
		if ($request->password)
		{
			$user->password = Hash::make($request->password);
		}
		if ($request->file('thumbnail'))
		{
			$fileName = time() . '_' . request()->file('thumbnail')->getClientOriginalName();
			$filePath = request()->file('thumbnail')->storeAs('images', str_replace(' ', '_', $fileName), 'public');
			$user->thumbnail = config('app.url') . 'storage/' . $filePath;
		}
		if ($user->save())
		{
			return response()->json('Profile updated!', 200);
		}
		else
		{
			return response()->json(['error' => 'Problem updating user!'], 400);
		}
	}
}

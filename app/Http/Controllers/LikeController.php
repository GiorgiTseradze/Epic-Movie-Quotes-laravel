<?php

namespace App\Http\Controllers;

use App\Events\AddLikeEvent;
use App\Events\AddNotificationEvent;
use App\Http\Requests\AddLikeRequest;
use App\Models\Like;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
	public function store(AddLikeRequest $request): JsonResponse
	{
		event(new AddLikeEvent($request->all()));
		$like = Like::where('user_id', jwtUser()->id)->where('quote_id', $request->quote_id)->first();
		if ($like)
		{
			$like->delete();
			return response()->json('Like deleted');
		}
		Like::create([
			'user_id'  => jwtuser()->id,
			'quote_id' => $request->quote_id,
		]);

		if (jwtUser()->id != $request['to_id'])
		{
			$notification = Notification::create([
				'from_id'=> jwtUser()->id,
				'to_id'  => $request['to_id'],
				'type'   => 'like',
			]);

			event(new AddNotificationEvent($notification->load('sender')));
		}

		return response()->json('like added');
	}
}

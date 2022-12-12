<?php

namespace App\Http\Controllers;

use App\Events\AddCommentEvent;
use App\Events\AddNotificationEvent;
use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function show(): JsonResponse
	{
		return response()->json(Comment::all());
	}

	public function store(AddCommentRequest $request): JsonResponse
	{
		event(new AddCommentEvent($request->all()));

		Comment::create([
			'comment'        => $request['comment'],
			'quote_id'       => $request['quote_id'],
			'user_id'        => jwtUser()->id,
		]);

		if (jwtUser()->id != $request['to_id'])
		{
			$notification = Notification::create([
				'from_id'=> jwtUser()->id,
				'to_id'  => $request['to_id'],
				'type'   => 'comment',
			]);

			event(new AddNotificationEvent($notification->load('sender')));
		}

		return response()->json('Comment has been added successfully', 200);
	}
}

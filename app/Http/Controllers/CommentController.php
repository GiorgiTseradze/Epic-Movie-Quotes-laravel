<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function show(): JsonResponse
	{
		return response()->json(Comment::all());
	}

	public function store(AddCommentRequest $request): JsonResponse
	{
		Comment::create([
			'comment'        => $request['comment'],
			'quote_id'       => $request['quote_id'],
			'user_id'        => jwtUser()->id,
		]);

		return response()->json('Comment has been added successfully', 200);
	}
}

<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function show(): JsonResponse
	{
		return response()->json(Notification::where('to_id', jwtUser()->id)->with('sender')->orderBy('created_at', 'desc')->get());
	}

	public function read(): JsonResponse
	{
		Notification::query()->where('to_id', jwtUser()->id)->update(['read' => 1]);
		return response()->json('all read', 200);
	}
}

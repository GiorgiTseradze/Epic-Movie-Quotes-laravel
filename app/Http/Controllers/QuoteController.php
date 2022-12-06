<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	public function show(): JsonResponse
	{
		return response()->json(Quote::all()->load('comments'));
	}

	public function store(AddQuoteRequest $request): JsonResponse
	{
		if ($request->file('image'))
		{
			$request->file('image')->store('photos');
		}

		$filePath = '';

		if ($request->file('image'))
		{
			$fileName = time() . '_' . request()->file('image')->getClientOriginalName();
			$filePath = request()->file('image')->storeAs('images', str_replace(' ', '_', $fileName), 'public');
		}

		Quote::create([
			'quote'        => ['en' => $request['quote_en'], 'ka' => $request['quote_ka']],
			'image'        => '/storage/' . $filePath,
			'movie_id'     => $request['movie_id'],
			'user_id'      => jwtUser()->id,
		]);

		return response()->json('Quote has been added successfully', 200);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		if ($request->file('image'))
		{
			$request->file('image')->store('photos');
		}

		$filePath = '';
		if ($request->file('image'))
		{
			$fileName = time() . '_' . request()->file('image')->getClientOriginalName();
			$filePath = request()->file('image')->storeAs('images', str_replace(' ', '_', $fileName), 'public');
		}

		$quote->update([
			'quote'        => ['en' => $request['quote_en'], 'ka' => $request['quote_ka']],
			'image'        => '/storage/' . $filePath,
			'user_id'      => jwtUser()->id,
			'quote_id'     => $request['quote_id'],
		]);

		return response()->json('Quote has been updated successfully', 200);
	}

	public function get(Quote $quote)
	{
		return response()->json($quote->load('movie', 'comments'));
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json('Quote has been deleted successfully', 200);
	}
}

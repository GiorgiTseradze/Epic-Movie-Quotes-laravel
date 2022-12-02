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
		return response()->json(Quote::all());
	}

	public function store(AddQuoteRequest $request): JsonResponse
	{
		if ($request->file('image'))
		{
			$request->file('image')->store('photos');
		}

		$file_path = '';

		if ($request->file('image'))
		{
			$file_name = time() . '_' . request()->file('image')->getClientOriginalName();
			$file_path = request()->file('image')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
		}

		Quote::create([
			'quote'        => ['en' => $request['quote_en'], 'ka' => $request['quote_ka']],
			'image'        => '/storage/' . $file_path,
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

		$file_path = '';
		if ($request->file('image'))
		{
			$file_name = time() . '_' . request()->file('image')->getClientOriginalName();
			$file_path = request()->file('image')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
		}

		$quote->update([
			'quote'        => ['en' => $request['quote_en'], 'ka' => $request['quote_ka']],
			'image'        => '/storage/' . $file_path,
			'user_id'      => jwtUser()->id,
			'quote_id'     => $request['quote_id'],
		]);

		return response()->json('Quote has been updated successfully', 200);
	}

	public function get(Quote $quote)
	{
		return response()->json($quote->load('movies'));
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json('Quote has been deleted successfully', 200);
	}
}

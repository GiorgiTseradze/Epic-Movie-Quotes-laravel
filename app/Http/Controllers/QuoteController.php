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
			'user_id'      => auth()->id(),
		]);

		return response()->json('Quote has been added successfully', 200);
	}

	public function update(UpdateQuoteRequest $request): JsonResponse
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

		$quote = Quote::where('id', $request->quote_id)->first();

		$quote->update([
			'quote'        => ['en' => $request['quote_en'], 'ka' => $request['quote_ka']],
			'image'        => '/storage/' . $file_path,
			'movie_id'     => $request['movie_id'],
			'user_id'      => auth()->id(),
			'quote_id'     => $request['quote_id'],
		]);

		return response()->json('Quote has been updated successfully', 200);
	}
}

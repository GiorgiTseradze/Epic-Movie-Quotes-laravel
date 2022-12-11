<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
	public function show(): JsonResponse
	{
		return response()->json(Quote::with('comments.user', 'likes', 'user')->orderBy('created_at', 'desc')->get());
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

		if ($filePath)
		{
			$quote->update([
				'quote'        => ['en' => $request['quote_en'], 'ka' => $request['quote_ka']],
				'image'        => '/storage/' . $filePath,
				'user_id'      => jwtUser()->id,
				'quote_id'     => $request['quote_id'],
			]);
		}
		else
		{
			$quote->update([
				'quote'        => ['en' => $request['quote_en'], 'ka' => $request['quote_ka']],
				'user_id'      => jwtUser()->id,
				'quote_id'     => $request['quote_id'],
			]);
		}

		return response()->json('Quote has been updated successfully', 200);
	}

	public function get(Quote $quote)
	{
		return response()->json($quote->load('movie', 'comments', 'likes', 'user'));
	}

	public function destroy(Quote $quote): JsonResponse
	{
		$quote->delete();
		return response()->json('Quote has been deleted successfully', 200);
	}

	public function search(Request $request): JsonResponse
	{
		$quotes = [];
		$search = $request->search;
		if ($search[0] == '@')
		{
			$search = ltrim($search, '@');
			$quotes = Quote::whereHas('movie', function ($query) use ($search) {
				$query
					->where('name->en', 'like', $search . '%')
					->orWhere('name->ka', 'like', $search . '%');
			})->get();
		}
		elseif ($search[0] == '#')
		{
			$search = ltrim($search, '#');
			$quotes = Quote::query()
				->where('quote->en', 'like', '%' . $search . '%')
				->orWhere('quote->ka', 'like', '%' . $search . '%')
				->get();
		}
		else
		{
			$quotes = Quote::whereHas('movie', function ($query) use ($search) {
				$query
					->where('name->en', 'like', $search . '%')->orWhere('name->ka', 'like', $search . '%');
			})->orwhere('quote->en', 'like', '%' . $search . '%')
			->orwhere('quote->ka', 'like', '%' . $search . '%')->get();
		}
		return response()->json($quotes->load('comments.user', 'user', 'likes', ));
	}
}

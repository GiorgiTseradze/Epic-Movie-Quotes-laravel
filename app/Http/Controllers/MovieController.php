<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function show(): JsonResponse
	{
		return response()->json(Movie::all());
	}

	public function store(AddMovieRequest $request): JsonResponse
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

		Movie::create([
			'name'        => ['en' => $request['name_en'], 'ka' => $request['name_ka']],
			'genre'       => $request['genre'],
			'director'    => ['en' => $request['director_en'], 'ka' => $request['director_ka']],
			'description' => ['en' => $request['description_en'], 'ka' => $request['description_ka']],
			'image'       => '/storage/' . $filePath,
			'user_id'     => jwtUser()->id,
		]);

		return response()->json('Movie has been added successfully', 200);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
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

		$movie->update([
			'name'        => ['en' => $request['name_en'], 'ka' => $request['name_ka']],
			'genre'       => $request['genre'],
			'director'    => ['en' => $request['director_en'], 'ka' => $request['director_ka']],
			'description' => ['en' => $request['description_en'], 'ka' => $request['description_ka']],
			'image'       => '/storage/' . $filePath,
			'user_id'     => jwtUser()->id,
		]);
		return response()->json('Movie has been updated successfully', 200);
	}

	public function get(Movie $movie)
	{
		return response()->json($movie->load('quotes'));
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json('Movie has been deleted successfully', 200);
	}
}

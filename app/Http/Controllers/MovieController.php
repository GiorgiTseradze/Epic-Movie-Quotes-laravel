<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function store(AddMovieRequest $request): JsonResponse
	{
		$file_path = '';
		if ($request->file('image'))
		{
			$file_name = time() . '_' . request()->file('image')->getClientOriginalName();
			$file_path = request()->file('image')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
		}

		Movie::create([
			'name'        => ['en' => $request['name_en'], 'ka' => $request['name_ka']],
			'genre'       => $request->genre,
			'director'    => ['en' => $request['director_en'], 'ka' => $request['director_ka']],
			'description' => ['en' => $request['description_en'], 'ka' => $request['description_ka']],
			'image'       => '/storage/' . $file_path,
			'user_id'     => auth()->id(),
		]);

		return response()->json('Movie has been added successfully', 200);
	}
}

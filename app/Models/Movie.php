<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
	protected $guarded = ['id'];

	use HasFactory, HasTranslations;

	public $translatable = ['name', 'director', 'description'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

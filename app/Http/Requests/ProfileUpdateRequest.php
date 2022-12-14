<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'thumbnail' => '',
			'name'      => 'min:3|unique:users,name',
			'email'     => '',
			'password'  => 'min:8|confirmed',
		];
	}
}

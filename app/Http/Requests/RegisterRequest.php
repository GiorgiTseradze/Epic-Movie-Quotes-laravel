<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name'            => 'required|min:3|unique:users,name',
			'email'           => 'required|min:3|unique:users,email',
			'password'        => 'required|min:8|confirmed',
		];
	}
}

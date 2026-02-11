<?php

namespace App\Http\Requests\Jury;

use Illuminate\Foundation\Http\FormRequest;

class StoreJuryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'description' => 'nullable|string|max:255',
			'year' => 'required|integer|min:1900|max:2100',
			'link' => 'nullable|url|max:255',
			'publish' => 'boolean',
		];
	}
}

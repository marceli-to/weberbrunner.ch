<?php

namespace App\Http\Requests\ProjectAttribute;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectAttributeRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'label' => 'required|string|max:255',
			'value' => 'required|string|max:255',
		];
	}
}

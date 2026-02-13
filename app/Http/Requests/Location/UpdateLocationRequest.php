<?php

namespace App\Http\Requests\Location;

use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('location'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
		];
	}

	public function messages(): array
	{
		return [
			'title.required' => 'Bitte überprüfe den Titel',
			'title.max' => 'Bitte überprüfe den Titel',
		];
	}
}

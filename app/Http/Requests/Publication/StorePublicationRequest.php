<?php

namespace App\Http\Requests\Publication;

use App\Models\Publication;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicationRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Publication::class);
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'subtitle' => 'nullable|string|max:255',
			'location_id' => 'nullable|exists:locations,id',
			'publish' => 'boolean',
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

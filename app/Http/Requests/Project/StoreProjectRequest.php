<?php

namespace App\Http\Requests\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Project::class);
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'number' => 'required|integer',
			'priority' => 'nullable|in:A,B,C',
			'city' => 'nullable|string|max:255',
			'location_id' => 'nullable|exists:locations,id',
		];
	}

	public function messages(): array
	{
		return [
			'title.required' => 'Bitte überprüfe den Titel',
			'title.max' => 'Bitte überprüfe den Titel',
			'location_id.exists' => 'Bitte überprüfe den Standort',
		];
	}
}

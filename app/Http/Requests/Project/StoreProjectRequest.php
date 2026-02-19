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
			'short_description' => 'nullable|string',
			'description' => 'nullable|string',
			'meta_description' => 'nullable|string',
			'city' => 'nullable|string|max:255',
			'location_id' => 'nullable|exists:locations,id',
			'publish' => 'boolean',
			'categories' => 'nullable|array',
			'categories.*' => 'exists:categories,id',
			'statuses' => 'nullable|array',
			'statuses.*' => 'exists:statuses,id',
			'media' => 'nullable|array',
			'media.*.uuid' => 'required|string',
			'media.*.file' => 'required|string',
			'media.*.original_name' => 'required|string',
			'media.*.mime_type' => 'required|string',
			'media.*.size' => 'required|integer',
			'media.*.width' => 'nullable|integer',
			'media.*.height' => 'nullable|integer',
			'media.*.alt' => 'nullable|string|max:255',
			'media.*.caption' => 'nullable|string|max:255',
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

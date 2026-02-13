<?php

namespace App\Http\Requests\NetworkEntry;

use App\Models\NetworkEntry;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNetworkEntryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('networkEntry'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'description' => 'nullable|string|max:255',
			'category' => 'nullable|string|max:255',
			'link' => 'nullable|url|max:255',
			'publish' => 'boolean',
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
			'link.url' => 'Bitte überprüfe den Link',
		];
	}
}

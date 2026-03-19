<?php

namespace App\Http\Requests\PublicationBlock;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicationBlockRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('publication'));
	}

	public function rules(): array
	{
		return [
			'type' => 'required|string|in:slider,download,link',
			'title' => 'nullable|string|max:255',
			'content' => 'nullable|string',
			'url' => 'required_if:type,link|nullable|string|max:255',
		];
	}

	public function messages(): array
	{
		return [
			'type.required' => 'Bitte einen Blocktyp angeben',
			'type.in' => 'Ungültiger Blocktyp',
		];
	}
}

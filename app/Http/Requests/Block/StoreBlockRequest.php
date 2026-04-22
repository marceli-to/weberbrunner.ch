<?php

namespace App\Http\Requests\Block;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlockRequest extends FormRequest
{
	use ResolvesBlockable;

	public function authorize(): bool
	{
		return $this->user()->can('update', $this->blockable());
	}

	public function rules(): array
	{
		return [
			'type' => ['required', 'string', Rule::in($this->blockable()->allowedBlockTypes())],
			'title' => $this->input('type') === 'fixed-slider'
				? 'nullable|string|max:255'
				: 'nullable|string|max:255',
			'content' => 'nullable|string',
			'url' => 'nullable|string|max:2048',
		];
	}

	public function messages(): array
	{
		return [
			'type.required' => 'Bitte einen Blocktyp angeben',
			'type.in' => 'Ungültiger Blocktyp',
			'title.max' => 'Bitte überprüfe den Titel',
		];
	}
}

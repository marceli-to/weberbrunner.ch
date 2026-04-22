<?php

namespace App\Http\Requests\Block;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlockRequest extends FormRequest
{
	use ResolvesBlockable;

	public function authorize(): bool
	{
		return $this->user()->can('update', $this->blockable());
	}

	public function rules(): array
	{
		return [
			'title' => 'nullable|string|max:255',
			'content' => 'nullable|string',
			'url' => 'nullable|string|max:2048',
		];
	}

	public function messages(): array
	{
		return [
			'title.max' => 'Bitte überprüfe den Titel',
		];
	}
}

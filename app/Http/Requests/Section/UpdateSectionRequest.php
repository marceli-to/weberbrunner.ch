<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('section'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'type' => 'sometimes|string|in:award,jury,talk',
		];
	}

	public function messages(): array
	{
		return [
			'title.required' => 'Bitte überprüfe den Titel',
			'title.max' => 'Bitte überprüfe den Titel',
			'type.required' => 'Bitte überprüfe den Typ',
			'type.in' => 'Bitte überprüfe den Typ',
		];
	}
}

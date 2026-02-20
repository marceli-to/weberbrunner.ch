<?php

namespace App\Http\Requests\ProjectBlock;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectBlockRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'title' => 'nullable|string|max:255',
			'content' => 'nullable|string',
		];
	}

	public function messages(): array
	{
		return [
			'title.max' => 'Bitte überprüfe den Titel',
		];
	}
}

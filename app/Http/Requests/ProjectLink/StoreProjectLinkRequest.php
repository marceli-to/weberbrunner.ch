<?php

namespace App\Http\Requests\ProjectLink;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectLinkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'url' => 'required|string|max:2048',
		];
	}

	public function messages(): array
	{
		return [
			'url.required' => 'Bitte überprüfe die URL',
			'url.max' => 'Bitte überprüfe die URL',
		];
	}
}

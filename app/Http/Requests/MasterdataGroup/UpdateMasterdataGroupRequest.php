<?php

namespace App\Http\Requests\MasterdataGroup;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMasterdataGroupRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('masterdataGroup'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
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

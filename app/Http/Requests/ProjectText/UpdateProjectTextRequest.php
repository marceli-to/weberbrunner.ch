<?php

namespace App\Http\Requests\ProjectText;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectTextRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'description' => 'nullable|string',
			'short_description' => 'nullable|string',
		];
	}
}

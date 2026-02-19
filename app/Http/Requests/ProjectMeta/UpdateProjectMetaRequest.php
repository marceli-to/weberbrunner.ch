<?php

namespace App\Http\Requests\ProjectMeta;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectMetaRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'meta_description' => 'nullable|string',
		];
	}
}

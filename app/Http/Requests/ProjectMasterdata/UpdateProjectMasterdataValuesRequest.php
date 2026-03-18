<?php

namespace App\Http\Requests\ProjectMasterdata;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectMasterdataValuesRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'entries' => ['nullable', 'array'],
			'entries.*.uuid' => ['required', 'string', 'exists:masterdata,uuid'],
			'entries.*.value' => ['nullable', 'string', 'max:255'],
		];
	}
}

<?php

namespace App\Http\Requests\ProjectStatus;

use Illuminate\Foundation\Http\FormRequest;

class SyncProjectStatusRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'statuses' => 'nullable|array',
			'statuses.*' => 'exists:statuses,id',
		];
	}
}

<?php

namespace App\Http\Requests\ProjectCategory;

use Illuminate\Foundation\Http\FormRequest;

class SyncProjectCategoryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'categories' => 'nullable|array',
			'categories.*' => 'exists:categories,id',
		];
	}
}

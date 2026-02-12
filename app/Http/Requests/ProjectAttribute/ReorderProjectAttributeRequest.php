<?php

namespace App\Http\Requests\ProjectAttribute;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ReorderProjectAttributeRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:project_attributes,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

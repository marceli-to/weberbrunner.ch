<?php

namespace App\Http\Requests\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ReorderProjectRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Project::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:projects,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

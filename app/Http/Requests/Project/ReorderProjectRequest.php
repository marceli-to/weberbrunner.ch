<?php

namespace App\Http\Requests\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ReorderProjectRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Project::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:projects,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

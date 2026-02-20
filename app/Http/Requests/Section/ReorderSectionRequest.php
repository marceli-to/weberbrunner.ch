<?php

namespace App\Http\Requests\Section;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;

class ReorderSectionRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Section::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:sections,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

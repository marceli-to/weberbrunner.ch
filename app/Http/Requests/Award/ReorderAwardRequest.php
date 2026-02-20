<?php

namespace App\Http\Requests\Award;

use App\Models\Award;
use Illuminate\Foundation\Http\FormRequest;

class ReorderAwardRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Award::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:awards,uuid',
			'items.*.sort_order' => 'required|integer',
			'items.*.section_id' => 'sometimes|integer|exists:sections,id',
		];
	}
}

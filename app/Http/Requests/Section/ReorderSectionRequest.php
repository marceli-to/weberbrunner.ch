<?php

namespace App\Http\Requests\Section;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;

class ReorderSectionRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Section::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:sections,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

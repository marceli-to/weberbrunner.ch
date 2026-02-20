<?php

namespace App\Http\Requests\Category;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class ReorderCategoryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Category::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:categories,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

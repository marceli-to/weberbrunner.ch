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
			'items.*.id' => 'required|integer|exists:categories,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

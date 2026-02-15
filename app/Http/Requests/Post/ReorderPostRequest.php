<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class ReorderPostRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Post::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:posts,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

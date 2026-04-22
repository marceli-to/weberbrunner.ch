<?php

namespace App\Http\Requests\Block;

use Illuminate\Foundation\Http\FormRequest;

class ReorderBlockRequest extends FormRequest
{
	use ResolvesBlockable;

	public function authorize(): bool
	{
		return $this->user()->can('update', $this->blockable());
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:blocks,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

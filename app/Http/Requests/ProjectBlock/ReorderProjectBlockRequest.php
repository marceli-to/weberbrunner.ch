<?php

namespace App\Http\Requests\ProjectBlock;

use Illuminate\Foundation\Http\FormRequest;

class ReorderProjectBlockRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:project_blocks,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

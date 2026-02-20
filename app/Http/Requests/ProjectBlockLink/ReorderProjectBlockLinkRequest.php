<?php

namespace App\Http\Requests\ProjectBlockLink;

use Illuminate\Foundation\Http\FormRequest;

class ReorderProjectBlockLinkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:project_block_links,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

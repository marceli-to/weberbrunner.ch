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
			'items.*.uuid' => 'required|string|exists:project_block_links,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

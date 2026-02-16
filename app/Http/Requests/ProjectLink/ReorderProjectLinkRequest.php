<?php

namespace App\Http\Requests\ProjectLink;

use Illuminate\Foundation\Http\FormRequest;

class ReorderProjectLinkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:project_links,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

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
			'items.*.uuid' => 'required|string|exists:project_links,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

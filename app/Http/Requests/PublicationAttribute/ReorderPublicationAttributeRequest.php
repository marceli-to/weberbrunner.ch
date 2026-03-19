<?php

namespace App\Http\Requests\PublicationAttribute;

use Illuminate\Foundation\Http\FormRequest;

class ReorderPublicationAttributeRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('publication'));
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:publication_attributes,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

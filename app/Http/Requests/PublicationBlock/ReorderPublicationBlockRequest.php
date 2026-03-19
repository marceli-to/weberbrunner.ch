<?php

namespace App\Http\Requests\PublicationBlock;

use Illuminate\Foundation\Http\FormRequest;

class ReorderPublicationBlockRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('publication'));
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:publication_blocks,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

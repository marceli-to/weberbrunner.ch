<?php

namespace App\Http\Requests\PublicationBlockLink;

use Illuminate\Foundation\Http\FormRequest;

class ReorderPublicationBlockLinkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('publication'));
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:publication_block_links,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

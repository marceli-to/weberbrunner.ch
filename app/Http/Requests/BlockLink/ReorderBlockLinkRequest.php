<?php

namespace App\Http\Requests\BlockLink;

use App\Http\Requests\Block\ResolvesBlockable;
use Illuminate\Foundation\Http\FormRequest;

class ReorderBlockLinkRequest extends FormRequest
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
			'items.*.uuid' => 'required|string|exists:block_links,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

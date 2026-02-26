<?php

namespace App\Http\Requests\LandingItem;

use App\Models\LandingItem;
use Illuminate\Foundation\Http\FormRequest;

class ReorderLandingItemRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', LandingItem::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:landing_items,uuid',
			'items.*.column' => 'required|integer|in:1,2,3',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

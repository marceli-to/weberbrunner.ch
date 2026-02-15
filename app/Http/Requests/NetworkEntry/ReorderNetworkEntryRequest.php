<?php

namespace App\Http\Requests\NetworkEntry;

use App\Models\NetworkEntry;
use Illuminate\Foundation\Http\FormRequest;

class ReorderNetworkEntryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', NetworkEntry::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:network_entries,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

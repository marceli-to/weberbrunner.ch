<?php

namespace App\Http\Requests\MasterdataGroup;

use App\Models\MasterdataGroup;
use Illuminate\Foundation\Http\FormRequest;

class ReorderMasterdataGroupRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', MasterdataGroup::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:masterdata_groups,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

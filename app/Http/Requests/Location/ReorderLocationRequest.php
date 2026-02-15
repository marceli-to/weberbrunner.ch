<?php

namespace App\Http\Requests\Location;

use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;

class ReorderLocationRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Location::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:locations,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

<?php

namespace App\Http\Requests\Masterdata;

use App\Models\Masterdata;
use Illuminate\Foundation\Http\FormRequest;

class ReorderMasterdataRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Masterdata::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:masterdata,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

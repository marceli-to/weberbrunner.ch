<?php

namespace App\Http\Requests\Jury;

use App\Models\Jury;
use Illuminate\Foundation\Http\FormRequest;

class ReorderJuryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Jury::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:juries,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

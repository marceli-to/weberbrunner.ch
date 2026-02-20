<?php

namespace App\Http\Requests\Talk;

use App\Models\Talk;
use Illuminate\Foundation\Http\FormRequest;

class ReorderTalkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Talk::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:talks,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

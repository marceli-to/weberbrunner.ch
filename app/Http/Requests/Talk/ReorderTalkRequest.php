<?php

namespace App\Http\Requests\Talk;

use App\Models\Talk;
use Illuminate\Foundation\Http\FormRequest;

class ReorderTalkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Talk::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:talks,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

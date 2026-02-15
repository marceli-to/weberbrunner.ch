<?php

namespace App\Http\Requests\Status;

use App\Models\Status;
use Illuminate\Foundation\Http\FormRequest;

class ReorderStatusRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Status::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:statuses,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

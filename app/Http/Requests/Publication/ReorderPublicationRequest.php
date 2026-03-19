<?php

namespace App\Http\Requests\Publication;

use App\Models\Publication;
use Illuminate\Foundation\Http\FormRequest;

class ReorderPublicationRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Publication::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:publications,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

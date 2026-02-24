<?php

namespace App\Http\Requests\Contact;

use App\Models\Contact;
use Illuminate\Foundation\Http\FormRequest;

class ReorderContactRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', Contact::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.uuid' => 'required|string|exists:contacts,uuid',
			'items.*.sort_order' => 'required|integer',
			'items.*.location_id' => 'sometimes|integer|exists:locations,id',
		];
	}
}

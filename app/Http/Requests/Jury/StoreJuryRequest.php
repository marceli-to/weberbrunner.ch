<?php

namespace App\Http\Requests\Jury;

use App\Models\Jury;
use Illuminate\Foundation\Http\FormRequest;

class StoreJuryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Jury::class);
	}

	public function rules(): array
	{
		return [
			'text' => 'nullable|string',
			'section_id' => 'required|exists:sections,id',
			'publish' => 'boolean',
		];
	}

	public function messages(): array
	{
		return [
			'section_id.required' => 'Bitte überprüfe die Sektion',
			'section_id.exists' => 'Bitte überprüfe die Sektion',
		];
	}
}

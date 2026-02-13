<?php

namespace App\Http\Requests\Talk;

use App\Models\Talk;
use Illuminate\Foundation\Http\FormRequest;

class StoreTalkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Talk::class);
	}

	public function rules(): array
	{
		return [
			'text' => 'nullable|string',
			'section_id' => 'nullable|exists:sections,id',
			'publish' => 'boolean',
		];
	}
}

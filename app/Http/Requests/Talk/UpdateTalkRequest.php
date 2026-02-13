<?php

namespace App\Http\Requests\Talk;

use App\Models\Talk;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTalkRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('talk'));
	}

	public function rules(): array
	{
		return [
			'text' => 'nullable|string',
			'section_id' => 'nullable|exists:sections,id',
			'publish' => 'boolean',
		];
	}

	public function messages(): array
	{
		return [
			'section_id.exists' => 'Bitte überprüfe die Sektion',
		];
	}
}

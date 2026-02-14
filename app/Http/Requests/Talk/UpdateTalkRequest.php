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

	protected function prepareForValidation(): void
	{
		if (trim(strip_tags($this->text ?? '')) === '') {
			$this->merge(['text' => null]);
		}
	}

	public function rules(): array
	{
		return [
			'text' => 'required|string',
			'section_id' => 'required|exists:sections,id',
			'publish' => 'boolean',
		];
	}

	public function messages(): array
	{
		return [
			'text.required' => 'Bitte Text eingeben',
			'section_id.required' => 'Bitte überprüfe die Sektion',
			'section_id.exists' => 'Bitte überprüfe die Sektion',
		];
	}
}

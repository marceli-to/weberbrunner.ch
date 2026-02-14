<?php

namespace App\Http\Requests\Section;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Section::class);
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'type' => 'required|string|in:award,jury,talk,network',
		];
	}

	public function messages(): array
	{
		return [
			'title.required' => 'Bitte überprüfe den Titel',
			'title.max' => 'Bitte überprüfe den Titel',
			'type.required' => 'Bitte überprüfe den Typ',
			'type.in' => 'Bitte überprüfe den Typ',
		];
	}
}

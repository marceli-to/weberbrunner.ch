<?php

namespace App\Http\Requests\ProjectAttribute;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectAttributeRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'label' => 'required|string|max:255',
			'value' => 'required|string|max:255',
		];
	}

	public function messages(): array
	{
		return [
			'label.required' => 'Bitte überprüfe das Label',
			'label.max' => 'Bitte überprüfe das Label',
			'value.required' => 'Bitte überprüfe den Wert',
			'value.max' => 'Bitte überprüfe den Wert',
		];
	}
}

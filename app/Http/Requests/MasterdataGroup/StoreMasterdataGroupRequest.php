<?php

namespace App\Http\Requests\MasterdataGroup;

use App\Models\MasterdataGroup;
use Illuminate\Foundation\Http\FormRequest;

class StoreMasterdataGroupRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', MasterdataGroup::class);
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
		];
	}

	public function messages(): array
	{
		return [
			'title.required' => 'Bitte überprüfe den Titel',
			'title.max' => 'Bitte überprüfe den Titel',
		];
	}
}

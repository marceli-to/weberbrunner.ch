<?php

namespace App\Http\Requests\PublicationAttribute;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublicationAttributeRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('publication'));
	}

	public function rules(): array
	{
		return [
			'key' => 'required|string|max:255',
			'value' => 'required|string',
		];
	}
}

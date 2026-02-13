<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('section'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'type' => 'required|string|in:award,jury,talk',
		];
	}
}

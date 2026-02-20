<?php

namespace App\Http\Requests\ProjectBlock;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectBlockRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('project'));
	}

	public function rules(): array
	{
		return [
			'type' => 'required|string|in:text,slider,image,links,fixed-slider',
			'title' => $this->input('type') === 'fixed-slider'
				? 'nullable|string|max:255'
				: 'required|string|max:255',
			'content' => 'nullable|string',
		];
	}

	public function messages(): array
	{
		return [
			'type.required' => 'Bitte einen Blocktyp angeben',
			'type.in' => 'Ungültiger Blocktyp',
			'title.required' => 'Bitte einen Titel angeben',
			'title.max' => 'Bitte überprüfe den Titel',
		];
	}
}

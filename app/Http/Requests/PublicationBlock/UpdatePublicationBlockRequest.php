<?php

namespace App\Http\Requests\PublicationBlock;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublicationBlockRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('publication'));
	}

	public function rules(): array
	{
		return [
			'title' => 'nullable|string|max:255',
			'content' => 'nullable|string',
			'url' => 'nullable|string|max:255',
		];
	}
}

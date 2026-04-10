<?php

namespace App\Http\Requests\PageText;

use App\Models\PageText;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePageTextRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', PageText::class);
	}

	public function rules(): array
	{
		return [
			'title' => 'nullable|string',
			'text' => 'nullable|string',
		];
	}
}

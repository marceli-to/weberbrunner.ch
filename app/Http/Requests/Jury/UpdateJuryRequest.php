<?php

namespace App\Http\Requests\Jury;

use App\Models\Jury;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJuryRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('jury'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'description' => 'nullable|string|max:255',
			'year' => 'required|integer|min:1900|max:2100',
			'link' => 'nullable|url|max:255',
			'publish' => 'boolean',
		];
	}
}

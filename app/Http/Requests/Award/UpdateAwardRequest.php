<?php

namespace App\Http\Requests\Award;

use App\Models\Award;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAwardRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('award'));
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'description' => 'nullable|string|max:255',
			'section_id' => 'required|exists:sections,id',
			'project_id' => 'nullable|exists:projects,id',
			'link' => 'nullable|url|max:255',
			'publish' => 'boolean',
		];
	}
}

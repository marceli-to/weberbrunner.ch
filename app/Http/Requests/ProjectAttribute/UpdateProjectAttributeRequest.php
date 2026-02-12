<?php

namespace App\Http\Requests\ProjectAttribute;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectAttributeRequest extends FormRequest
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
}

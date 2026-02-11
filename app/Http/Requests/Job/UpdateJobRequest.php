<?php

namespace App\Http\Requests\Job;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'location_id' => 'nullable|exists:locations,id',
			'contact_email' => 'nullable|email|max:255',
			'publish' => 'boolean',
		];
	}
}

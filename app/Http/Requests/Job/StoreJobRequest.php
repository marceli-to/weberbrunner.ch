<?php

namespace App\Http\Requests\Job;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Job::class);
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

	public function messages(): array
	{
		return [
			'title.required' => 'Bitte überprüfe den Titel',
			'title.max' => 'Bitte überprüfe den Titel',
			'description.required' => 'Bitte überprüfe die Beschreibung',
			'contact_email.email' => 'Bitte überprüfe die Kontakt-E-Mail',
			'contact_email.max' => 'Bitte überprüfe die Kontakt-E-Mail',
		];
	}
}

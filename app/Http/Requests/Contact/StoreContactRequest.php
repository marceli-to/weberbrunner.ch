<?php

namespace App\Http\Requests\Contact;

use App\Models\Contact;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', Contact::class);
	}

	public function rules(): array
	{
		return [
			'location_id' => 'required|exists:locations,id',
			'company_name' => 'required|string|max:255',
			'address' => 'required|string',
			'phone' => 'nullable|string|max:255',
			'email' => 'nullable|email|max:255',
			'maps_url' => 'nullable|url|max:2048',
			'publish' => 'boolean',
		];
	}

	public function messages(): array
	{
		return [
			'company_name.required' => 'Bitte überprüfe den Firmennamen',
			'company_name.max' => 'Bitte überprüfe den Firmennamen',
			'address.required' => 'Bitte überprüfe die Adresse',
			'email.email' => 'Bitte überprüfe die E-Mail',
			'email.max' => 'Bitte überprüfe die E-Mail',
			'maps_url.url' => 'Bitte überprüfe die Google Maps URL',
		];
	}
}

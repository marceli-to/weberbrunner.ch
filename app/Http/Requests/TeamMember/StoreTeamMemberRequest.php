<?php

namespace App\Http\Requests\TeamMember;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeamMemberRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('create', TeamMember::class);
	}

	public function rules(): array
	{
		return [
			'firstname' => 'required|string|max:255',
			'name' => 'required|string|max:255',
			'email' => 'required|email|max:255',
			'title' => 'nullable|string|max:255',
			'since' => 'nullable|integer|min:1900|max:2100',
			'location_id' => 'nullable|exists:locations,id',
			'publish' => 'boolean',
		];
	}

	public function messages(): array
	{
		return [
			'firstname.required' => 'Bitte überprüfe den Vornamen',
			'firstname.max' => 'Bitte überprüfe den Vornamen',
			'name.required' => 'Bitte überprüfe den Namen',
			'name.max' => 'Bitte überprüfe den Namen',
			'email.required' => 'Bitte überprüfe die E-Mail-Adresse',
			'email.email' => 'Bitte überprüfe die E-Mail-Adresse',
			'email.max' => 'Bitte überprüfe die E-Mail-Adresse',
			'since.integer' => 'Bitte überprüfe das Jahr',
			'since.min' => 'Bitte überprüfe das Jahr',
			'since.max' => 'Bitte überprüfe das Jahr',
		];
	}
}

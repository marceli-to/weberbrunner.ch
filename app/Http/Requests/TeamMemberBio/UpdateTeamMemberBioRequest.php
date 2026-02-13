<?php

namespace App\Http\Requests\TeamMemberBio;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamMemberBioRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('teamMember'));
	}

	public function rules(): array
	{
		return [
			'period' => 'required|string|max:255',
			'description' => 'required|string',
		];
	}

	public function messages(): array
	{
		return [
			'period.required' => 'Bitte überprüfe die Zeitperiode',
			'period.max' => 'Bitte überprüfe die Zeitperiode',
			'description.required' => 'Bitte überprüfe die Beschreibung',
		];
	}
}

<?php

namespace App\Http\Requests\TeamMemberBio;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamMemberBioRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'period' => 'required|string|max:255',
			'description' => 'required|string',
		];
	}
}

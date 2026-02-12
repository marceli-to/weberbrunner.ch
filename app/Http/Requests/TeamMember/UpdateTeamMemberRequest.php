<?php

namespace App\Http\Requests\TeamMember;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamMemberRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('teamMember'));
	}

	public function rules(): array
	{
		return [
			'firstname' => 'required|string|max:255',
			'name' => 'required|string|max:255',
			'email' => 'nullable|email|max:255',
			'title' => 'nullable|string|max:255',
			'since' => 'nullable|integer|min:1900|max:2100',
			'location_id' => 'nullable|exists:locations,id',
			'publish' => 'boolean',
			'media' => 'nullable|array',
			'media.*.uuid' => 'required|string',
			'media.*.file' => 'required|string',
			'media.*.original_name' => 'required|string',
			'media.*.mime_type' => 'required|string',
			'media.*.size' => 'required|integer',
			'media.*.width' => 'nullable|integer',
			'media.*.height' => 'nullable|integer',
			'media.*.alt' => 'nullable|string|max:255',
			'media.*.caption' => 'nullable|string|max:255',
		];
	}
}

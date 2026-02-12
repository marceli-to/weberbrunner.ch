<?php

namespace App\Http\Requests\TeamMemberBio;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class ReorderTeamMemberBioRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('update', $this->route('teamMember'));
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:team_member_bios,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

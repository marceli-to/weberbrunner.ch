<?php

namespace App\Http\Requests\TeamMember;

use App\Models\TeamMember;
use Illuminate\Foundation\Http\FormRequest;

class ReorderTeamMemberRequest extends FormRequest
{
	public function authorize(): bool
	{
		return $this->user()->can('reorder', TeamMember::class);
	}

	public function rules(): array
	{
		return [
			'items' => 'required|array',
			'items.*.id' => 'required|integer|exists:team_members,id',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

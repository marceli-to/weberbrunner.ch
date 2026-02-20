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
			'items.*.uuid' => 'required|string|exists:team_members,uuid',
			'items.*.sort_order' => 'required|integer',
		];
	}
}

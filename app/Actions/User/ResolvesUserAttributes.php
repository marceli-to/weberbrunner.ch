<?php

namespace App\Actions\User;

use App\Models\TeamMember;

trait ResolvesUserAttributes
{
	protected function resolveAttributes(array $data): array
	{
		if ($data['type'] === 'intern') {
			$member = TeamMember::findOrFail($data['team_member_id']);

			return [
				'firstname' => $member->firstname,
				'name' => $member->name,
				'email' => $member->email,
				'team_member_id' => $member->id,
			];
		}

		return [
			'firstname' => $data['firstname'] ?? null,
			'name' => $data['name'],
			'email' => $data['email'],
			'team_member_id' => null,
		];
	}
}

<?php

namespace App\Actions\TeamMember;

use App\Models\TeamMember;

class StoreAction
{
	public function execute(array $data): TeamMember
	{
		$data['slug'] = (new GenerateSlugAction)->execute($data['firstname'], $data['name']);

		return TeamMember::create($data);
	}
}

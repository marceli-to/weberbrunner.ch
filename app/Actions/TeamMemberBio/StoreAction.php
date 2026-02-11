<?php

namespace App\Actions\TeamMemberBio;

use App\Models\TeamMember;
use App\Models\TeamMemberBio;

class StoreAction
{
	public function execute(TeamMember $teamMember, array $data): TeamMemberBio
	{
		return $teamMember->bios()->create($data);
	}
}

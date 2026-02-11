<?php

namespace App\Actions\TeamMemberBio;

use App\Models\TeamMemberBio;

class DeleteAction
{
	public function execute(TeamMemberBio $bio): void
	{
		$bio->delete();
	}
}

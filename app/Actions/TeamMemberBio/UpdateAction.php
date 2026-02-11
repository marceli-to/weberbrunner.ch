<?php

namespace App\Actions\TeamMemberBio;

use App\Models\TeamMemberBio;

class UpdateAction
{
	public function execute(TeamMemberBio $bio, array $data): TeamMemberBio
	{
		$bio->update($data);

		return $bio;
	}
}

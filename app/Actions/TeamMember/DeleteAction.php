<?php

namespace App\Actions\TeamMember;

use App\Actions\Media\DeleteAction as DeleteMediaAction;
use App\Models\TeamMember;

class DeleteAction
{
	public function execute(TeamMember $teamMember): void
	{
		$deleteMedia = new DeleteMediaAction;

		foreach ($teamMember->media as $media) {
			$deleteMedia->execute($media);
		}

		$teamMember->delete();
	}
}

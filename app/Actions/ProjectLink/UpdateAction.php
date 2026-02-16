<?php

namespace App\Actions\ProjectLink;

use App\Models\ProjectLink;

class UpdateAction
{
	public function execute(ProjectLink $link, array $data): ProjectLink
	{
		$link->update($data);

		return $link;
	}
}

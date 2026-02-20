<?php

namespace App\Actions\ProjectBlockLink;

use App\Models\ProjectBlockLink;

class UpdateAction
{
	public function execute(ProjectBlockLink $link, array $data): ProjectBlockLink
	{
		$link->update($data);

		return $link;
	}
}

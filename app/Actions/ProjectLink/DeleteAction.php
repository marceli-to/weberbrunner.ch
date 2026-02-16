<?php

namespace App\Actions\ProjectLink;

use App\Models\ProjectLink;

class DeleteAction
{
	public function execute(ProjectLink $link): void
	{
		$link->delete();
	}
}

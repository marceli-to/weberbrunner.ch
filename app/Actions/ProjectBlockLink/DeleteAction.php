<?php

namespace App\Actions\ProjectBlockLink;

use App\Models\ProjectBlockLink;

class DeleteAction
{
	public function execute(ProjectBlockLink $link): void
	{
		$link->delete();
	}
}

<?php

namespace App\Actions\ProjectBlockLink;

use App\Models\ProjectBlockLink;

class ToggleAction
{
	public function execute(ProjectBlockLink $link): void
	{
		$link->update(['publish' => !$link->publish]);
	}
}

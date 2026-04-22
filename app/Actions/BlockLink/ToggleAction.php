<?php

namespace App\Actions\BlockLink;

use App\Models\BlockLink;

class ToggleAction
{
	public function execute(BlockLink $link): void
	{
		$link->update(['publish' => !$link->publish]);
	}
}

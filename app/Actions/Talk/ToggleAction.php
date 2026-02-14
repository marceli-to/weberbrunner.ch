<?php

namespace App\Actions\Talk;

use App\Models\Talk;

class ToggleAction
{
	public function execute(Talk $talk): void
	{
		$talk->update(['publish' => !$talk->publish]);
	}
}

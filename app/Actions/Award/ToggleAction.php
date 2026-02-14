<?php

namespace App\Actions\Award;

use App\Models\Award;

class ToggleAction
{
	public function execute(Award $award): void
	{
		$award->update(['publish' => !$award->publish]);
	}
}

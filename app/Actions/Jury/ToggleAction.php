<?php

namespace App\Actions\Jury;

use App\Models\Jury;

class ToggleAction
{
	public function execute(Jury $jury): void
	{
		$jury->update(['publish' => !$jury->publish]);
	}
}

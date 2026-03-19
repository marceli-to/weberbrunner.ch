<?php

namespace App\Actions\Publication;

use App\Models\Publication;

class ToggleAction
{
	public function execute(Publication $publication): void
	{
		$publication->update(['publish' => !$publication->publish]);
	}
}

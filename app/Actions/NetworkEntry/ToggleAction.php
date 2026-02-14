<?php

namespace App\Actions\NetworkEntry;

use App\Models\NetworkEntry;

class ToggleAction
{
	public function execute(NetworkEntry $entry): void
	{
		$entry->update(['publish' => !$entry->publish]);
	}
}

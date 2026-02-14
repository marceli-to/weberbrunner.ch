<?php

namespace App\Actions\NetworkEntry;

use App\Models\NetworkEntry;

class UpdateAction
{
	public function execute(NetworkEntry $entry, array $data): NetworkEntry
	{
		$entry->update($data);

		return $entry;
	}
}

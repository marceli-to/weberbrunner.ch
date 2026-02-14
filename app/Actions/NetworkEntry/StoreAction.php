<?php

namespace App\Actions\NetworkEntry;

use App\Models\NetworkEntry;

class StoreAction
{
	public function execute(array $data): NetworkEntry
	{
		return NetworkEntry::create($data);
	}
}

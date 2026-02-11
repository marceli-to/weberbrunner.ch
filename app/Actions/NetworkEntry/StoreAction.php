<?php

namespace App\Actions\NetworkEntry;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Models\NetworkEntry;

class StoreAction
{
	public function execute(array $data): NetworkEntry
	{
		$media = $data['media'] ?? [];
		unset($data['media']);

		$entry = NetworkEntry::create($data);

		if (!empty($media)) {
			(new AttachMediaAction)->execute($media, $entry);
		}

		return $entry;
	}
}

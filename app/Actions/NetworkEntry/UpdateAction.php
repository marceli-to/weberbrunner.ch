<?php

namespace App\Actions\NetworkEntry;

use App\Actions\Media\AttachAction as AttachMediaAction;
use App\Models\NetworkEntry;

class UpdateAction
{
	public function execute(NetworkEntry $entry, array $data): NetworkEntry
	{
		$media = $data['media'] ?? [];
		unset($data['media']);

		$entry->update($data);

		if (!empty($media)) {
			(new AttachMediaAction)->execute($media, $entry);
		}

		return $entry;
	}
}

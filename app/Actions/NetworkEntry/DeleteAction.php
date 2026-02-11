<?php

namespace App\Actions\NetworkEntry;

use App\Actions\Media\DeleteAction as DeleteMediaAction;
use App\Models\NetworkEntry;

class DeleteAction
{
	public function execute(NetworkEntry $entry): void
	{
		$deleteMedia = new DeleteMediaAction;

		foreach ($entry->media as $media) {
			$deleteMedia->execute($media);
		}

		$entry->delete();
	}
}

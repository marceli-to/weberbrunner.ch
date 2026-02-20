<?php

namespace App\Actions\Media;

use App\Models\Media;

class TogglePublishAction
{
	public function execute(Media $media): Media
	{
		$media->update(['is_published' => !$media->is_published]);

		return $media;
	}
}

<?php

namespace App\Actions\Media;

use App\Models\Media;

class TogglePublishAction
{
	public function execute(Media $media): Media
	{
		$media->update(['publish' => !$media->publish]);

		return $media;
	}
}

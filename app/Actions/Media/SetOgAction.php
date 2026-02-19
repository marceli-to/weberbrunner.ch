<?php

namespace App\Actions\Media;

use App\Models\Media;

class SetOgAction
{
	public function execute(Media $media): Media
	{
		// Unset all OG images for the same entity
		Media::where('mediable_type', $media->mediable_type)
			->where('mediable_id', $media->mediable_id)
			->update(['is_og' => false]);

		// Set the new OG image
		$media->update(['is_og' => true]);

		return $media;
	}
}

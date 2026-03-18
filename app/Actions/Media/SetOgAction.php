<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Support\Facades\DB;

class SetOgAction
{
	public function execute(Media $media): Media
	{
		return DB::transaction(function () use ($media): Media {
			// Reset all related media first; the selected item is set back to true immediately below.
			Media::where('mediable_type', $media->mediable_type)
				->where('mediable_id', $media->mediable_id)
				->update(['is_og' => false]);

			$media->update(['is_og' => true]);

			return $media;
		});
	}
}

<?php

namespace App\Actions\Block;

use App\Models\Block;
use App\Models\Media;
use Illuminate\Support\Str;

class SelectMediaAction
{
	public function execute(Block $block, array $mediaUuids): void
	{
		$maxSort = $block->media()->max('sort_order') ?? -1;

		foreach ($mediaUuids as $uuid) {
			$source = Media::where('uuid', $uuid)->first();

			if (!$source) {
				continue;
			}

			$maxSort++;
			$block->media()->create([
				'uuid' => Str::uuid(),
				'file' => $source->file,
				'original_name' => $source->original_name,
				'mime_type' => $source->mime_type,
				'size' => $source->size,
				'width' => $source->width,
				'height' => $source->height,
				'alt' => $source->alt,
				'caption' => $source->caption,
				'credits' => $source->credits,
				'sort_order' => $maxSort,
			]);
		}
	}
}

<?php

namespace App\Actions\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachAction
{
	public function execute(array $mediaItems, Model $parent): void
	{
		$maxSort = $parent->media()->max('sort_order') ?? -1;

		foreach ($mediaItems as $item) {
			$tempPath = 'temp/' . $item['file'];

			if (!Storage::disk('public')->exists($tempPath)) {
				continue;
			}

			$filename = $this->uniqueFilename($item['file']);
			Storage::disk('public')->move($tempPath, 'uploads/' . $filename);

			$maxSort++;
			$parent->media()->create([
				'uuid' => $item['uuid'],
				'file' => 'uploads/' . $filename,
				'original_name' => $item['original_name'],
				'mime_type' => $item['mime_type'],
				'size' => $item['size'],
				'width' => $item['width'] ?? null,
				'height' => $item['height'] ?? null,
				'alt' => $item['alt'] ?? null,
				'caption' => $item['caption'] ?? null,
				'sort_order' => $maxSort,
			]);
		}
	}

	private function uniqueFilename(string $filename): string
	{
		$name = pathinfo($filename, PATHINFO_FILENAME);
		$extension = Str::lower(pathinfo($filename, PATHINFO_EXTENSION));
		$suffix = Str::random(6);

		return $name . '-' . $suffix . '.' . $extension;
	}
}

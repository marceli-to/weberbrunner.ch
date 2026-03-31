<?php

namespace App\Actions\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class AttachAction
{
	public function execute(array $mediaItems, Model $parent): void
	{
		$disk = Storage::disk('public');
		$movedFiles = [];

		try {
			DB::transaction(function () use ($mediaItems, $parent, $disk, &$movedFiles): void {
				$maxSort = $parent->media()->max('sort_order') ?? -1;

				foreach ($mediaItems as $item) {
					$tempPath = 'temp/' . $item['file'];

					if (!$disk->exists($tempPath)) {
						continue;
					}

					$filename = $this->uniqueFilename($item['file']);
					$uploadPath = 'uploads/' . $filename;

					if (!$disk->move($tempPath, $uploadPath)) {
						throw new RuntimeException('Failed to move uploaded media into the permanent storage location.');
					}

					$movedFiles[] = [$tempPath, $uploadPath];

					$maxSort++;
					$parent->media()->create([
						'uuid' => $item['uuid'],
						'file' => $uploadPath,
						'original_name' => $item['original_name'],
						'mime_type' => $item['mime_type'],
						'size' => $item['size'],
						'width' => $item['width'] ?? null,
						'height' => $item['height'] ?? null,
						'alt' => $item['alt'] ?? null,
						'caption' => $item['caption'] ?? null,
						'is_download' => $item['is_download'] ?? false,
						'sort_order' => $maxSort,
					]);
				}
			});
		} catch (Throwable $e) {
			foreach (array_reverse($movedFiles) as [$tempPath, $uploadPath]) {
				if ($disk->exists($uploadPath) && !$disk->exists($tempPath)) {
					$disk->move($uploadPath, $tempPath);
				}
			}

			throw $e;
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

<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class PersistAction
{
	public function execute(array $item): Media
	{
		$disk = Storage::disk('public');
		$tempPath = 'temp/' . $item['file'];

		if (!$disk->exists($tempPath)) {
			throw new RuntimeException('Temp file does not exist.');
		}

		$filename = $this->uniqueFilename($item['file']);
		$uploadPath = 'uploads/' . $filename;

		if (!$disk->move($tempPath, $uploadPath)) {
			throw new RuntimeException('Failed to move uploaded media into the permanent storage location.');
		}

		return Media::create([
			'uuid' => $item['uuid'],
			'file' => $uploadPath,
			'original_name' => $item['original_name'],
			'mime_type' => $item['mime_type'],
			'size' => $item['size'],
			'width' => $item['width'] ?? null,
			'height' => $item['height'] ?? null,
			'alt' => $item['alt'] ?? null,
			'caption' => $item['caption'] ?? null,
			'sort_order' => 0,
		]);
	}

	private function uniqueFilename(string $filename): string
	{
		$name = pathinfo($filename, PATHINFO_FILENAME);
		$extension = Str::lower(pathinfo($filename, PATHINFO_EXTENSION));
		$suffix = Str::random(6);

		return $name . '-' . $suffix . '.' . $extension;
	}
}

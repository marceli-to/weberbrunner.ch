<?php

namespace App\Support;

use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

class ImageDownsizer
{
	public static function downsizeIfNeeded(string $absolutePath, ?int $maxEdge = null, ?int $quality = null): ?array
	{
		$maxEdge ??= (int) config('media.max_upload_edge', 3000);
		$quality ??= (int) config('media.upload_quality', 85);

		$dimensions = @getimagesize($absolutePath);
		if (!$dimensions) {
			return null;
		}

		[$width, $height] = $dimensions;
		$longEdge = max($width, $height);

		if ($longEdge <= $maxEdge) {
			return [
				'width' => $width,
				'height' => $height,
				'size' => filesize($absolutePath),
				'resized' => false,
			];
		}

		$manager = new ImageManager(new ImagickDriver());
		$image = $manager->read($absolutePath);

		if ($width >= $height) {
			$image->scaleDown(width: $maxEdge);
		} else {
			$image->scaleDown(height: $maxEdge);
		}

		$extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
		$encoded = match ($extension) {
			'png' => $image->toPng(),
			'webp' => $image->toWebp(quality: $quality),
			'gif' => $image->toGif(),
			default => $image->toJpeg(quality: $quality, progressive: true),
		};

		$encoded->save($absolutePath);

		$newDimensions = @getimagesize($absolutePath);

		return [
			'width' => $newDimensions[0] ?? null,
			'height' => $newDimensions[1] ?? null,
			'size' => filesize($absolutePath),
			'resized' => true,
		];
	}
}

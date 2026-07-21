<?php

namespace App\Support;

use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

class ImageDownsizer
{
	public static function downsizeIfNeeded(string $absolutePath, ?int $maxLongEdge = null, ?int $quality = null, ?int $maxShortEdge = null): ?array
	{
		$maxLongEdge ??= (int) config('media.max_upload_edge', 6000);
		$maxShortEdge ??= (int) config('media.max_upload_short_edge', 1400);
		$quality ??= (int) config('media.upload_quality', 85);

		$dimensions = @getimagesize($absolutePath);
		if (!$dimensions) {
			return null;
		}

		[$width, $height] = $dimensions;

		if (max($width, $height) <= $maxLongEdge && min($width, $height) <= $maxShortEdge) {
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
			$image->scaleDown(width: $maxLongEdge, height: $maxShortEdge);
		} else {
			$image->scaleDown(width: $maxShortEdge, height: $maxLongEdge);
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

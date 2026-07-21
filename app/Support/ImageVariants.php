<?php

namespace App\Support;

class ImageVariants
{
	public static function byHeight(?int $sourceWidth, ?int $sourceHeight, ?int $maxHeight = null): array
	{
		if (!$sourceWidth || !$sourceHeight) {
			return [];
		}

		$variants = [];

		foreach (config('media.heights', []) as $h) {
			if ($maxHeight && $h > $maxHeight) {
				continue;
			}
			if ($h > $sourceHeight) {
				continue;
			}

			$w = (int) round($h * $sourceWidth / $sourceHeight);
			if ($w < 1 || $w > $sourceWidth) {
				continue;
			}

			$variants[$w] = ['w' => $w, 'h' => $h];
		}

		if (empty($variants)) {
			$variants[$sourceWidth] = ['w' => $sourceWidth, 'h' => $sourceHeight];
		}

		ksort($variants);

		return array_values($variants);
	}
}

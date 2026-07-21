<?php

namespace App\Support;

class ImageVariants
{
	public static function byHeight(?int $sourceWidth, ?int $sourceHeight, ?int $maxHeight = null): array
	{
		if (!$sourceWidth || !$sourceHeight) {
			return [];
		}

		$ceiling = $maxHeight ? min($maxHeight, $sourceHeight) : $sourceHeight;

		$variants = [];

		foreach (config('media.heights', []) as $h) {
			if ($h > $ceiling) {
				continue;
			}

			$w = (int) round($h * $sourceWidth / $sourceHeight);
			if ($w < 1 || $w > $sourceWidth) {
				continue;
			}

			$variants[$w] = ['w' => $w, 'h' => $h];
		}

		$ceilingWidth = min($sourceWidth, (int) round($ceiling * $sourceWidth / $sourceHeight));
		if ($ceilingWidth > 0 && !isset($variants[$ceilingWidth])) {
			$variants[$ceilingWidth] = ['w' => $ceilingWidth, 'h' => $ceiling];
		}

		ksort($variants);

		return array_values($variants);
	}
}

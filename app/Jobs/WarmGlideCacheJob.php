<?php

namespace App\Jobs;

use App\Models\Media;
use App\Support\ImageVariants;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Glide\ServerFactory;

class WarmGlideCacheJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public int $tries = 2;
	public int $timeout = 300;

	public function __construct(public string $mediaUuid)
	{
	}

	public function handle(): void
	{
		@ini_set('memory_limit', config('media.warm_memory_limit', '512M'));

		$media = Media::where('uuid', $this->mediaUuid)->first();
		if (!$media || !str_starts_with((string) $media->mime_type, 'image/')) {
			return;
		}

		$server = ServerFactory::create([
			'source' => storage_path('app/public'),
			'cache' => storage_path('app/.glide-cache'),
			'driver' => 'imagick',
		]);

		$widths = array_values(array_filter(
			config('media.widths', []),
			fn ($w) => !$media->width || $w <= $media->width
		));
		if (empty($widths) && $media->width) {
			$widths = [$media->width];
		}

		$formats = config('media.formats', ['avif', 'webp', 'jpg']);
		$fits = config('media.fits', ['crop', 'max']);
		$quality = (int) config('media.quality', 90);

		$aspectRatio = $media->height / max($media->width, 1);

		$heightVariants = ImageVariants::byHeight($media->width, $media->height);

		foreach ($formats as $format) {
			$fm = $format === 'jpeg' ? 'jpg' : $format;

			foreach ($fits as $fit) {
				foreach ($widths as $w) {
					$h = (int) round($w * $aspectRatio);
					$server->makeImage($media->file, [
						'w' => $w,
						'h' => $h,
						'fit' => $fit,
						'fm' => $fm,
						'q' => $quality,
					]);
				}
			}

			foreach ($heightVariants as $variant) {
				$server->makeImage($media->file, [
					'w' => $variant['w'],
					'h' => $variant['h'],
					'fit' => 'max',
					'fm' => $fm,
					'q' => $quality,
				]);
			}
		}
	}
}

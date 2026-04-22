<?php

namespace App\Console\Commands;

use App\Jobs\WarmGlideCacheJob;
use App\Models\Media;
use App\Support\ImageDownsizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ReprocessMedia extends Command
{
	protected $signature = 'media:reprocess {--no-warm : Skip dispatching Glide cache warm jobs} {--no-resize : Skip resizing originals, only warm cache}';

	protected $description = 'Downsize oversized media originals and dispatch Glide cache warm jobs';

	public function handle(): int
	{
		@ini_set('memory_limit', config('media.warm_memory_limit', '512M'));

		$disk = Storage::disk('public');
		$maxEdge = (int) config('media.max_upload_edge', 3000);

		$query = Media::query()->where('mime_type', 'like', 'image/%');
		$total = $query->count();

		if ($total === 0) {
			$this->info('No image media found.');
			return self::SUCCESS;
		}

		$this->info("Processing {$total} image(s). Max edge: {$maxEdge}px.");
		$bar = $this->output->createProgressBar($total);
		$bar->start();

		$resized = 0;
		$warmed = 0;
		$missing = 0;

		$query->chunkById(50, function ($chunk) use ($disk, &$resized, &$warmed, &$missing, $bar) {
			foreach ($chunk as $media) {
				if (!$disk->exists($media->file)) {
					$missing++;
					$bar->advance();
					continue;
				}

				if (!$this->option('no-resize')) {
					$absolute = $disk->path($media->file);
					$info = ImageDownsizer::downsizeIfNeeded($absolute);
					if ($info && $info['resized']) {
						$media->update([
							'width' => $info['width'],
							'height' => $info['height'],
							'size' => $info['size'],
						]);
						$resized++;
					} elseif ($info && (!$media->width || !$media->height)) {
						$media->update([
							'width' => $info['width'],
							'height' => $info['height'],
							'size' => $info['size'],
						]);
					}
				}

				if (!$this->option('no-warm')) {
					WarmGlideCacheJob::dispatch($media->uuid);
					$warmed++;
				}

				$bar->advance();
			}
		});

		$bar->finish();
		$this->newLine(2);
		$this->info("Resized: {$resized}. Warm jobs dispatched: {$warmed}. Missing files: {$missing}.");

		return self::SUCCESS;
	}
}

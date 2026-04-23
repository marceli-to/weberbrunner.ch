<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupDeferredMedia extends Command
{
	protected $signature = 'media:cleanup';

	protected $description = 'Delete temp upload files older than 24 hours';

	public function handle(): int
	{
		$count = $this->purgeStale(Storage::disk('public'), 'temp')
			+ $this->purgeStale(Storage::disk('originals'), 'temp');

		$this->info("Deleted {$count} temp file(s).");

		return self::SUCCESS;
	}

	private function purgeStale($disk, string $directory): int
	{
		$count = 0;

		foreach ($disk->files($directory) as $file) {
			$lastModified = Carbon::createFromTimestamp($disk->lastModified($file));

			if ($lastModified->lt(now()->subHours(24))) {
				$disk->delete($file);
				$count++;
			}
		}

		return $count;
	}
}

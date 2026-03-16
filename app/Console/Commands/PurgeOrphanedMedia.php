<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeOrphanedMedia extends Command
{
	protected $signature = 'media:purge-orphans {--force : Actually delete files (default is dry-run)}';

	protected $description = 'Delete files in uploads/ with no matching Media record';

	public function handle(): int
	{
		$disk = Storage::disk('public');
		$files = $disk->files('uploads');

		$referenced = Media::pluck('file')->flip();

		$orphans = array_filter($files, fn($file) => !$referenced->has($file));

		if (empty($orphans)) {
			$this->info('No orphaned files found.');
			return self::SUCCESS;
		}

		$isDryRun = !$this->option('force');

		foreach ($orphans as $file) {
			$this->line(($isDryRun ? '[dry-run] ' : '') . "Deleting: {$file}");
			if (!$isDryRun) {
				$disk->delete($file);
			}
		}

		$count = count($orphans);
		$this->info($isDryRun
			? "{$count} orphaned file(s) found. Run with --force to delete."
			: "Deleted {$count} orphaned file(s)."
		);

		return self::SUCCESS;
	}
}

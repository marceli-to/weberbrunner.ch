<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ArchiveOriginals extends Command
{
	protected $signature = 'media:archive-originals';

	protected $description = 'Copy current upload masters to the originals disk for any image media that has not been archived yet';

	public function handle(): int
	{
		$publicDisk = Storage::disk('public');
		$originalsDisk = Storage::disk('originals');

		$query = Media::query()
			->where('mime_type', 'like', 'image/%')
			->where('file', 'like', 'uploads/%');
		$total = $query->count();

		if ($total === 0) {
			$this->info('No image media found.');
			return self::SUCCESS;
		}

		$this->info("Checking {$total} image(s) for missing originals.");
		$bar = $this->output->createProgressBar($total);
		$bar->start();

		$copied = 0;
		$skipped = 0;
		$missing = 0;

		$query->chunkById(50, function ($chunk) use ($publicDisk, $originalsDisk, &$copied, &$skipped, &$missing, $bar) {
			foreach ($chunk as $media) {
				if ($originalsDisk->exists($media->file)) {
					$skipped++;
					$bar->advance();
					continue;
				}

				if (!$publicDisk->exists($media->file)) {
					$missing++;
					$bar->advance();
					continue;
				}

				$stream = fopen($publicDisk->path($media->file), 'r');
				if ($stream === false) {
					$missing++;
					$bar->advance();
					continue;
				}

				try {
					$originalsDisk->writeStream($media->file, $stream);
					$copied++;
				} finally {
					if (is_resource($stream)) {
						fclose($stream);
					}
				}

				$bar->advance();
			}
		});

		$bar->finish();
		$this->newLine(2);
		$this->info("Copied: {$copied}. Already archived: {$skipped}. Missing source files: {$missing}.");

		return self::SUCCESS;
	}
}

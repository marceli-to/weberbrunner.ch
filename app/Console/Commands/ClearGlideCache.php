<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearGlideCache extends Command
{
	protected $signature = 'glide:clear';

	protected $description = 'Delete all cached Glide images';

	public function handle(Filesystem $files): int
	{
		$path = storage_path('app/.glide-cache');

		if (!$files->isDirectory($path)) {
			$this->info('Glide cache is already empty.');

			return self::SUCCESS;
		}

		$files->cleanDirectory($path);

		$this->info('Glide cache cleared.');

		return self::SUCCESS;
	}
}

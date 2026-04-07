<?php

namespace App\Console\Commands;

use App\Models\PublicationAttribute;
use Illuminate\Console\Command;

class RemoveDownloadAttributes extends Command
{
	protected $signature = 'app:remove-download-attributes';

	protected $description = 'Remove publication attributes with key "Download" (now handled via media)';

	public function handle(): void
	{
		$count = PublicationAttribute::where('key', 'Download')->count();

		if ($count === 0) {
			$this->info('No download attributes found.');
			return;
		}

		$this->info("Found {$count} download attribute(s).");

		PublicationAttribute::where('key', 'Download')->delete();

		$this->info("Deleted {$count} download attribute(s).");
	}
}

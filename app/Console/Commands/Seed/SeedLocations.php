<?php

namespace App\Console\Commands\Seed;

use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class SeedLocations extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:seed-locations {--force : Force the operation to run in production}';

	protected $description = 'Seed locations';

	private array $locations = [
		'zuerich' => 'Zürich',
		'berlin' => 'Berlin',
	];

	public function handle(): void
	{
		if (!$this->confirmToProceed()) {
			return;
		}

		foreach ($this->locations as $slug => $title) {
			Location::firstOrCreate(
				['slug' => $slug],
				['title' => $title]
			);
			$this->line("  Created: {$title}");
		}

		$this->info("Done! Created " . count($this->locations) . " locations.");
	}
}

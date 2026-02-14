<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class SeedLocations extends Command
{
	protected $signature = 'app:seed-locations';

	protected $description = 'Seed locations';

	private array $locations = [
		'zuerich' => 'Zürich',
		'berlin' => 'Berlin',
	];

	public function handle(): void
	{
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

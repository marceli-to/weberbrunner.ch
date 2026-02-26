<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedProjects extends Command
{
	protected $signature = 'app:seed-projects';

	protected $description = 'Seed projects from project-data.json';

	public function handle(): void
	{
		$path = storage_path('app/project-data.json');

		if (!file_exists($path)) {
			$this->error('File not found: storage/app/project-data.json');
			return;
		}

		$data = json_decode(file_get_contents($path), true);

		if (!is_array($data)) {
			$this->error('Invalid JSON in project-data.json');
			return;
		}

		$zurich = Location::where('slug', 'zuerich')->first();
		$berlin = Location::where('slug', 'berlin')->first();

		if (!$zurich || !$berlin) {
			$this->error('Locations not found. Make sure zurich and berlin locations exist.');
			return;
		}

		$this->info('Seeding ' . count($data) . ' projects...');

		$created = 0;

		foreach ($data as $entry) {
			$number = $entry['Projektnummer'];
			$title = $entry['Projektname'];
			$city = $entry['Ort'] ?? null;
			$priority = $entry['Priorität (A=hoch 50, B=mittel 200, C=tief 300)'] ?? null;
			$location = (int) $number >= 1000 ? $berlin : $zurich;

			$slug = Str::slug($title) . '-' . $number;

			if (Project::where('slug', $slug)->exists()) {
				$slug .= '-' . Str::random(4);
			}

			Project::create([
				'priority' => $priority,
				'number' => $number,
				'title' => $title,
				'slug' => $slug,
				'city' => $city,
				'location_id' => $location->id,
				'publish' => false,
			]);

			$created++;
			$this->line("  [{$number}] {$title}");
		}

		$this->info("Done! Created {$created} projects.");
	}
}

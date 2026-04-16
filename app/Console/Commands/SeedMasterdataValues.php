<?php

namespace App\Console\Commands;

use App\Models\Masterdata;
use App\Models\MasterdataGroup;
use App\Models\MasterdataProject;
use App\Models\Project;
use Illuminate\Console\Command;

class SeedMasterdataValues extends Command
{
	protected $signature = 'app:seed-masterdata-values';

	protected $description = 'Import per-project masterdata values from storage/app/projects-masterdata-complete.json';

	public function handle(): void
	{
		$path = storage_path('app/projects-masterdata-complete.json');

		if (! file_exists($path)) {
			$this->error('File not found: ' . $path);
			return;
		}

		$data = json_decode(file_get_contents($path), true);

		if (! is_array($data)) {
			$this->error('Invalid JSON in projects-masterdata-complete.json');
			return;
		}

		MasterdataProject::truncate();

		$projects = Project::pluck('id', 'uuid');
		$groups = MasterdataGroup::with('masterdata')->get()->keyBy('title');

		$masterdataLookup = [];
		foreach ($groups as $groupTitle => $group) {
			foreach ($group->masterdata as $entry) {
				$masterdataLookup[$groupTitle][$entry->title] = $entry->id;
			}
		}

		$created = 0;
		$skippedProjects = 0;
		$skippedEntries = 0;

		foreach ($data as $project) {
			$projectId = $projects[$project['uuid']] ?? null;

			if (! $projectId) {
				$this->warn("  Project #{$project['number']} (uuid: {$project['uuid']}) not found, skipping");
				$skippedProjects++;
				continue;
			}

			$masterdata = $project['masterdata'] ?? [];

			if (empty($masterdata)) {
				continue;
			}

			$sortOrder = 0;

			foreach ($masterdata as $groupTitle => $entries) {
				foreach ($entries as $entryTitle => $value) {
					$masterdataId = $masterdataLookup[$groupTitle][$entryTitle] ?? null;

					if (! $masterdataId) {
						$this->warn("  Entry [{$groupTitle}] {$entryTitle} not found, skipping");
						$skippedEntries++;
						continue;
					}

					MasterdataProject::create([
						'project_id' => $projectId,
						'masterdata_id' => $masterdataId,
						'value' => (string) $value,
						'sort_order' => $sortOrder++,
						'publish' => true,
					]);

					$created++;
				}
			}

			$this->line("  Project #{$project['number']}: assigned values");
		}

		$this->info("Done! Created {$created} values.");

		if ($skippedProjects > 0) {
			$this->warn("Skipped {$skippedProjects} projects (not found in DB).");
		}

		if ($skippedEntries > 0) {
			$this->warn("Skipped {$skippedEntries} entries (not found in masterdata).");
		}
	}
}

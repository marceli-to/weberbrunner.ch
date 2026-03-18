<?php

namespace App\Console\Commands;

use App\Models\Masterdata;
use App\Models\MasterdataGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SeedMasterdata extends Command
{
	protected $signature = 'app:seed-masterdata';

	protected $description = 'Seed masterdata groups and entries from storage/app/master-data.json';

	public function handle(): void
	{
		$path = storage_path('app/master-data.json');

		if (! file_exists($path)) {
			$this->error('File not found: ' . $path);
			return;
		}

		$data = json_decode(file_get_contents($path), true);

		if (! is_array($data)) {
			$this->error('Invalid JSON in master-data.json');
			return;
		}

		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		Masterdata::truncate();
		MasterdataGroup::truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1');

		$groupCount = 0;
		$entryCount = 0;

		foreach ($data as $groupTitle => $entries) {
			$group = MasterdataGroup::firstOrCreate(['title' => $groupTitle]);

			if ($group->wasRecentlyCreated) {
				$groupCount++;
			}

			$this->line("  [{$groupTitle}]");

			foreach ($entries as $entryTitle) {
				$entry = Masterdata::firstOrCreate([
					'title' => $entryTitle,
					'masterdata_group_id' => $group->id,
				]);

				if ($entry->wasRecentlyCreated) {
					$entryCount++;
				}

				$this->line("    {$entryTitle}");
			}
		}

		$this->info("Done! Created {$groupCount} groups and {$entryCount} entries.");
	}
}

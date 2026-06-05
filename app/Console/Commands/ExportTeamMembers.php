<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\TeamMember;
use Illuminate\Console\Command;

class ExportTeamMembers extends Command
{
	protected $signature = 'app:export-team-members
		{--location=berlin : Location slug to filter by}
		{--path= : Output CSV file path. If omitted, prints to stdout.}';

	protected $description = 'Export team members (firstname, name, email) for a given location as CSV';

	public function handle(): int
	{
		$slug = $this->option('location');

		$location = Location::where('slug', $slug)->first();

		if (! $location) {
			$this->error("Location not found: {$slug}");
			return self::FAILURE;
		}

		$members = TeamMember::where('location_id', $location->id)
			->orderBy('name')
			->orderBy('firstname')
			->get(['firstname', 'name', 'email']);

		$handle = $this->option('path')
			? fopen($this->option('path'), 'w')
			: fopen('php://output', 'w');

		fputcsv($handle, ['firstname', 'name', 'email']);

		foreach ($members as $member) {
			fputcsv($handle, [$member->firstname, $member->name, $member->email]);
		}

		fclose($handle);

		if ($path = $this->option('path')) {
			$this->info("Exported {$members->count()} team member(s) from \"{$location->title}\" to {$path}");
		}

		return self::SUCCESS;
	}
}

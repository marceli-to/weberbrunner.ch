<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\Location;
use Illuminate\Console\Command;

class SeedJobs extends Command
{
	protected $signature = 'app:seed-jobs';

	protected $description = 'Seed job listings';

	private array $jobs = [
		[
			'location' => 'zuerich',
			'title' => 'Architekt:in',
			'description' => '<p>Für die Mitarbeit an interessanten Wettbewerben und Projekten suchen wir ab sofort oder nach Vereinbarung motivierte Praktikant:innen. Voraussetzung sind zwei Jahre Studium oder Bachelorabschluss (immatrikuliert), gute Computerkenntnisse in 2D / 3D bevorzugt in Revit, Rhino und Affinity Suite, Praktikumsdauer mindestens sechs Monate.</p>',
			'contact_email' => 'bewerbungen@weberbrunner.ch',
		],
		[
			'location' => 'berlin',
			'title' => 'Praktikant:in',
			'description' => '<p>Für die Mitarbeit an interessanten Wettbewerben und Projekten suchen wir ab sofort oder nach Vereinbarung motivierte Praktikant:innen. Voraussetzung sind zwei Jahre Studium oder Bachelorabschluss (immatrikuliert), gute Computerkenntnisse in 2D / 3D bevorzugt in Revit, Rhino und Affinity Suite, Praktikumsdauer mindestens sechs Monate.</p>',
			'contact_email' => 'bewerbungen@wbp-architektur.de',
		],
	];

	public function handle(): void
	{
		foreach ($this->jobs as $order => $data) {
			$location = Location::where('slug', $data['location'])->first();

			$job = Job::create([
				'title' => $data['title'],
				'description' => $data['description'],
				'contact_email' => $data['contact_email'],
				'location_id' => $location->id,
				'publish' => true,
				'sort_order' => $order + 1,
			]);

			$this->line("  Created: {$job->title} ({$location->title})");
		}

		$this->info("Done! Created " . count($this->jobs) . " job listings.");
	}
}

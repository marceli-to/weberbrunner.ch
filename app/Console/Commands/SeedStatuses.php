<?php

namespace App\Console\Commands;

use App\Models\Status;
use Illuminate\Console\Command;

class SeedStatuses extends Command
{
	protected $signature = 'app:seed-statuses';

	protected $description = 'Seed project statuses';

	private array $statuses = [
		'projekte' => 'Projekte',
		'in-bearbeitung' => 'In Bearbeitung',
		'realisiert' => 'Realisiert',
	];

	public function handle(): void
	{
		foreach ($this->statuses as $slug => $title) {
			Status::firstOrCreate(
				['slug' => $slug],
				['title' => $title]
			);
			$this->line("  {$title}");
		}

		$this->info('Done! Seeded ' . count($this->statuses) . ' statuses.');
	}
}

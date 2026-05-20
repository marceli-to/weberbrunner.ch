<?php

namespace App\Console\Commands\Seed;

use App\Models\Status;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class SeedStatuses extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:seed-statuses {--force : Force the operation to run in production}';

	protected $description = 'Seed project statuses';

	private array $statuses = [
		'projekte' => 'Projekte',
		'in-bearbeitung' => 'In Bearbeitung',
		'realisiert' => 'Realisiert',
	];

	public function handle(): void
	{
		if (!$this->confirmToProceed()) {
			return;
		}

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

<?php

namespace App\Console\Commands\Seed;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class SeedCategories extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:seed-categories {--force : Force the operation to run in production}';

	protected $description = 'Seed project categories';

	private array $categories = [
		'oeffentliche-gebaeude' => 'Öffentliche Gebäude',
		'wohnungsbau' => 'Wohnungsbau',
		'bauen-im-bestand' => 'Bauen im Bestand',
		'zustandsanalyse' => 'Zustandsanalyse',
		'zirkulaeres-bauen' => 'Zirkuläres Bauen',
		'lca' => 'LCA',
	];

	public function handle(): void
	{
		if (!$this->confirmToProceed()) {
			return;
		}

		foreach ($this->categories as $slug => $title) {
			Category::firstOrCreate(
				['slug' => $slug],
				['title' => $title]
			);
			$this->line("  {$title}");
		}

		$this->info('Done! Seeded ' . count($this->categories) . ' categories.');
	}
}

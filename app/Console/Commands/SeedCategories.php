<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class SeedCategories extends Command
{
	protected $signature = 'app:seed-categories';

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

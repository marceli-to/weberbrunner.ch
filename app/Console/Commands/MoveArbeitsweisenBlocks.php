<?php

namespace App\Console\Commands;

use App\Models\Block;
use App\Models\PageText;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoveArbeitsweisenBlocks extends Command
{
	protected $signature = 'app:move-arbeitsweisen-blocks {--dry-run : Show what would change without writing}';

	protected $description = 'Move Arbeitsweisen blocks from the Intro (office) PageText to the Arbeitsweisen PageText.';

	private array $keepOnIntro = ['Büro', 'Bürogeschichte', 'Kompetenzen'];

	public function handle(): int
	{
		$intro = PageText::where('page', 'office')->first();

		if (!$intro) {
			$this->error("PageText with page='office' not found. Nothing to move.");
			return self::FAILURE;
		}

		$arbeitsweisen = PageText::firstOrCreate(
			['page' => 'arbeitsweisen'],
			['title' => null, 'text' => null],
		);

		$toMove = Block::where('blockable_type', PageText::class)
			->where('blockable_id', $intro->id)
			->whereNotIn('title', $this->keepOnIntro)
			->orderBy('sort_order')
			->get();

		if ($toMove->isEmpty()) {
			$this->info('Nothing to move — Intro page has no blocks outside the keep-list.');
			return self::SUCCESS;
		}

		$this->info("Will move {$toMove->count()} block(s) from Intro → Arbeitsweisen:");
		foreach ($toMove as $b) {
			$this->line(sprintf(' - [%3d] %s', $b->sort_order, $b->title ?? '(untitled)'));
		}

		$this->newLine();
		$this->info('Blocks staying on Intro:');
		$staying = Block::where('blockable_type', PageText::class)
			->where('blockable_id', $intro->id)
			->whereIn('title', $this->keepOnIntro)
			->orderBy('sort_order')
			->get();
		foreach ($staying as $b) {
			$this->line(sprintf(' - [%3d] %s', $b->sort_order, $b->title));
		}

		if ($this->option('dry-run')) {
			$this->warn('Dry run — no changes written.');
			return self::SUCCESS;
		}

		$baseSort = (int) Block::where('blockable_type', PageText::class)
			->where('blockable_id', $arbeitsweisen->id)
			->max('sort_order');

		DB::transaction(function () use ($toMove, $arbeitsweisen, $baseSort) {
			$i = 1;
			foreach ($toMove as $block) {
				$block->blockable_id = $arbeitsweisen->id;
				$block->sort_order = $baseSort + $i;
				$block->saveQuietly();
				$i++;
			}
		});

		$this->info("Moved {$toMove->count()} block(s) to Arbeitsweisen (page='arbeitsweisen').");
		return self::SUCCESS;
	}
}

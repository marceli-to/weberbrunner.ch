<?php

namespace App\Http\Controllers;

use App\Models\PageText;
use App\Models\LandingItem;
use Illuminate\View\View;

class LandingController extends Controller
{
	public function __invoke(): View
	{
		$columns = $this->getColumns();
		$landing = PageText::where('page', 'landing')->first();

		return view('pages.landing', [
			'columns' => $columns,
			'column' => $this->getColumn($columns),
			'text' => $landing?->text ? $landing->text : null,
		]);
	}

	// Desktop: 3-column grid
	protected function getColumns(): array
	{
		$items = LandingItem::with(['project' => fn ($q) => $q->with(['media' => fn ($q) => $q->where('is_teaser', true)])])
			->orderBy('sort_order')
			->get();

		if ($items->isEmpty()) {
			return [[], [], []];
		}

		$grouped = [0 => [], 1 => [], 2 => []];

		foreach ($items as $item) {
			$grouped[$item->column - 1][] = [
				'title' => $item->project->full_title,
				'slug' => $item->project->slug,
				'media' => $item->project->media->first(),
			];
		}

		return $grouped;
	}

	// Mobile: interleaved single column
	protected function getColumn(array $columns): array
	{
		$maxLen = max(array_map('count', $columns));
		$result = [];
		for ($row = 0; $row < $maxLen; $row++) {
			foreach ($columns as $col) {
				if (isset($col[$row])) {
					$result[] = $col[$row];
				}
			}
		}
		return $result;
	}
}

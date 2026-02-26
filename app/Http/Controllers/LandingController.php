<?php

namespace App\Http\Controllers;

use App\Actions\Project\ListAction;
use App\Models\LandingItem;
use Illuminate\View\View;

class LandingController extends Controller
{
	public function __invoke(): View
	{
		$columns = $this->getHomepageColumns();

		return view('pages.landing', [
			'columns' => $columns,
			'mobileItems' => $this->interleaveForMobile($columns),
		]);
	}

	protected function getHomepageColumns(): array
	{
		$items = LandingItem::with(['project' => fn ($q) => $q->with(['media' => fn ($q) => $q->where('is_teaser', true)])])
			->orderBy('sort_order')
			->get();

		if ($items->isEmpty()) {
			$projects = (new ListAction)->execute(published: true);
			return $this->splitIntoColumns($projects, 3);
		}

		$grouped = [0 => [], 1 => [], 2 => []];

		foreach ($items as $item) {
			$media = $item->project->media->first();
			$grouped[$item->column - 1][] = [
				'title' => $item->project->full_title,
				'slug' => $item->project->slug,
				'image' => $media?->file ?? 'images/dummy-teaser-1.jpg',
				'width' => $media?->width,
				'height' => $media?->height,
				'orientation' => $media?->orientation ?? 'unknown',
				'caption' => $media?->caption,
			];
		}

		return $grouped;
	}

	protected function interleaveForMobile(array $columns): array
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

	protected function splitIntoColumns($items, int $count): array
	{
		$columns = array_fill(0, $count, []);

		foreach ($items->values() as $index => $item) {
			$columns[$index % $count][] = $item;
		}

		return $columns;
	}
}

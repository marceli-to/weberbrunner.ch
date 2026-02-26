<?php

namespace App\Actions\LandingItem;

use App\Models\LandingItem;

class ListAction
{
	public function execute(): array
	{
		$items = LandingItem::with(['project' => fn ($q) => $q->with(['media' => fn ($q) => $q->where('is_teaser', true)])])
			->orderBy('sort_order')
			->get();

		$grouped = [1 => [], 2 => [], 3 => []];

		foreach ($items as $item) {
			$grouped[$item->column][] = $item;
		}

		return $grouped;
	}
}

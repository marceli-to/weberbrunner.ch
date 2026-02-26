<?php

namespace App\Actions\LandingItem;

use App\Models\LandingItem;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			LandingItem::where('uuid', $item['uuid'])->update([
				'column' => $item['column'],
				'sort_order' => $item['sort_order'],
			]);
		}
	}
}

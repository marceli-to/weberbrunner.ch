<?php

namespace App\Actions\Location;

use App\Models\Location;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Location::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

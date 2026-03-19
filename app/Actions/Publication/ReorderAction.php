<?php

namespace App\Actions\Publication;

use App\Models\Publication;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Publication::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

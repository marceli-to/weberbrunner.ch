<?php

namespace App\Actions\NetworkEntry;

use App\Models\NetworkEntry;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			NetworkEntry::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

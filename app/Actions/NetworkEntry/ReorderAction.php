<?php

namespace App\Actions\NetworkEntry;

use App\Models\NetworkEntry;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			NetworkEntry::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

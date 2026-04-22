<?php

namespace App\Actions\Block;

use App\Models\Block;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Block::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

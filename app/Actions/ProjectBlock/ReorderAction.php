<?php

namespace App\Actions\ProjectBlock;

use App\Models\ProjectBlock;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			ProjectBlock::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

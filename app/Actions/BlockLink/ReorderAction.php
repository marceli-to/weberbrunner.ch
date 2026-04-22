<?php

namespace App\Actions\BlockLink;

use App\Models\BlockLink;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			BlockLink::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

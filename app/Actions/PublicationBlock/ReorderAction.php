<?php

namespace App\Actions\PublicationBlock;

use App\Models\PublicationBlock;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			PublicationBlock::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

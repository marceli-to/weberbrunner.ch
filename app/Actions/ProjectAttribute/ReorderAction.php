<?php

namespace App\Actions\ProjectAttribute;

use App\Models\ProjectAttribute;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			ProjectAttribute::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

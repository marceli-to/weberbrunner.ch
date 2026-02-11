<?php

namespace App\Actions\ProjectAttribute;

use App\Models\ProjectAttribute;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			ProjectAttribute::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

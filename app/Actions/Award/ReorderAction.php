<?php

namespace App\Actions\Award;

use App\Models\Award;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Award::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

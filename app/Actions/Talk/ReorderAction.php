<?php

namespace App\Actions\Talk;

use App\Models\Talk;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Talk::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

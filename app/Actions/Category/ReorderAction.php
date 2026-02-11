<?php

namespace App\Actions\Category;

use App\Models\Category;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Category::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

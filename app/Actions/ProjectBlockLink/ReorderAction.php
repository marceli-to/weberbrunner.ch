<?php

namespace App\Actions\ProjectBlockLink;

use App\Models\ProjectBlockLink;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			ProjectBlockLink::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

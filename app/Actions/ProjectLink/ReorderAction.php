<?php

namespace App\Actions\ProjectLink;

use App\Models\ProjectLink;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			ProjectLink::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

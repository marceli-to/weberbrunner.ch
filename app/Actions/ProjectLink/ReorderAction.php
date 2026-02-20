<?php

namespace App\Actions\ProjectLink;

use App\Models\ProjectLink;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			ProjectLink::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

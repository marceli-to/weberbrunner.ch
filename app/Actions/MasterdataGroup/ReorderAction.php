<?php

namespace App\Actions\MasterdataGroup;

use App\Models\MasterdataGroup;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			MasterdataGroup::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

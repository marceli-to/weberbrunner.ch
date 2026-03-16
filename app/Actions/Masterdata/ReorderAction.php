<?php

namespace App\Actions\Masterdata;

use App\Models\Masterdata;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Masterdata::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

<?php

namespace App\Actions\Status;

use App\Models\Status;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Status::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

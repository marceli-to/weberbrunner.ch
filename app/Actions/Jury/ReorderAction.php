<?php

namespace App\Actions\Jury;

use App\Models\Jury;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Jury::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

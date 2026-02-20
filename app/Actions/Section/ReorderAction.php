<?php

namespace App\Actions\Section;

use App\Models\Section;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Section::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

<?php

namespace App\Actions\TeamMember;

use App\Models\TeamMember;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			TeamMember::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

<?php

namespace App\Actions\TeamMemberBio;

use App\Models\TeamMemberBio;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			TeamMemberBio::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

<?php

namespace App\Actions\TeamMemberBio;

use App\Models\TeamMemberBio;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			TeamMemberBio::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

<?php

namespace App\Actions\Project;

use App\Models\Project;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Project::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

<?php

namespace App\Actions\Job;

use App\Models\Job;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			Job::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

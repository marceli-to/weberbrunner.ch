<?php

namespace App\Actions\Job;

use App\Models\Job;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			$data = ['sort_order' => $item['sort_order']];
			if (isset($item['location_id'])) {
				$data['location_id'] = $item['location_id'];
			}
			Job::where('uuid', $item['uuid'])->update($data);
		}
	}
}

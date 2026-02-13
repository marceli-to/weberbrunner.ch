<?php

namespace App\Actions\Award;

use App\Models\Award;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			$update = ['sort_order' => $item['sort_order']];
			if (isset($item['section_id'])) {
				$update['section_id'] = $item['section_id'];
			}
			Award::where('id', $item['id'])->update($update);
		}
	}
}

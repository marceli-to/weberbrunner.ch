<?php

namespace App\Actions\PublicationAttribute;

use App\Models\PublicationAttribute;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			PublicationAttribute::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

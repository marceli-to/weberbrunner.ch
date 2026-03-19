<?php

namespace App\Actions\PublicationBlockLink;

use App\Models\PublicationBlockLink;

class ReorderAction
{
	public function execute(array $items): void
	{
		foreach ($items as $item) {
			PublicationBlockLink::where('uuid', $item['uuid'])->update(['sort_order' => $item['sort_order']]);
		}
	}
}

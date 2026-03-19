<?php

namespace App\Actions\PublicationBlockLink;

use App\Models\PublicationBlock;
use App\Models\PublicationBlockLink;

class StoreAction
{
	public function execute(PublicationBlock $block, array $data): PublicationBlockLink
	{
		return $block->links()->create($data);
	}
}

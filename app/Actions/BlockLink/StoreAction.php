<?php

namespace App\Actions\BlockLink;

use App\Models\Block;
use App\Models\BlockLink;

class StoreAction
{
	public function execute(Block $block, array $data): BlockLink
	{
		return $block->links()->create($data);
	}
}

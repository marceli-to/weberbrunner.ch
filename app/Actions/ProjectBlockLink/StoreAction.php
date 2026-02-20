<?php

namespace App\Actions\ProjectBlockLink;

use App\Models\ProjectBlock;
use App\Models\ProjectBlockLink;

class StoreAction
{
	public function execute(ProjectBlock $block, array $data): ProjectBlockLink
	{
		return $block->links()->create($data);
	}
}

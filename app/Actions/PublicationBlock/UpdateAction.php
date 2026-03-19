<?php

namespace App\Actions\PublicationBlock;

use App\Models\PublicationBlock;

class UpdateAction
{
	public function execute(PublicationBlock $block, array $data): PublicationBlock
	{
		$block->update($data);
		return $block;
	}
}

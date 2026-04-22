<?php

namespace App\Actions\Block;

use App\Models\Block;

class UpdateAction
{
	public function execute(Block $block, array $data): Block
	{
		$block->update($data);

		return $block;
	}
}

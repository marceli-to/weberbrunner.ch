<?php

namespace App\Actions\ProjectBlock;

use App\Models\ProjectBlock;

class UpdateAction
{
	public function execute(ProjectBlock $block, array $data): ProjectBlock
	{
		$block->update($data);

		return $block;
	}
}

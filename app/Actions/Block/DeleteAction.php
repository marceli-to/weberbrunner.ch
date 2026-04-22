<?php

namespace App\Actions\Block;

use App\Models\Block;

class DeleteAction
{
	public function execute(Block $block): void
	{
		$block->media()->delete();
		$block->delete();
	}
}

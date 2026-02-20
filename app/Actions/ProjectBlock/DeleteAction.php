<?php

namespace App\Actions\ProjectBlock;

use App\Models\ProjectBlock;

class DeleteAction
{
	public function execute(ProjectBlock $block): void
	{
		$block->media()->delete();
		$block->delete();
	}
}
